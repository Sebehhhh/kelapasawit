<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua user kecuali user yg sedang login (opsional)
        $users = User::where('id', '!=', auth()->id())->get();

        return view('admin.users.index', compact('users'));
    }

    // Modal style (tidak perlu create & edit function)
    // public function create() { ... }
    // public function edit($id) { ... }

    /**
     * Store a newly created resource in storage.
     */
    public function store(\Illuminate\Http\Request $request)
    {
        // Isi nanti (buat user baru via modal)
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Opsional: tampilkan detail user jika mau
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(\Illuminate\Http\Request $request, string $id)
    {
        // Isi nanti (update user via modal)
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Isi nanti (hapus user via modal)
    }
}