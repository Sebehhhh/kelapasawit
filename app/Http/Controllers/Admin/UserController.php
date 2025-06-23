<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Tidak tampilkan akun yang sedang login (opsional)
        $users = User::where('id', '!=', Auth::id())->orderByDesc('created_at')->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Store a newly created user (AJAX).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'role'     => ['required', Rule::in(['admin', 'owner', 'pelanggan'])],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $validated['password'] = Hash::make($request->password);

        $user = User::create($validated);

        return response()->json(['message' => 'Pengguna berhasil ditambah.', 'user' => $user]);
    }

    /**
     * Update an existing user (AJAX).
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'    => ['nullable', 'string', 'max:20'],
            'role'     => ['required', Rule::in(['admin', 'owner', 'pelanggan'])],
        ];

        // Password hanya jika diisi
        if ($request->filled('password')) {
            $rules['password'] = ['string', 'min:6'];
        }

        $validated = $request->validate($rules);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return response()->json(['message' => 'Pengguna berhasil diupdate.']);
    }

    /**
     * Remove the specified user from storage (AJAX).
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Tidak bisa hapus diri sendiri
        if ($user->id == Auth::id()) {
            return response()->json(['message' => 'Tidak bisa menghapus akun sendiri!'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'Pengguna berhasil dihapus.']);
    }
}
