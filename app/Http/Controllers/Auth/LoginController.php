<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'password' => [
                'required',
                'string',
            ],
        ]);

        // ✅ TINANGGAL ANG '$credentials['status'] = 'active';' 
        // Dahil baka walang 'status' column sa 'users' table at mag-cause ng SQL error.
        
        if (! Auth::attempt(
            $credentials,
            $request->boolean('remember')
        )) {
            return back()
                ->withErrors([
                    'email' => 'Invalid credentials or account not found.',
                ])
                ->onlyInput('email');
        }

        // Optional: Kung gusto mong i-check ang status PAGKATAPOS ng login
        // if (Auth::user()->status !== 'active') {
        //     Auth::logout();
        //     return back()->withErrors(['email' => 'Account is inactive.']);
        // }

        $request->session()->regenerate();

        return redirect()->intended(
            route('dashboard', absolute: false)
        );
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}