<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required',
            'message' => 'required'
        ]);

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

    public function getMessages($conversationId)
    {
        return Message::where('conversation_id', $conversationId)->get();
    }
}
