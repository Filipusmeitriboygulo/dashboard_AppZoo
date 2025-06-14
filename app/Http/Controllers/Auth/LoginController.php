<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/admin/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function redirectTo()
    {
        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            return redirect('/login')->withErrors(['email' => 'Your account is inactive.']);
        }

        // Redirect based on role
        switch ($user->role) {
            case 'admin':
                return '/admin/home';
            case 'kepala_upa':
                return '/kepala-upa/dashboard';
            case 'ketua_jurusan':
                return '/ketua-jurusan/dashboard';
            case 'ketua_prodi':
                return '/ketua-prodi/dashboard';
            case 'wakil_direktur':
                return '/wakil-direktur/dashboard';
            default:
                return '/';
        }
    }

    protected function credentials(Request $request)
    {
        return array_merge(
            $request->only($this->username(), 'password'),
            ['is_active' => true]
        );
    }
}
