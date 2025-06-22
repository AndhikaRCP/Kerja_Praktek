<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    public function index()
    {
        $users = User::get();
        return view('user.index', compact('users'));
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'username' => 'required|string|max:100|unique:users,username',
                'name' => 'required|string|max:100',
                'email' => 'required|email|max:100|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
                'role' => ['required', Rule::in(['superadmin', 'admin', 'sales'])],
            ],
            [
                'username.required' => 'Username wajib diisi.',
                'username.unique' => 'Username sudah digunakan.',
                'name.required' => 'Nama lengkap wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah digunakan.',
                'password.required' => 'Password wajib diisi.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'role.required' => 'Role harus dipilih.',
                'role.in' => 'Role tidak valid.',
            ]
        );

        User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => true,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:superadmin,admin,staff',
            'is_active' => ['required', Rule::in([0, 1])],
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $superadminCount = User::where('role', 'superadmin')
            ->where('is_active', true)
            ->where('id', '!=', $user->id)
            ->count();

        if ($user->role === 'superadmin' && $user->is_active) {
            $mengubahRole = $request->role !== 'superadmin';
            $mengubahStatus = $request->is_active == false;

            if ($superadminCount === 0 && ($mengubahRole || $mengubahStatus)) {
                return redirect()->back()->with('error', 'Tidak bisa mengubah role atau menonaktifkan Superadmin terakhir.');
            }
        }

        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->is_active = $request->is_active;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        return redirect()->route('user.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }
}
