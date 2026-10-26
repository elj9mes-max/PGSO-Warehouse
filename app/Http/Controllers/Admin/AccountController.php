<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AccountLevel;
use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    /**
     * Display accounts and staff listing.
     */
    public function index(): View
    {
        $users = User::with('office')->orderBy('id', 'desc')->get();
        $offices = Office::orderBy('office_name')->get();

        $totalAccounts = $users->count();
        $staffCount = $users->where('account_level', AccountLevel::Staff)->count();
        $adminCount = $users->where('account_level', AccountLevel::Admin)->count();
        $assignedCount = $users->whereNotNull('office_id')->count();
        $unassignedCount = $users->whereNull('office_id')->count();

        return view('admin.accounts', compact(
            'users',
            'offices',
            'totalAccounts',
            'staffCount',
            'adminCount',
            'assignedCount',
            'unassignedCount'
        ));
    }

    /**
     * Redirect /users to accounts index.
     */
    public function usersRedirect(): RedirectResponse
    {
        return redirect()->route('admin.accounts.index');
    }

    /**
     * Store a newly created account.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_initial' => ['nullable', 'string', 'max:10'],
            'last_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'office_id' => ['nullable', 'exists:offices,id'],
            'account_level' => ['required', Rule::enum(AccountLevel::class)],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $fullName = trim("{$validated['first_name']} {$validated['last_name']}");

        User::create([
            'first_name' => $validated['first_name'],
            'middle_initial' => ! empty($validated['middle_initial']) ? rtrim($validated['middle_initial'], '.').'.' : null,
            'last_name' => $validated['last_name'],
            'name' => $fullName,
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'office_id' => ! empty($validated['office_id']) ? $validated['office_id'] : null,
            'account_level' => $validated['account_level'],
            'password' => $validated['password'],
        ]);

        return redirect()->route('admin.accounts.index')->with('status', "Account for '{$fullName}' (@{$validated['username']}) created successfully!");
    }

    /**
     * Update account profile details.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_initial' => ['nullable', 'string', 'max:10'],
            'last_name' => ['required', 'string', 'max:255'],
            'account_level' => ['required', Rule::enum(AccountLevel::class)],
        ]);

        $targetLevel = $validated['account_level'] instanceof AccountLevel ? $validated['account_level'] : AccountLevel::tryFrom($validated['account_level']);
        if ($user->id === auth()->id() && $targetLevel !== AccountLevel::Admin) {
            return redirect()->route('admin.accounts.index')->with('error', 'You cannot change your own administrative account level.');
        }

        $fullName = trim("{$validated['first_name']} {$validated['last_name']}");

        $user->update([
            'first_name' => $validated['first_name'],
            'middle_initial' => ! empty($validated['middle_initial']) ? rtrim($validated['middle_initial'], '.').'.' : null,
            'last_name' => $validated['last_name'],
            'name' => $fullName,
            'account_level' => $validated['account_level'],
        ]);

        return redirect()->route('admin.accounts.index')->with('status', "Profile details for '{$user->full_name}' updated successfully!");
    }

    /**
     * Update account credentials (username/password).
     */
    public function updateCredentials(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $data = ['username' => $validated['username']];
        if (! empty($validated['password'])) {
            $data['password'] = $validated['password'];
        }

        $user->update($data);

        $message = ! empty($validated['password'])
            ? "Credentials for '{$user->full_name}' updated (new password set, username: @{$user->username})!"
            : "Username for '{$user->full_name}' updated to '@{$user->username}'!";

        return redirect()->route('admin.accounts.index')->with('status', $message);
    }

    /**
     * Update account office assignment.
     */
    public function updateOffice(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'office_id' => ['nullable', 'exists:offices,id'],
        ]);

        $newOfficeId = ! empty($validated['office_id']) ? (int) $validated['office_id'] : null;
        $user->update(['office_id' => $newOfficeId]);

        $user->refresh();
        $officeName = $user->office ? $user->office->office_name : 'No Office (Floating Staff)';

        return redirect()->route('admin.accounts.index')->with('status', "Office assignment for '{$user->full_name}' updated to '{$officeName}'!");
    }

    /**
     * Delete user account.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.accounts.index')->with('error', 'You cannot delete your own logged-in account.');
        }

        $userName = $user->full_name ?: $user->username;
        $user->delete();

        return redirect()->route('admin.accounts.index')->with('status', "Account '{$userName}' deleted successfully.");
    }
}
