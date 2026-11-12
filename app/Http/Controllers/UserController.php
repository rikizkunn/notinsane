<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::orderBy('nama')->paginate(15);

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        return view('users.create', ['roles' => Role::cases()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', Password::min(6)],
            'role' => ['required', Rule::enum(Role::class)],
        ]);

        User::create($data);

        return redirect()->route('users.index')->with('status', 'Pengguna baru ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('users.edit', ['user' => $user, 'roles' => Role::cases()]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'password' => ['nullable', Password::min(6)],
            'role' => ['required', Rule::enum(Role::class)],
        ]);

        // Keep the old password when the field is left blank
        if (blank($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('status', 'Data pengguna diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('status', 'Nggak bisa hapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('status', 'Pengguna dihapus.');
    }
}
