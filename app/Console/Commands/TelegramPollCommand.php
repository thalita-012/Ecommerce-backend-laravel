<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramPollCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:poll {--timeout=30 : Timeout for long polling in seconds}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Poll Telegram API for incoming messages and commands (for local development)';

    /**
     * Telegram service instance.
     */
    protected $telegramService;

    /**
     * Create a new command instance.
     */
    public function __construct(TelegramService $telegramService)
    {
        parent::__construct();
        $this->telegramService = $telegramService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $token = config('services.telegram.bot_token');
        if (!$token) {
            $this->error("TELEGRAM_BOT_TOKEN is not configured. Please add it to your .env file.");
            return Command::FAILURE;
        }

        $this->info("Starting Telegram Bot Polling (Thalita Ecommerce Bot)... Press Ctrl+C to stop.");
        $offset = 0;
        $timeout = (int) $this->option('timeout');

        // Loop indefinitely
        while (true) {
            $url = "https://api.telegram.org/bot{$token}/getUpdates";
            
            try {
                // We add 5 seconds to the Laravel HTTP client timeout to prevent it from timing out before Telegram's poll timeout does
                $response = Http::withoutVerifying()->timeout($timeout + 5)->post($url, [
                    'offset' => $offset,
                    'timeout' => $timeout,
                ]);

                if ($response->failed()) {
                    $this->error("Failed to connect to Telegram: " . $response->body());
                    sleep(5);
                    continue;
                }

                $data = $response->json();
                if (isset($data['ok']) && $data['ok']) {
                    $updates = $data['result'] ?? [];

                    foreach ($updates as $update) {
                        $updateId = $update['update_id'];
                        $offset = $updateId + 1;

                        $this->info("Received update #{$updateId}. Processing...");
                        
                        try {
                            $this->telegramService->handleUpdate($update);
                        } catch (\Exception $e) {
                            $this->error("Error processing update #{$updateId}: " . $e->getMessage());
                            Log::error("Error processing Telegram update: " . $e->getMessage(), ['update' => $update]);
                        }
                    }
                } else {
                    $this->error("Telegram response error: " . json_encode($data));
                    sleep(5);
                }
            } catch (\Exception $e) {
                $this->error("Error polling Telegram: " . $e->getMessage());
                sleep(5);
            }
        }

        return Command::SUCCESS;
    }
}
