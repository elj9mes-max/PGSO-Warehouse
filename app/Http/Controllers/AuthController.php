<?php

namespace App\Http\Controllers;

use App\Enums\AccountLevel;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Display the login page.
     */
    public function showLogin(): View
    {
        return view('login');
    }

    /**
     * Handle an incoming authentication request using Eloquent.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $identifier = $request->validated('email');

        // Query user using Eloquent by username or email
        $user = User::query()
            ->where('username', $identifier)
            ->orWhere('email', $identifier)
            ->first();

        if (! $user || ! Hash::check($request->validated('password'), $user->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'The provided credentials do not match our records.',
                ]);
        }

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('staff.inventory'));
    }

    /**
     * Display the signup page.
     */
    public function showSignup(): View
    {
        return view('signup');
    }

    /**
     * Handle user registration and account creation using Eloquent.
     */
    public function signup(SignupRequest $request): RedirectResponse
    {
        $firstName = $request->validated('first_name');
        $middleInitial = $request->validated('middle_initial');
        $lastName = $request->validated('last_name');

        // Create new user using Eloquent
        $user = User::create([
            'first_name' => $firstName,
            'middle_initial' => $middleInitial,
            'last_name' => $lastName,
            'name' => trim("{$firstName} {$lastName}"),
            'username' => $request->validated('username'),
            'password' => $request->validated('password'),
            'account_level' => AccountLevel::Staff,
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('status', 'Account created successfully! Welcome to PGSO Warehouse Inventory.');
        }

        return redirect()->route('staff.inventory')->with('status', 'Account created successfully! Welcome to PGSO Warehouse Inventory.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
