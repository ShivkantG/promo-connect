<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            // 'conversation_id' => 'required',
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'required'
        ]);

        $conversation = Conversation::findOrFail($request->conversation_id);
        // Security check
        if (
            $conversation->brand_id !== Auth::id() &&
            $conversation->promoter_id !== Auth::id()
        ) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }


        $user = Auth::user();
        $message = Message::create([
            'conversation_id' => $request->conversation_id,
            'sender_type' =>  $user->role, // brand/promoter
            'sender_id' => $user->id,
            'message' => $request->message
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'status' => true,
            'data' => $message
        ]);
    }

    public function getOrCreateConversation(Request $request)
    {
        $request->validate([
            'brand_id' => 'required|exists:users,id',
        ]);

        $conversation = Conversation::firstOrCreate([
            'brand_id' => $request->brand_id,
            // 'promoter_id' => auth()->id(),
            'promoter_id' => Auth::id(),
        ]);

        return response()->json([
            'conversation_id' => $conversation->id
        ]);
    }

    public function getMessages($conversationId)
    {
        // return Message::where('conversation_id', $conversationId)->get();
        $conversation = Conversation::findOrFail($conversationId);
        if (
            $conversation->brand_id !== Auth::id() &&
            $conversation->promoter_id !== Auth::id()
        ) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return Message::where('conversation_id', $conversationId)
            ->orderBy('id', 'asc')
            ->get();
    }
}
