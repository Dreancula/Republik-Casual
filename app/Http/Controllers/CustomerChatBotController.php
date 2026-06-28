<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CustomerChatBotController extends Controller
{
    public function getBotResponse(Request $request)
    {
        $userMessage = $request->input('message');
        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            return response()->json([
                'reply' => 'Halo Kak! Fitur chat AI kami sedang istirahat sebentar. Boleh hubungi kami via WhatsApp ya!'
            ]);
        }

        try {
            // URL menggunakan v1beta karena gemini-1.5-flash stabil berada di jalur ini untuk API publik gratisan
            // Ganti modelnya menjadi gemini-2.5-flash (Model paling kilat dan update)
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->withoutVerifying()->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey, [
                        'contents' => [
                            [
                                'parts' => [
                                    [
                                        'text' => "Kamu adalah 'RC Asisten' dari brand streetwear 'Republik Casual'. Jawablah dengan sangat ramah, singkat, padat, dan gunakan Bahasa Indonesia. Selalu panggil customer dengan sebutan 'Kak'. Fokus utamanya adalah membantu seputar produk baju, cargo, kemeja, celana, cek stok barang, kurir pengiriman, dan cara order.\n\nCustomer: " . $userMessage
                                    ]
                                ]
                            ]
                        ]
                    ]);

            if ($response->successful()) {
                $result = $response->json();
                $botReply = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;

                if ($botReply) {
                    return response()->json(['reply' => trim($botReply)]);
                }
            }

            // Kembalikan ke pesan ramah untuk user jika API mengalami kendala limitasi
            // GANTI SEMENTARA BARIS INI UNTUK MELIHAT ALASANNYA:
            return response()->json(['reply' => 'Gagal! Alasan Google menolak: ' . $response->body()]);

        } catch (\Exception $e) {
            return response()->json(['reply' => 'Maaf Kak, ada gangguan koneksi ke otak AI. Coba sesaat lagi ya.']);
        }
    }
}