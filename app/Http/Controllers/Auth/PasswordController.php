<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
{
    $request->validate([
        'current_password' => ['required'],
        'password' => ['required','min:8','confirmed'],
    ]);

    if (!Hash::check($request->current_password, $request->user()->password)) {
        return back()->withErrors([
            'current_password' => 'Password lama yang Anda masukkan salah.'
        ]);
    }

    if ($request->current_password == $request->password) {
        return back()->withErrors([
            'password' => 'Password baru tidak boleh sama dengan password lama.'
        ]);
    }

    $request->user()->update([
        'password' => Hash::make($request->password),
    ]);

    return back()->with('success', 'Password berhasil diperbarui.');
}
}
