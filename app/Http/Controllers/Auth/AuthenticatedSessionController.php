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
    /** Toon het inlogformulier. */
    public function create(): View
    {
        return view('auth.login');
    }

    /** Log de gebruiker in en vernieuw de sessie voor extra veiligheid. */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if ($request->user()->is_personeel) {
            return redirect()->route('personeel.index');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /** Log uit en maak de huidige sessie ongeldig. */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
