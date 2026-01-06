<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordChangeRequest;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show change password form
     */
    public function showChangePasswordForm()
    {
        return view('owner.profile.change-password');
    }

    /**
     * Update password
     */
    public function updatePassword(PasswordChangeRequest $request)
    {
        $user = auth()->user();

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password berhasil diubah.');
    }
}
