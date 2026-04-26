<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    public function ask($message, $provider = null)
    {
        $this->log("         NEW REQUEST        ");
        $this->log("User Message: " . $message);

        if (!$provider || $provider === 'gemini') {
            $this->log("Using Gemini as PRIMARY AI");
            return $this->askGeminiWithFallback($message);
        }

        $this->log("Using Groq directly");
        return $this->askGroq($message);
    }

    private function askGeminiWithFallback($message)
    {
        try {
            $this->log("Calling Gemini API...");
            return $this->askGemini($message);
        } catch (\Exception $e) {
            $this->log("Gemini FAILED: " . $e->getMessage());
            $this->log("Switching to Groq (FALLBACK)");

            return $this->askGroq($message);
        }
    }

    private function askGemini($message)
    {
        $apiKey = env('GEMINI_API_KEY');

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=$apiKey";

        $response = Http::timeout(10)->post($url, [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $message]
                    ]
                ]
            ]
        ]);

        if (!$response->successful()) {
            throw new \Exception('Gemini API failed with status ' . $response->status());
        }

        return $response['candidates'][0]['content']['parts'][0]['text']
            ?? 'No response from Gemini';
    }

    private function askGroq($message)
    {
        $this->log("Calling Groq API...");

        $startTime = microtime(true);

        $response = Http::timeout(10)->withHeaders([
            'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
            'Content-Type'  => 'application/json',
        ])->post(env('GROQ_BASE_URL') . '/chat/completions', [
            'model' => 'llama-3.1-8b-instant',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                ['role' => 'user', 'content' => $message]
            ],
        ]);

        $this->log("Groq Key Exists: " . (env('GROQ_API_KEY') ? 'YES' : 'NO'));

        $duration = microtime(true) - $startTime;

        $this->log("Groq Response Status: " . $response->status());
        $this->log("Groq Execution Time: " . $duration . " seconds");

        $this->log("Groq Raw Response: " . json_encode($response->json(), JSON_PRETTY_PRINT));

        if (!$response->successful()) {
            $this->log("Groq FAILED");
            return 'Gemini and Groq both failed.';
        }

        $text = $response['choices'][0]['message']['content']
            ?? 'No response from Groq';

        $this->log("Groq Reply: " . $text);

        return $text;
    }

    //logger to debug
    private function log($message)
    {
        Log::info('[AIService] ' . $message);
    }
}