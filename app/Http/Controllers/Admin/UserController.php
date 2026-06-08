<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderByDesc('created_at')->get();

        return view('pages.admin-users', compact('users'));
    }

    public function create()
    {
        return view('pages.admin-user-form', ['user' => new User()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:user,admin'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        User::create($data);

        return redirect()->route('recorda.manageUsers')->with('success', 'Pengguna ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('pages.admin-user-form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:user,admin'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('recorda.manageUsers')->with('success', 'Pengguna diperbarui.');
    }

    public function toggleStatus(User $user)
    {
        $user->update([
            'status' => $user->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        return back()->with('success', 'Status pengguna diperbarui.');
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role' => ['required', 'in:user,admin']]);

        $user->update(['role' => $request->input('role')]);

        return back()->with('success', 'Role pengguna diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $user->delete();

        return back()->with('success', 'Pengguna dihapus.');
    }
}
