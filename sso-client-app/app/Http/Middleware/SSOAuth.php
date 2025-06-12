<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SSOAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is already authenticated locally
        if (Auth::check()) {
            return $next($request);
        }

        // Check if there's an SSO token in the session
        $ssoToken = Session::get('sso_token');
        
        if ($ssoToken) {
            // Verify the token with SSO server
            $response = $this->verifyTokenWithSSOServer($ssoToken);
            
            if ($response && $response['success']) {
                // Token is valid, authenticate the user locally
                $this->authenticateUser($response['user']);
                return $next($request);
            } else {
                // Token is invalid, remove it from session
                Session::forget('sso_token');
            }
        }

        // Check if there's a token in the request (from SSO redirect)
        if ($request->has('sso_token')) {
            $token = $request->get('sso_token');
            $response = $this->verifyTokenWithSSOServer($token);
            
            if ($response && $response['success']) {
                // Store token in session and authenticate user
                Session::put('sso_token', $token);
                $this->authenticateUser($response['user']);
                return redirect($request->url());
            }
        }

        // Redirect to SSO login page
        return $this->redirectToSSOLogin($request);
    }

    /**
     * Verify token with SSO server
     */
    private function verifyTokenWithSSOServer($token)
    {
        try {
            $response = Http::post(config('services.sso.base_url') . '/api/verify-token', [
                'token' => $token
            ]);

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            \Log::error('SSO token verification failed: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Authenticate user locally
     */
    private function authenticateUser($userData)
    {
        // Find or create user locally
        $user = User::firstOrCreate(
            ['email' => $userData['email']],
            [
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => bcrypt(\Str::random(32)), // Random password since auth is handled by SSO
            ]
        );

        // Log the user in
        Auth::login($user);
    }

    /**
     * Redirect to SSO login page
     */
    private function redirectToSSOLogin(Request $request)
    {
        $clientId = config('services.sso.client_id');
        $redirectUri = urlencode(config('services.sso.redirect_uri'));
        $state = csrf_token();
        
        Session::put('sso_state', $state);

        $ssoLoginUrl = config('services.sso.base_url') . '/oauth/authorize?' . http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'read-user',
            'state' => $state,
        ]);

        return redirect($ssoLoginUrl);
    }
}
