<?php

namespace App\Http\Controllers;

use App\Models\UserInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\UserInvitation as UserInvitationMail;

class UserInvitationController extends Controller
{
    public function index()
    {
        $invitations = UserInvitation::with('inviter')->latest()->paginate(10);
        return view('admin.invitations.index', compact('invitations'));
    }

    public function create()
    {
        return view('admin.invitations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:user_invitations,email|unique:users,email',
        ]);

        $invitation = UserInvitation::create([
            'email' => $request->email,
            'token' => Str::random(64),
            'invited_by' => Auth::id(),
            'expires_at' => now()->addDays(7),
        ]);

        Mail::to($request->email)->send(new UserInvitationMail($invitation));

        return redirect()->route('invitations.index')
            ->with('success', 'Invitación enviada correctamente.');
    }

    public function destroy(UserInvitation $invitation)
    {
        $invitation->delete();
        return redirect()->route('invitations.index')
            ->with('success', 'Invitación eliminada correctamente.');
    }
}
