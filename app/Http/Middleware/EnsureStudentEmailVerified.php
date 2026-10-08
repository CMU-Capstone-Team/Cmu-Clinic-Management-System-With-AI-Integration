<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class EnsureStudentEmailVerified {
    public function handle(Request $request, Closure $next) {
        $student = $request->user('student');
        if (!$student || !$student->email_verified_at || $student->status !== 'active') {
            Auth::guard('student')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('status', 'Please sign in to verify your email.');
        }
        return $next($request);
    }
}
