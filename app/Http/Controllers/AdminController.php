<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Get all users, including the banned ones (soft deleted)
        // Optionally sort them or filter admin out, but requirement: "Je moet alle profielen kunnen zien"
        $users = User::withTrashed()
            ->where('role', '!=', 'admin') // exclude admins themselves maybe? Or just show everyone. Let's just show everyone.
            ->orderBy('id', 'desc')
            ->get();
            
        return view('admin.dashboard', compact('users'));
    }

    public function ban($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent admin from banning themselves
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Je kunt jezelf niet bannen.']);
        }

        $user->delete(); // Soft delete

        return back()->with('success', "Gebruiker {$user->first_name} is succesvol geband.");
    }

    public function unban($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        
        $user->restore(); // Restore from soft delete
        $user->banned = false; // Just in case, update the legacy banned field
        $user->save();

        return back()->with('success', "Gebruiker {$user->first_name} is succesvol geünband.");
    }
}
