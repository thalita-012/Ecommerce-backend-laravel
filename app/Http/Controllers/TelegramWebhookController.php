<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TelegramService;

class TelegramWebhookController extends Controller
{
    /**
     * Telegram service instance.
     */
    protected $telegramService;

    /**
     * Create a new controller instance.
     */
    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    /**
     * Handle the incoming Telegram webhook request.
     */
    public function handle(Request $request)
    {
        $update = $request->all();
        
        if (!empty($update)) {
            $this->telegramService->handleUpdate($update);
        }

        return response()->json(['status' => 'success']);
    }
}
