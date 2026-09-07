<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('Super Admin')) {
            return redirect('/admin');
        }

        /*
        |--------------------------------------------------------------------------
        | SUPERVISOR
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('Supervisor')) {
            return redirect()->route('supervisor.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | DIRECTOR
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('Director')) {
            return redirect()->route('director.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | HRD
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('HRD')) {
            return redirect()->route('hrd.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | MANAGER
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('Manager')) {
            return redirect()->route('manager.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | EMPLOYEE
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('Employee')) {
            return redirect()->route('employee.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | ROLE TIDAK DIKENALI
        |--------------------------------------------------------------------------
        */

        Auth::logout();

        return redirect('/login')->withErrors([
            'login' => 'Role pengguna tidak dikenali.',
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}