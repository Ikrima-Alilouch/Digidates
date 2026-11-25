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

        // Get all users except current, admin, and banned ones, with their tags
        $users = User::with('tags')
            ->where('id', '!=', $currentUser->id)
            ->where('role', '!=', 'admin')
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

    public function show($id)
    {
        $currentUser = Auth::user();
        $user = User::with('tags')->findOrFail($id);

        // Calculate overlap score
        $currentUserTagIds = $currentUser->tags->pluck('id')->toArray();
        $userTagIds = $user->tags->pluck('id')->toArray();
        $overlap = array_intersect($currentUserTagIds, $userTagIds);
        
        $user->overlap_score = count($overlap);
        $totalCurrentUserTags = count($currentUserTagIds);
        $totalUserTags = count($userTagIds);
        
        // Calculate a percentage match for the UI (e.g., 84%)
        // Simple logic: overlap / max(tags) or similar. 
        // Let's use intersection over union or just a simple percentage of user's tags.
        // For a dating app vibe, let's use a base compatibility + overlap percentage
        $maxTags = max(1, count(array_unique(array_merge($currentUserTagIds, $userTagIds))));
        // Actually Laravel doesn't have Math::round, just using round()
        $matchPercentage = min(100, (int)round((count($overlap) / $maxTags) * 100));
        
        // If there are no tags, maybe provide a default semi-random or 0%
        if ($maxTags === 1 && count($overlap) === 0) {
            $matchPercentage = 0;
        }

        // Like & Match logic
        $hasLiked = \App\Models\Like::where('user_id', $currentUser->id)
            ->where('liked_user_id', $user->id)
            ->exists();

        $isMatch = false;
        if ($hasLiked) {
            $isMatch = \App\Models\Like::where('user_id', $user->id)
                ->where('liked_user_id', $currentUser->id)
                ->exists();
        }

        return view('profiles.show', compact('user', 'matchPercentage', 'hasLiked', 'isMatch'));
    }

    public function edit()
    {
        $user = Auth::user();
        $user->load('tags');

        $allTags = Tag::all();
        // Extract tag IDs for frontend checkboxes
        $userTags = $user->tags->pluck('id')->toArray();

        return view('profiles.edit', compact('user', 'allTags', 'userTags'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'biography' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',
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
        if ($request->has('tags') && is_array($request->tags)) {
            $user->tags()->sync($request->tags);
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
