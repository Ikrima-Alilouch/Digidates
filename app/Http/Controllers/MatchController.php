<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatchController extends Controller
{
    public function like($id)
    {
        $currentUser = Auth::user();

        // Check if target user exists, is not banned, is not admin
        $targetUser = User::where('role', '!=', 'admin')
            ->where('banned', false)
            ->findOrFail($id);

        if ($currentUser->id === $targetUser->id) {
            return back()->with('error', 'Je kunt jezelf niet liken.');
        }

        // Check if like already exists
        $existingLike = Like::where('user_id', $currentUser->id)
            ->where('liked_user_id', $targetUser->id)
            ->first();

        if (!$existingLike) {
            Like::create([
                'user_id' => $currentUser->id,
                'liked_user_id' => $targetUser->id,
            ]);
        }

        // Check if mutual match
        $mutualLike = Like::where('user_id', $targetUser->id)
            ->where('liked_user_id', $currentUser->id)
            ->exists();

        if ($mutualLike) {
            return back()->with('success', 'It\'s a match! Je kunt nu chatten met ' . $targetUser->first_name . '.');
        }

        return back()->with('success', 'Je hebt ' . $targetUser->first_name . ' geliket.');
    }

    public function unmatch($id)
    {
        $currentUser = Auth::user();

        // Check if there is a match to unmatch
        $mutualLike = Like::where('user_id', $currentUser->id)
            ->where('liked_user_id', $id)
            ->exists();

        if ($mutualLike) {
            // Delete likes in both directions to completely unmatch
            Like::where(function($q) use ($currentUser, $id) {
                $q->where('user_id', $currentUser->id)->where('liked_user_id', $id);
            })->orWhere(function($q) use ($currentUser, $id) {
                $q->where('user_id', $id)->where('liked_user_id', $currentUser->id);
            })->delete();

            return redirect()->route('chat.index')->with('success', 'Match succesvol verbroken.');
        }

        return redirect()->route('chat.index')->with('error', 'Kon match niet verbreken.');
    }
}
