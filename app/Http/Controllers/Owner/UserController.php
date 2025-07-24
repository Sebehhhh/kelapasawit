<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('id', '!=', Auth::id());

        // Filter nama
        $searchName = $request->name;
        if ($searchName) {
            $query->where('name', 'like', '%' . $searchName . '%');
        }

        // Filter email
        $searchEmail = $request->email;
        if ($searchEmail) {
            $query->where('email', 'like', '%' . $searchEmail . '%');
        }

        // Filter role
        $selectedRole = $request->role;
        if ($selectedRole) {
            $query->where('role', $selectedRole);
        }

        $users = $query->orderByDesc('created_at')->paginate(10)->appends($request->all());
        return view('owner.users.index', compact('users', 'searchName', 'searchEmail', 'selectedRole'));
    }

    public function printReport(Request $request)
    {
        $users = User::orderByDesc('created_at')->get();
        $pdf = Pdf::loadView('owner.users.report-pdf', compact('users'));
        return $pdf->download('laporan-pengguna-' . date('Y-m-d') . '.pdf');
    }
}