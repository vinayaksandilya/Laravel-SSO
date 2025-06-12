<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Middleware\SSOAuth;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// SSO callback route
Route::get('/sso/callback', function (Request $request) {
    $code = $request->get('code');
    $state = $request->get('state');
    
    // Verify state to prevent CSRF attacks
    if ($state !== Session::get('sso_state')) {
        return redirect('/')->with('error', 'Invalid state parameter');
    }
    
    Session::forget('sso_state');
    
    if (!$code) {
        return redirect('/')->with('error', 'Authorization code not received');
    }
    
    // Exchange code for token
    try {
        $response = Http::asForm()->post(config('services.sso.base_url') . '/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => config('services.sso.client_id'),
            'client_secret' => config('services.sso.client_secret'),
            'redirect_uri' => config('services.sso.redirect_uri'),
            'code' => $code,
        ]);
        
        if ($response->successful()) {
            $tokenData = $response->json();
            $accessToken = $tokenData['access_token'];
            
            // Get user information from SSO server
            $userResponse = Http::withToken($accessToken)
                ->get(config('services.sso.base_url') . '/api/user');
            
            if ($userResponse->successful()) {
                $userData = $userResponse->json()['user'];
                
                // Store token in session
                Session::put('sso_token', $accessToken);
                
                // Find or create user locally
                $user = User::firstOrCreate(
                    ['email' => $userData['email']],
                    [
                        'name' => $userData['name'],
                        'email' => $userData['email'],
                        'password' => bcrypt(\Str::random(32)),
                    ]
                );
                
                // Log the user in
                Auth::login($user);
                
                return redirect('/dashboard')->with('success', 'Successfully logged in via SSO');
            }
        }
        
        return redirect('/')->with('error', 'Failed to authenticate with SSO server');
        
    } catch (\Exception $e) {
        \Log::error('SSO callback error: ' . $e->getMessage());
        return redirect('/')->with('error', 'Authentication failed');
    }
});

// SSO logout route
Route::post('/sso/logout', function (Request $request) {
    $token = Session::get('sso_token');
    
    if ($token) {
        // Logout from SSO server
        try {
            Http::withToken($token)
                ->post(config('services.sso.base_url') . '/api/logout');
        } catch (\Exception $e) {
            \Log::error('SSO logout error: ' . $e->getMessage());
        }
        
        Session::forget('sso_token');
    }
    
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    return redirect('/');
})->name('sso.logout');

// Protected routes (require SSO authentication)
Route::middleware([SSOAuth::class])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
    
    Route::get('/profile', function () {
        return view('profile');
    });
});

// Login route (redirects to SSO)
Route::get('/login', function () {
    return redirect('/dashboard'); // This will trigger SSO middleware
})->name('login');
