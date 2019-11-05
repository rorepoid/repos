<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Services\Marketservice;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Services\MarketAuthenticationService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = '/home';

    /**
     * The service to perform authentication actions
     * @var App\Services\MarketAuthenticationService
     */
    protected $marketAuthenticationService;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MarketAuthenticationService $marketAuthenticationService, Marketservice $marketservice)
    {
        $this->middleware('guest')->except('logout');
        $this->marketAuthenticationService = $marketAuthenticationService;
        parent::__construct($marketservice);
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $authorizationUrl = $this->marketAuthenticationService->resolveAuthorizationUrl();
        // dd($this->marketAuthenticationService);
        return view('auth.login')->with(['authorizationUrl' => $authorizationUrl]);
    }

    /**
     * Recibe el resultado de la autorización de la API
     * 
     * @return \Illuminate\Http\Response
     */
    public function authorization(Request $request)
    {
        if ($request->has('code')) {
            $tokenData = $this->marketAuthenticationService->getCodeToken($request->code);

            $userData = $this->marketService->getUserInformation();

            dd($userData);

            $user = $this->registerOrUpdateUser($userData, $tokenData);

            $this->loginUser($user);

            return redirect()->intended('home');
        }

        return redirect()->route('login')->withErrors(['Cancelaste el proceso de Autorización']);
    }

    /**
     * Crea o actualiza al usuario desde la API
     * 
     * @param stdClass $userData
     * @param stdClass $tokenData
     * @return App\User
     */
    public function registerOrUpdateUser($userData, $tokenData): User
    {
        return User::updateOrCreate(
            [
                'service_id' => $userData->identifier,
            ],
            [
                'grant_type' => $tokenData->grant_type,
                'access_token' => $tokenData->access_token,
                'refresh_token' => $tokenData->refresh_token,
                'token_expires_at' => $tokenData->token_expires_at,
            ]
        );
    }

    /**
     * Autentica al usuario en el Cliente
     * 
     * @param App\User $user
     * @param boolean $remember
     * @return void
     */
    public function loginUser(User $user, $remember = true)
    {
        Auth::login($user, $remember);

        session()->regenerate();
    }
}
