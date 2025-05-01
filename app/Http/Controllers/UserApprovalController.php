<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserApprovalController extends Controller
{
    public function index()
    {
        $pendingUsers = User::where('is_approved', false)->latest()->paginate(10);
        return view('admin.users.pending', compact('pendingUsers'));
    }

    public function approve(User $user)
    {
        $user->update(['is_approved' => true]);

        // Opcional: Enviar notificación al usuario
        // $user->notify(new AccountApproved());

        return redirect()->route('users.pending')
            ->with('success', 'Usuario aprobado correctamente.');
    }

    public function reject(User $user)
    {
        // Opcional: Enviar notificación al usuario antes de eliminar
        // $user->notify(new AccountRejected());

        $user->delete();

        return redirect()->route('users.pending')
            ->with('success', 'Usuario rechazado y eliminado correctamente.');
    }
}
