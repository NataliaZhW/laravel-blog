<?php

namespace App\Service;

use App\Jobs\PasswordResetMailSendJob;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserService
{
    public function login(Request $request): RedirectResponse
    {
        $loginType = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $request->merge([$loginType => $request->input('login')]);

        $credentials = $request->validate([
            'username' => 'required_without:email|string|exists:users,username',
            'email' => 'required_without:username|email|exists:users,email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('posts.index', ['lang' => app()->getLocale()]);
        }

        return back()->withErrors([
            'login' => 'Неверные учетные данные.',
        ])->onlyInput('login');
    }


    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $user = User::query()
            ->where('username', $request->input('username'))
            ->first();

        if ($user) {
            $maskedEmail = Str::mask($user->email, '*', 3, -3);

            dispatch(new PasswordResetMailSendJob($user));
            return back()->with(['success' => 'Письмо с сбросом пароля отправлена на почту ' . $maskedEmail . '.']);
        }

        return back()->withErrors(['errors' => 'Данного пользователя не существует!']);
    }

    public function userPasswordReset(Request $request, User $user, string $hash): View
    {
        if (!$request->hasValidSignature()) {
            abort(400, 'Invalid or expired link');
        }

        if ($hash !== sha1($user->email)) {
            abort(400, 'Invalid signature');
        }

        return view('auth.passwords.user-password-reset', ['user' => $user]);
    }

    public function changePassword(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return redirect()->route('login');
    }
}
