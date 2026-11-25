<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    private function getMatches($userId)
    {
        // Get all users who I liked and who liked me
        $likedIds = Like::where('user_id', $userId)->pluck('liked_user_id')->toArray();
        $likedByIds = Like::where('liked_user_id', $userId)->pluck('user_id')->toArray();

        $matchIds = array_intersect($likedIds, $likedByIds);

        return User::whereIn('id', $matchIds)->get();
    }

    public function index()
    {
        $currentUser = Auth::user();
        $matches = $this->getMatches($currentUser->id);

        // Decorate matches with latest message and unread count
        $matches->map(function ($match) use ($currentUser) {
            $latestMessage = Message::where(function($q) use ($currentUser, $match) {
                    $q->where('sender_id', $currentUser->id)->where('receiver_id', $match->id);
                })
                ->orWhere(function($q) use ($currentUser, $match) {
                    $q->where('sender_id', $match->id)->where('receiver_id', $currentUser->id);
                })
                ->orderBy('created_at', 'desc')
                ->first();

            $unreadCount = Message::where('sender_id', $match->id)
                ->where('receiver_id', $currentUser->id)
                ->where('is_read', false)
                ->count();

            $match->latest_message = $latestMessage;
            $match->unread_count = $unreadCount;

            return $match;
        });

        // Sort by latest message date (descending) so most recent chats are at the top
        $matches = $matches->sortByDesc(function ($match) {
            return $match->latest_message ? $match->latest_message->created_at : '1970-01-01 00:00:00';
        })->values();

        return view('chat.index', compact('matches'));
    }

    public function show($match_id)
    {
        $currentUser = Auth::user();
        $matches = $this->getMatches($currentUser->id);

        if (!$matches->contains('id', $match_id)) {
            abort(403, 'Unauthorized. You can only chat with your matches.');
        }

        $match = User::findOrFail($match_id);

        // Mark messages as read
        Message::where('sender_id', $match->id)
            ->where('receiver_id', $currentUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Get messages
        $messages = Message::where(function($q) use ($currentUser, $match) {
                $q->where('sender_id', $currentUser->id)->where('receiver_id', $match->id);
            })
            ->orWhere(function($q) use ($currentUser, $match) {
                $q->where('sender_id', $match->id)->where('receiver_id', $currentUser->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return view('chat.show', compact('match', 'messages'));
    }

    public function store(Request $request, $match_id)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $currentUser = Auth::user();
        $matches = $this->getMatches($currentUser->id);

        if (!$matches->contains('id', $match_id)) {
            abort(403, 'Unauthorized. You can only chat with your matches.');
        }

        Message::create([
            'sender_id' => $currentUser->id,
            'receiver_id' => $match_id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return redirect()->route('chat.show', $match_id);
    }
}
