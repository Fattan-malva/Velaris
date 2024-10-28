<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use League\Flysystem\UrlGeneration\PublicUrlGenerator;

class UserAuthController extends Controller
{
    public function showLoginForm(): Response
    {
        return response(view('auth.login'));

    }
    public function login(Request $request): RedirectResponse
    {
        $username = $request->input('username');
        $password = $request->input('password');

        try {

            // Find user by username (case-sensitive)
            $customer = Customer::where('username', $username)->first();

            // Check if user exists and password matches
            if (!$customer || !Hash::check($password, $customer->password)) {
                throw new \Exception('Username and Password do not match.');
            }

            // Store user ID, role, and name in the session
            $request->session()->put('user_id', $customer->id);
            $request->session()->put('user_username', $customer->username);
            $request->session()->put('user_role', $customer->role);
            $request->session()->put('user_name', $customer->name);
            $request->session()->put('user_nrp', $customer->nrp);
            $request->session()->put('user_mapping', $customer->mapping);

            // Redirect based on user role
            if ($customer->role === 'admin') {
                return redirect()->route('dashboard')->with('success', 'Success login.');
            } elseif ($customer->role === 'sales') {
                return redirect()->route('shared.homeSales')->with('success', 'Success login as Sales.');
            } else {
                return redirect()->route('shared.homeUser')->with('success', 'Success login.');
            }
        } catch (\Exception $e) {
            // Redirect back to login with error message
            return redirect()->back()->withInput()->with('error', 'Login failed: ' . $e->getMessage());
        }
    }





    /**
     * Show the registration form.
     */
    public function register(): Response
    {
        return response(view('auth.register'));
    }

    /**
     * Handle user registration.
     */
    public function storeregister(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:customer',
            'password' => 'required|string|max:50',
            'role' => 'required|string|max:50',
            'nrp' => 'required|string|max:50|unique:customer',
            'name' => 'required|string|max:50|regex:/^[\p{L}\s]+$/u',
            'mapping' => 'nullable|string|max:50',
            'login_method' => 'nullable|string|max:50',
        ]);

        $customer = Customer::create([
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')), // Hashing the password
            'role' => $request->input('role'),
            'nrp' => $request->input('nrp'),
            'name' => $request->input('name'),
            'mapping' => $request->input('mapping'),
            'login_method' => 'Register User'
        ]);
        event(new \App\Events\CustomerDataChanged($customer, 'created'));
        return redirect()->route('login')->with('success', 'Register successfully. Please log in using your email and password');
    }



    /**
     * Handle user logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('user_id');
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }

}
