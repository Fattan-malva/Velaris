<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;

class MicrosoftAuthController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('azure')->redirect();
    }

    public function handleProviderCallback(Request $request)
    {
        try {
            // Get the user information from Microsoft
            $user = Socialite::driver('azure')->stateless()->user();

            // Log user data to help with debugging
            \Log::info('Microsoft user data: ' . json_encode($user));

            // Extract and clean the email from userPrincipalName
            $microsoftEmail = explode('#', $user->getEmail())[2];

            // Check if the user already exists in the database
            $existingUser = Customer::where('username', $microsoftEmail)->first();

            if ($existingUser) {
                \Log::info('Existing user found');
                // Log in the existing user
                Auth::login($existingUser);
            } else {
                \Log::info('No existing user found, creating new user');
                // Create a new user if they don't exist
                $newUser = Customer::create([
                    'username' => $microsoftEmail,
                    'password' => Hash::make(uniqid()), // Use a simple bcrypt hash for initial password
                    'role' => 'user', // Default role for new users
                    'nrp' => $user->getId(),
                    'name' => $user->getName(),
                    'mapping' => null,
                    'login_method' => 'With Microsoft 365'
                ]);
                
                // Log in the newly created user immediately after creation
                Auth::login($newUser);
            }

            // Store user details in the session
            $request->session()->put('user_id', Auth::id());
            $request->session()->put('user_username', Auth::user()->username);
            $request->session()->put('user_role', Auth::user()->role);
            $request->session()->put('user_name', Auth::user()->name);
            $request->session()->put('user_nrp', Auth::user()->nrp);
            $request->session()->put('user_mapping', Auth::user()->mapping);
            $request->session()->put('login_method', Auth::user()->login_method);

            \Log::info('User logged in, redirecting to home');

            // Redirect to the home page for the logged-in user
            return redirect()->route('shared.homeUser')->with('success', 'Login successful using Microsoft 365.');
        } catch (\Exception $e) {
            // Log the exception message for debugging
            \Log::error('Microsoft login error: ' . $e->getMessage());

            // Redirect back to the login page with an error message
            return redirect('/login')->with('error', 'Login failed: ' . $e->getMessage());
        }
    }
}
