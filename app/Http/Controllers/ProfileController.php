<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();

        // Get all users except current and banned ones, with their tags
        $users = User::with('tags')
            ->where('id', '!=', $currentUser->id)
            ->where('banned', false)
            ->get();

        // Current user's tag IDs
        $currentUserTagIds = $currentUser->tags->pluck('id')->toArray();

        // Calculate overlap score and sort
        $users = $users->map(function ($user) use ($currentUserTagIds) {
            $userTagIds = $user->tags->pluck('id')->toArray();
            $overlap = array_intersect($currentUserTagIds, $userTagIds);
            
            // Assign overlap score as a temporary attribute for sorting
            $user->overlap_score = count($overlap);
            return $user;
        })->sortByDesc('overlap_score')->values();

        return view('profiles.index', compact('users'));
    }

    public function edit()
    {
        $user = Auth::user();
        $user->load('tags');
        
        $allTags = Tag::all();
        // Extract tag names for easier frontend handling
        $userTags = $user->tags->pluck('name')->toArray();

        return view('profiles.edit', compact('user', 'allTags', 'userTags'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'biography' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048', // max 2MB
            'tags' => 'nullable|string', // comma separated strings
            'photo_visible' => 'nullable|boolean',
            'interests_visible' => 'nullable|boolean',
            'bio_visible' => 'nullable|boolean',
        ]);

        $user->first_name = $validated['first_name'] ?? $user->first_name;
        $user->last_name = $validated['last_name'] ?? $user->last_name;
        $user->biography = $validated['biography'] ?? $user->biography;
        
        // Handle photo upload
        if ($request->hasFile('profile_photo')) {
            $imagePath = $request->file('profile_photo')->path();
            
            // Resize and compress the image to prevent max_allowed_packet errors limit
            $img = imagecreatefromstring(file_get_contents($imagePath));
            if ($img !== false) {
                $width = imagesx($img);
                $height = imagesy($img);
                
                $maxSize = 800; // max width/height in pixels
                if ($width > $maxSize || $height > $maxSize) {
                    $ratio = $width / $height;
                    if ($ratio > 1) {
                        $newWidth = $maxSize;
                        $newHeight = $maxSize / $ratio;
                    } else {
                        $newHeight = $maxSize;
                        $newWidth = $maxSize * $ratio;
                    }
                    
                    $resized = imagecreatetruecolor((int)$newWidth, (int)$newHeight);
                    
                    // Handle transparency for PNGs momentarily to avoid black backgrounds
                    imagealphablending($resized, false);
                    imagesavealpha($resized, true);
                    $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
                    imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $transparent);
                    
                    imagecopyresampled($resized, $img, 0, 0, 0, 0, (int)$newWidth, (int)$newHeight, $width, $height);
                    imagedestroy($img);
                    $img = $resized;
                }
                
                ob_start();
                // Output as PNG or WEBP since JPEG support is missing in this PHP build
                if (function_exists('imagejpeg')) {
                    imagejpeg($img, null, 80); 
                } elseif (function_exists('imagepng')) {
                    imagepng($img, null, 8); // Compression level 0-9
                }
                $user->profile_photo = ob_get_clean();
                imagedestroy($img);
            } else {
                // Fallback
                $user->profile_photo = file_get_contents($imagePath);
            }
        }

        // Handle privacy toggles
        $user->photo_visible = $request->has('photo_visible');
        $user->interests_visible = $request->has('interests_visible');
        $user->bio_visible = $request->has('bio_visible');

        $user->save();

        // Handle tags
        if ($request->has('tags')) {
            $tagNames = array_filter(array_map('trim', explode(',', $request->tags)));
            $tagIds = [];
            
            foreach ($tagNames as $tagName) {
                if (!empty($tagName)) {
                    $tag = Tag::firstOrCreate(['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }
            }
            
            $user->tags()->sync($tagIds);
        } else {
            // clear tags if empty
            $user->tags()->sync([]);
        }

        return redirect()->route('profiles.edit')->with('success', 'Profile updated successfully.');
    }

    public function photo($id)
    {
        $user = User::findOrFail($id);
        
        if (!$user->profile_photo) {
            abort(404);
        }
        
        // If not the current user, check if photo is visible
        if (Auth::id() !== $user->id && !$user->photo_visible) {
            abort(404);
        }

        $mime = 'image/jpeg';
        if (function_exists('finfo_open')) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->buffer($user->profile_photo) ?: 'image/jpeg';
        }

        return response($user->profile_photo)
            ->header('Content-Type', $mime)
            ->header('Cache-Control', 'max-age=86400');
    }
}
