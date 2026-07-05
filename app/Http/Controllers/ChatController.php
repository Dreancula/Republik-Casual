<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $chats = Chat::where('id_user', Auth::id())
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($chat) {
                return [
                    'id_chat' => $chat->id_chat,
                    'is_admin' => (bool) $chat->is_admin,
                    'teks' => $chat->teks,
                    'kategori_chat' => $chat->kategori_chat,
                    'foto_chat' => $chat->foto_chat ? asset('storage/' . $chat->foto_chat) : null,
                    'tgl_chat' => $chat->created_at->toISOString(),
                ];
            });

        return response()->json($chats);
    }

    public function store(Request $request)
    {
        $request->validate([
            'teks' => 'required_without:foto_chat|string|max:1000',
            'kategori_chat' => 'required|string|max:50',
            'foto_chat' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_chat')) {
            $fotoPath = $request->file('foto_chat')->store('chat-foto', 'public');
        }

        $chat = Chat::create([
            'id_user' => Auth::id(),
            'teks' => $request->input('teks', ''),
            'kategori_chat' => $request->input('kategori_chat', 'Umum'),
            'is_admin' => false,
            'foto_chat' => $fotoPath,
        ]);

        $botReply = null;
        if ($request->filled('teks')) {
            try {
                $botRequest = new Request(['message' => $request->input('teks')]);
                $botController = app(CustomerChatBotController::class);
                $botResponse = $botController->getBotResponse($botRequest);
                $botData = $botResponse->getData();

                if (isset($botData->reply)) {
                    $botChat = Chat::create([
                        'id_user' => Auth::id(),
                        'teks' => $botData->reply,
                        'kategori_chat' => 'Bot',
                        'is_admin' => true,
                    ]);

                    $botReply = [
                        'id_chat' => $botChat->id_chat,
                        'is_admin' => true,
                        'teks' => $botChat->teks,
                        'kategori_chat' => 'Bot',
                        'foto_chat' => null,
                        'tgl_chat' => $botChat->created_at->toISOString(),
                    ];
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Bot reply error: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'chat' => [
                'id_chat' => $chat->id_chat,
                'is_admin' => false,
                'teks' => $chat->teks,
                'kategori_chat' => $chat->kategori_chat,
                'foto_chat' => $chat->foto_chat ? asset('storage/' . $chat->foto_chat) : null,
                'tgl_chat' => $chat->created_at->toISOString(),
            ],
            'bot_reply' => $botReply,
        ]);
    }
}
