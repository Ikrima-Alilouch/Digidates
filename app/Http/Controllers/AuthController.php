<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetMail;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showRegister(): View
    {
        $tags = Tag::orderBy('name')->get();

        return view('auth.register', [
            'tags' => $tags,
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'], // provincie
            'location' => ['required', 'string', 'max:225'], // stad
            'age' => ['required', 'integer', 'min:18'],
            'gender' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:225', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'profile_photo' => ['required', 'image', 'max:2048'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ]);

        $birthdate = now()->subYears($validated['age'])->startOfDay();

        $profilePhotoPath = $request->file('profile_photo')->store('profile-photos', 'public');

        $user = User::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'name' => trim($validated['first_name'].' '.($validated['middle_name'] ?? '').' '.$validated['last_name']),
            'country' => $validated['country'],
            'location' => $validated['location'],
            'birthdate' => $birthdate,
            'gender' => $validated['gender'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'profile_photo' => $profilePhotoPath,
            'role' => 'user',
            'banned' => false,
        ]);

        if (! empty($validated['tags'])) {
            $user->tags()->sync($validated['tags']);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('user.dashboard');
    }

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        /** @var User|null $user */
        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            Log::warning('Login mislukte (ongeldige credentials)', ['email' => $validated['email']]);

            return back()
                ->withErrors(['email' => 'E-mailadres of wachtwoord onjuist.'])
                ->onlyInput('email');
        }

        if ($user->banned) {
            Log::warning('Geblokkeerde gebruiker probeerde in te loggen', ['user_id' => $user->id]);

            return back()
                ->withErrors(['email' => 'Uw account is geblokkeerd. Neem contact op met de administrator.'])
                ->onlyInput('email');
        }

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('user.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    public function handleForgotPassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        /** @var User|null $user */
        $user = User::where('email', $validated['email'])->first();

        // Altijd zelfde melding om niet te lekken of het e-mailadres bestaat
        $genericMessage = 'Als dit e-mailadres bij ons bekend is, ontvangt u een e-mail met een resetlink.';

        if (! $user) {
            return back()->with('status', $genericMessage);
        }

        if ($user->banned) {
            return back()->with('status', $genericMessage);
        }

        $token = Str::random(64);
        $expiresAt = now()->addHour();

        DB::table('password_resets')->where('user_id', $user->id)->delete();

        DB::table('password_resets')->insert([
            'user_id' => $user->id,
            'token' => hash('sha256', $token),
            'expires_at' => $expiresAt,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $resetUrl = route('password.reset.form', ['token' => $token]);

        Mail::to($user->email)->send(new PasswordResetMail($resetUrl));

        Log::info('Password reset mail verstuurd', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        return back()->with('status', $genericMessage);
    }

    public function showResetPassword(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
        ]);
    }

    public function handleResetPassword(Request $request, string $token): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $hashedToken = hash('sha256', $token);

        $record = DB::table('password_resets')
            ->where('token', $hashedToken)
            ->first();

        if (! $record) {
            return back()
                ->withErrors(['token' => 'Deze resetlink is ongeldig of verlopen.'])
                ->withInput();
        }

        if (now()->greaterThan($record->expires_at)) {
            return back()
                ->withErrors(['token' => 'Deze resetlink is verlopen. Vraag een nieuwe resetlink aan.'])
                ->withInput();
        }

        /** @var User|null $user */
        $user = User::find($record->user_id);

        if (! $user) {
            return back()
                ->withErrors(['token' => 'Deze resetlink is ongeldig.'])
                ->withInput();
        }

        if ($user->banned) {
            return back()
                ->withErrors(['token' => 'Dit account is geblokkeerd. Neem contact op met de administrator.'])
                ->withInput();
        }

        $user->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        DB::table('password_resets')->where('user_id', $user->id)->delete();

        Log::info('Password reset succesvol', ['user_id' => $user->id]);

        return redirect()
            ->route('login')
            ->with('status', 'Je wachtwoord is succesvol gewijzigd. Je kunt nu inloggen.');
    }
}

