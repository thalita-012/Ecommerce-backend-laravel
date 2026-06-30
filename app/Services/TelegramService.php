<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class TelegramService
{
    /**
     * Call Telegram API method.
     */
    protected function api(string $method, array $params = [])
    {
        $token = config('services.telegram.bot_token');
        if (!$token) {
            Log::error("Telegram Bot Token is not set in services config.");
            return null;
        }

        if (isset($params['reply_markup']) && is_array($params['reply_markup'])) {
            $params['reply_markup'] = json_encode($params['reply_markup']);
        }

        $url = "https://api.telegram.org/bot{$token}/{$method}";

        try {
            Log::debug("Sending post request to URL: " . $url . " with params: " . json_encode($params));
            $response = Http::withoutVerifying()->timeout(10)->post($url, $params);
            Log::debug("Response status: " . $response->status() . " Body: " . $response->body());
            if ($response->failed()) {
                Log::error("Telegram API response failed for method {$method}: " . $response->body());
            }
            return $response->json();
        } catch (\Exception $e) {
            Log::error("Telegram API call error for method {$method}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Send raw message.
     */
    public function sendMessage(string $chatId, string $text, array $options = [])
    {
        return $this->api('sendMessage', array_merge([
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ], $options));
    }

    /**
     * Edit message text.
     */
    public function editMessageText(string $chatId, int $messageId, string $text, array $options = [])
    {
        return $this->api('editMessageText', array_merge([
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ], $options));
    }

    /**
     * Answer callback query.
     */
    public function answerCallbackQuery(string $callbackQueryId, string $text = '', bool $showAlert = false)
    {
        return $this->api('answerCallbackQuery', [
            'callback_query_id' => $callbackQueryId,
            'text' => $text,
            'show_alert' => $showAlert,
        ]);
    }

    /**
     * Format Order object as HTML.
     */
    public function formatOrder(Order $order, string $updatedBy = null): string
    {
        $order->load(['user', 'items.product']);
        
        $itemsStr = '';
        foreach ($order->items as $item) {
            $productName = $item->product ? htmlspecialchars($item->product->name) : 'Unknown Product';
            $itemsStr .= "• {$productName} x{$item->quantity} - <b>$" . number_format($item->price * $item->quantity, 2) . "</b>\n";
        }

        $customerName = htmlspecialchars($order->user->name ?? 'Guest/Deleted User');
        $statusText = strtoupper($order->status);
        $statusEmoji = match ($order->status) {
            'pending' => '⏳',
            'processing' => '⚙️',
            'completed' => '✅',
            'cancelled' => '❌',
            default => '❓'
        };

        $shippingAddress = htmlspecialchars($order->shipping_address ?? 'N/A');

        $text = "📦 <b>Order #{$order->id} Details</b>\n" .
               "-----------------------------------\n" .
               "👤 <b>Customer:</b> {$customerName}\n" .
               "💵 <b>Total:</b> $" . number_format($order->total_price, 2) . "\n" .
               "📍 <b>Address:</b> {$shippingAddress}\n" .
               "🚦 <b>Status:</b> {$statusEmoji} <code>{$statusText}</code>\n" .
               "-----------------------------------\n" .
               "🛒 <b>Items:</b>\n" .
               $itemsStr;

        if ($updatedBy) {
            $text .= "\n🔄 <b>Action:</b> Updated to <code>{$statusText}</code> by <i>{$updatedBy}</i>";
        } else if (in_array($order->status, ['pending', 'processing'])) {
            $text .= "\n⚡ <i>Change the order status using the buttons below:</i>";
        }

        return $text;
    }

    /**
     * Generate inline keyboard buttons for Order status changes.
     */
    protected function getOrderButtons(Order $order): array
    {
        if (in_array($order->status, ['completed', 'cancelled'])) {
            return ['inline_keyboard' => []];
        }

        $buttons = [];
        $row1 = [];

        if ($order->status === 'pending') {
            $row1[] = [
                'text' => '⚙️ Accept & Process',
                'callback_data' => "order_status:{$order->id}:processing"
            ];
        }

        $row1[] = [
            'text' => '✅ Complete',
            'callback_data' => "order_status:{$order->id}:completed"
        ];

        $buttons[] = $row1;

        $buttons[] = [
            [
                'text' => '❌ Cancel Order',
                'callback_data' => "order_status:{$order->id}:cancelled"
            ]
        ];

        return [
            'inline_keyboard' => $buttons
        ];
    }

    /**
     * Send order notification to all authenticated admins.
     */
    public function sendOrderNotification(Order $order): void
    {
        $text = $this->formatOrder($order);
        $buttons = $this->getOrderButtons($order);

        $chatIds = [];

        // 1. Get direct chat ID from env configuration
        $fallbackChatId = config('services.telegram.admin_chat_id');
        if ($fallbackChatId) {
            $chatIds[] = $fallbackChatId;
        }

        // 2. Get dynamic chat IDs from database for logged in admins
        $adminChatIds = User::where('is_admin', true)
            ->whereNotNull('telegram_chat_id')
            ->pluck('telegram_chat_id')
            ->toArray();

        $chatIds = array_unique(array_merge($chatIds, $adminChatIds));

        foreach ($chatIds as $chatId) {
            $this->sendMessage($chatId, $text, [
                'reply_markup' => $buttons,
            ]);
        }
    }

    /**
     * Handle incoming updates from Telegram Webhook or Long Polling.
     */
    public function handleUpdate(array $update): void
    {
        Log::debug("handleUpdate incoming payload: " . json_encode($update));
        // 1. Handle Callback Query (Button clicks)
        if (isset($update['callback_query'])) {
            $this->handleCallbackQuery($update['callback_query']);
            return;
        }

        // 2. Handle Text Message
        if (isset($update['message']['text'])) {
            $this->handleTextMessage($update['message']);
            return;
        }
    }

    /**
     * Handle incoming callback queries.
     */
    protected function handleCallbackQuery(array $callbackQuery): void
    {
        $id = $callbackQuery['id'];
        $data = $callbackQuery['data'];
        $chatId = $callbackQuery['message']['chat']['id'];
        $messageId = $callbackQuery['message']['message_id'];

        // Verify sender is authorized
        $admin = $this->getAdminByChatId($chatId);
        if (!$admin) {
            $this->answerCallbackQuery($id, '🔒 Access Denied. Please log in first.', true);
            return;
        }

        // Expected format: order_status:{order_id}:{status}
        if (str_starts_with($data, 'order_status:')) {
            $parts = explode(':', $data);
            if (count($parts) === 3) {
                $orderId = $parts[1];
                $newStatus = $parts[2];

                if (!in_array($newStatus, ['processing', 'completed', 'cancelled'])) {
                    $this->answerCallbackQuery($id, '❌ Invalid status change request.', true);
                    return;
                }

                $order = Order::find($orderId);
                if (!$order) {
                    $this->answerCallbackQuery($id, '❌ Order not found.', true);
                    return;
                }

                // Update status
                $order->status = $newStatus;
                $order->save();

                $adminName = $admin->name ?? 'Admin';
                $this->answerCallbackQuery($id, "✅ Order #{$orderId} status changed to {$newStatus}!");

                // Refresh the message details and update the buttons
                $updatedText = $this->formatOrder($order, $adminName);
                $updatedButtons = $this->getOrderButtons($order);

                $this->editMessageText($chatId, $messageId, $updatedText, [
                    'reply_markup' => $updatedButtons
                ]);
            }
        }
    }

    /**
     * Handle incoming text messages/commands.
     */
    protected function handleTextMessage(array $message): void
    {
        $chatId = $message['chat']['id'];
        $text = trim($message['text']);

        // Route commands
        if ($text === '/start') {
            $this->sendStartResponse($chatId);
        } elseif ($text === '/help') {
            $this->sendHelpResponse($chatId);
        } elseif (str_starts_with($text, '/login')) {
            $this->handleLoginCommand($chatId, $text);
        } elseif ($text === '/logout') {
            $this->handleLogoutCommand($chatId);
        } elseif ($text === '/orders') {
            $this->handleOrdersCommand($chatId);
        } elseif (str_starts_with($text, '/status')) {
            $this->handleStatusCommand($chatId, $text);
        } else {
            $this->sendMessage($chatId, "❓ Unknown command. Send /help to see all available commands.");
        }
    }

    /**
     * Send /start response.
     */
    protected function sendStartResponse(string $chatId): void
    {
        $appName = env('APP_NAME', 'Thalita Ecommerce');
        $text = "👋 Welcome to the <b>{$appName} Admin Bot</b>!\n\n" .
                "This chatbot allows store administrators to receive real-time order notifications and process order updates (Accept, Complete, Cancel) directly from Telegram.\n\n" .
                "🔑 <b>Login Required</b>\n" .
                "To link your admin account, please type:\n" .
                "<code>/login [your_email] [your_password]</code>\n\n" .
                "💡 <i>Example:</i> <code>/login admin@example.com password</code>";
        
        $this->sendMessage($chatId, $text);
    }

    /**
     * Send /help response.
     */
    protected function sendHelpResponse(string $chatId): void
    {
        $text = "📋 <b>Available Admin Commands:</b>\n" .
                "• <code>/login [email] [password]</code> - Link your admin account to start receiving order alerts.\n" .
                "• <code>/logout</code> - Unlink your admin account from this chat.\n" .
                "• <code>/orders</code> - Display the 5 most recent pending or processing orders.\n" .
                "• <code>/status [order_id]</code> - View detailed info and status of a specific order.\n" .
                "• <code>/help</code> - Show this list of command options.";

        $this->sendMessage($chatId, $text);
    }

    /**
     * Handle the /login command.
     */
    protected function handleLoginCommand(string $chatId, string $text): void
    {
        $parts = preg_split('/\s+/', $text);
        if (count($parts) < 3) {
            $this->sendMessage($chatId, "⚠️ <b>Invalid format.</b> Use:\n<code>/login [email] [password]</code>");
            return;
        }

        $email = $parts[1];
        $password = $parts[2];

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password) || !$user->is_admin) {
            Log::warning("Unauthorized bot login attempt for email: {$email} (Chat ID: {$chatId})");
            $this->sendMessage($chatId, "❌ <b>Access Denied.</b> Invalid email/password, or you do not have admin privileges.");
            return;
        }

        // Store chat ID
        $user->telegram_chat_id = $chatId;
        $user->save();

        Log::info("Admin user {$user->name} linked their Telegram chat ID ({$chatId})");

        $this->sendMessage($chatId, "✅ <b>Success!</b> Your Telegram account has been linked to admin user <b>" . htmlspecialchars($user->name) . "</b>.\n\nYou will now receive real-time order alerts here.");
    }

    /**
     * Handle the /logout command.
     */
    protected function handleLogoutCommand(string $chatId): void
    {
        $user = User::where('telegram_chat_id', $chatId)->first();

        if (!$user) {
            $this->sendMessage($chatId, "⚠️ You are not currently logged in.");
            return;
        }

        $user->telegram_chat_id = null;
        $user->save();

        Log::info("Admin user {$user->name} unlinked their Telegram chat ID ({$chatId})");

        $this->sendMessage($chatId, "🚪 <b>Logged out successfully.</b> You will no longer receive order alerts in this chat.");
    }

    /**
     * Handle the /orders command.
     */
    protected function handleOrdersCommand(string $chatId): void
    {
        $admin = $this->getAdminByChatId($chatId);
        if (!$admin) {
            $this->sendMessage($chatId, "🔒 <b>Access Denied.</b> Please log in first using <code>/login [email] [password]</code>.");
            return;
        }

        $orders = Order::whereIn('status', ['pending', 'processing'])
            ->latest()
            ->take(5)
            ->get();

        if ($orders->isEmpty()) {
            $this->sendMessage($chatId, "🎉 <b>No active orders!</b> There are currently no pending or processing orders.");
            return;
        }

        $this->sendMessage($chatId, "🔍 <b>Fetching the 5 most recent active orders:</b>");

        foreach ($orders as $order) {
            $text = $this->formatOrder($order);
            $buttons = $this->getOrderButtons($order);
            $this->sendMessage($chatId, $text, [
                'reply_markup' => $buttons
            ]);
        }
    }

    /**
     * Handle the /status command.
     */
    protected function handleStatusCommand(string $chatId, string $text): void
    {
        $admin = $this->getAdminByChatId($chatId);
        if (!$admin) {
            $this->sendMessage($chatId, "🔒 <b>Access Denied.</b> Please log in first using <code>/login [email] [password]</code>.");
            return;
        }

        $parts = preg_split('/\s+/', $text);
        if (count($parts) < 2) {
            $this->sendMessage($chatId, "⚠️ <b>Invalid format.</b> Use:\n<code>/status [order_id]</code>");
            return;
        }

        $orderId = $parts[1];
        $order = Order::find($orderId);

        if (!$order) {
            $this->sendMessage($chatId, "❌ Order <b>#{$orderId}</b> not found.");
            return;
        }

        $text = $this->formatOrder($order);
        $buttons = $this->getOrderButtons($order);

        $this->sendMessage($chatId, $text, [
            'reply_markup' => $buttons
        ]);
    }

    /**
     * Helper: Check if a chat ID belongs to an admin user (or match env configuration fallback).
     */
    protected function getAdminByChatId(string $chatId): ?User
    {
        // 1. Direct database match
        $user = User::where('is_admin', true)
            ->where('telegram_chat_id', $chatId)
            ->first();

        if ($user) {
            return $user;
        }

        // 2. Check if Chat ID matches the env fallback admin chat ID
        $fallbackChatId = config('services.telegram.admin_chat_id');
        if ($fallbackChatId && $fallbackChatId == $chatId) {
            // Find any admin user to associate
            return User::where('is_admin', true)->first();
        }

        return null;
    }
}
