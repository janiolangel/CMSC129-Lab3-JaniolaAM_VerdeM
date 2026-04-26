<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AIService;
use App\Services\PromptService;
use App\Services\FunctionCallService;

class ChatController extends Controller
{
    public function chat(Request $request, AIService $ai, PromptService $prompt, FunctionCallService $func)
    {
        $message = $request->input('message');

        $history = session('chat_history', []);
        $history[] = $message;
        session(['chat_history' => array_slice($history, -10)]);

        $promptText = $prompt->buildPrompt($message, $history);

        $response = $ai->ask($promptText);

        $clean = trim($response);
        $clean = preg_replace('/```json|```/', '', $clean);

        $decoded = json_decode($clean, true);

        if (!$decoded) {
            return response()->json([
                'reply' => "Something went wrong while understanding your request. Try rephrasing it.",
                'raw' => $response
            ]);
        }

        $result = $func->handle($decoded);

        return response()->json([
            'reply' => $result,
            'ai' => $decoded
        ]);
    }
}