<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        try {
            // [VULNERABILITY: SQL Injection & Plain text passwords]
            $sql = "SELECT id FROM users WHERE email = '" . $email . "' AND password = '" . $password . "'";
            $matchedUsers = DB::select($sql);


            // Solution use parameterized query
            // $sql_parameterized = "SELECT id FROM users WHERE email = ? AND password = ?";
            // $matchedUsers = DB::select($sql_parameterized, [$email, $password]);

            if (count($matchedUsers) > 0) {
                Auth::loginUsingId($matchedUsers[0]->id);
                return redirect()->route('products.index');
            }

            return back()->withErrors(['message' => 'Invalid credentials']);
        } catch (\Exception $e) {
            return back()->withErrors(['message' => 'Database error: ' . $e->getMessage()]);
        }
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:4',
        ]);

        // [VULNERABILITY: Plain text passwords stored]
        // Explicitly saving without hashing
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->role = 'customer';
        $user->save();

        Auth::login($user);

        return redirect()->route('products.index');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('products.index');
    }
}
