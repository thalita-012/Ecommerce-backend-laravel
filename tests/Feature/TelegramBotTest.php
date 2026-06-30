<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TelegramBotTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.telegram.bot_token' => 'test-token']);
        config(['services.telegram.admin_chat_id' => '12345']);
    }

    public function test_telegram_service_formats_order_correctly()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('secret_pass'),
            'is_admin' => true,
        ]);

        $category = Category::create([
            'name' => 'Sample Category',
            'slug' => 'sample-category',
            'description' => 'Sample Category Description',
        ]);

        $product = Product::create([
            'name' => 'Sample Product',
            'slug' => 'sample-product',
            'description' => 'Sample Product Description',
            'price' => 100.00,
            'stock' => 10,
            'category_id' => $category->id,
        ]);

        $order = Order::create([
            'user_id' => $admin->id,
            'total_price' => 100.00,
            'status' => 'pending',
            'shipping_address' => '123 Test St',
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 100.00,
        ]);

        $telegramService = app(TelegramService::class);
        $formatted = $telegramService->formatOrder($order);

        $this->assertStringContainsString('Order #' . $order->id, $formatted);
        $this->assertStringContainsString('Sample Product x1', $formatted);
        $this->assertStringContainsString('123 Test St', $formatted);
        $this->assertStringContainsString('PENDING', $formatted);
    }

    public function test_webhook_handles_start_command()
    {
        Http::fake([
            'https://api.telegram.org/bot*' => Http::response(['ok' => true], 200),
        ]);

        $response = $this->postJson('/api/telegram/webhook', [
            'update_id' => 1000,
            'message' => [
                'message_id' => 1,
                'chat' => [
                    'id' => 12345,
                    'type' => 'private',
                ],
                'text' => '/start',
            ],
        ]);

        $response->assertStatus(200);
        
        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.telegram.org/bottest-token/sendMessage' &&
                   $request['chat_id'] == 12345 &&
                   str_contains($request['text'], 'Welcome');
        });
    }

    public function test_webhook_handles_admin_login()
    {
        Http::fake([
            'https://api.telegram.org/bot*' => Http::response(['ok' => true], 200),
        ]);

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('secret_pass'),
            'is_admin' => true,
        ]);

        $response = $this->postJson('/api/telegram/webhook', [
            'update_id' => 1001,
            'message' => [
                'message_id' => 2,
                'chat' => [
                    'id' => 54321,
                    'type' => 'private',
                ],
                'text' => '/login admin@test.com secret_pass',
            ],
        ]);

        $response->assertStatus(200);

        // Verify admin chat id was saved
        $admin->refresh();
        $this->assertEquals('54321', $admin->telegram_chat_id);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.telegram.org/bottest-token/sendMessage' &&
                   $request['chat_id'] == 54321 &&
                   str_contains($request['text'], 'Success');
        });
    }

    public function test_webhook_handles_callback_status_update()
    {
        Http::fake([
            'https://api.telegram.org/bot*' => Http::response(['ok' => true], 200),
        ]);

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('secret_pass'),
            'is_admin' => true,
            'telegram_chat_id' => '54321',
        ]);

        $category = Category::create([
            'name' => 'Sample Category',
            'slug' => 'sample-category',
            'description' => 'Sample Category Description',
        ]);

        $product = Product::create([
            'name' => 'Sample Product',
            'slug' => 'sample-product',
            'description' => 'Sample Product Description',
            'price' => 50.00,
            'stock' => 10,
            'category_id' => $category->id,
        ]);

        $order = Order::create([
            'user_id' => $admin->id,
            'total_price' => 50.00,
            'status' => 'pending',
            'shipping_address' => '123 Test St',
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 50.00,
        ]);

        $response = $this->postJson('/api/telegram/webhook', [
            'update_id' => 1002,
            'callback_query' => [
                'id' => 'query_123',
                'data' => "order_status:{$order->id}:processing",
                'message' => [
                    'message_id' => 999,
                    'chat' => [
                        'id' => 54321,
                    ],
                ],
            ],
        ]);

        $response->assertStatus(200);

        // Verify status was updated
        $order->refresh();
        $this->assertEquals('processing', $order->status);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.telegram.org/bottest-token/answerCallbackQuery';
        });

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.telegram.org/bottest-token/editMessageText' &&
                   str_contains($request['text'], 'PROCESSING') &&
                   str_contains($request['text'], 'Admin User');
        });
    }

    public function test_placing_order_sends_notification_to_admins()
    {
        Http::fake([
            'https://api.telegram.org/bot*' => Http::response(['ok' => true], 200),
        ]);

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('secret_pass'),
            'is_admin' => true,
            'telegram_chat_id' => '54321',
        ]);

        $customer = User::create([
            'name' => 'Customer User',
            'email' => 'customer@test.com',
            'password' => Hash::make('secret_pass'),
            'is_admin' => false,
        ]);

        $category = Category::create([
            'name' => 'Sample Category',
            'slug' => 'sample-category',
            'description' => 'Sample Category Description',
        ]);

        $product = Product::create([
            'name' => 'Sample Product',
            'slug' => 'sample-product',
            'description' => 'Sample Product Description',
            'price' => 50.00,
            'stock' => 10,
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($customer, 'sanctum')->postJson('/api/orders', [
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ]
            ],
            'shipping_address' => 'Customer Address 123',
        ]);

        $response->assertStatus(201);

        // Verify Telegram notifications sent to both fallback chat ID (12345) and active admin chat ID (54321)
        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.telegram.org/bottest-token/sendMessage' &&
                   $request['chat_id'] == '12345';
        });

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.telegram.org/bottest-token/sendMessage' &&
                   $request['chat_id'] == '54321';
        });
    }
}
