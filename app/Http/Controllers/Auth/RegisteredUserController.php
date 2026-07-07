<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
    'name' => [
    'required',
    'string',
    'min:5',
    'max:100',
    'regex:/^[A-Za-z]+(?:\s[A-Za-z]+)*$/',
    'not_regex:/^\s*$/',
],

    'email' => [
    'required',
    'string',
    'lowercase',
    'email:rfc',
    'max:255',
    'unique:users,email'
],

    'phone' => [
    'required',
    'regex:/^8[0-9]{8,12}$/',
    'unique:users,phone',
],

    'password' => [
        'required',
        'confirmed',
        'max:50',
        Rules\Password::min(8)
            ->letters()
            ->mixedCase()
            ->numbers(),
    ],

    'captcha' => [
        'accepted'
    ],
], [

    // Nama
    'name.required' => 'Nama lengkap wajib diisi.',
    'name.min' => 'Nama minimal 5 karakter.',
    'name.max' => 'Nama maksimal 100 karakter.',
    'name.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
    'name.not_regex' => 'Nama tidak boleh kosong.',

    // Email
    'email.required' => 'Email wajib diisi.',
    'email.email' => 'Format email tidak valid.',
    'email.unique' => 'Email sudah terdaftar.',
    'email.max' => 'Email maksimal 255 karakter.',

    // Telepon
    'phone.required' => 'Nomor telepon wajib diisi.',
    'phone.digits_between' => 'Nomor telepon harus 9-13 digit.',
    'phone.regex' => 'Nomor telepon harus diawali angka 8 dan terdiri dari 9-13 digit.',
    'phone.unique' => 'Nomor telepon sudah digunakan.',
    'phone.not_regex' => 'Nomor telepon tidak valid.',

    // Password
    'password.required' => 'Password wajib diisi.',
    'password.confirmed' => 'Konfirmasi password tidak sama.',
    'password.max' => 'Password maksimal 50 karakter.',

    // Captcha
    'captcha.accepted' => 'Silakan centang "Saya bukan robot".',
]);

        $user = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'phone' => $request->phone,
    'password' => Hash::make($request->password),
]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
