<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // ✅ Step 4: Flexible validation for Students/Employees and Visitors
        $request->validate([
            'student_id' => [
                'nullable', // visitors can skip this
                'string',
                'max:255',
                Rule::unique('users', 'student_id'),
            ],
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_initial' => ['nullable', 'string', 'max:5'], 
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // ✅ Step 4: Determine user type automatically
        $userType = $request->filled('student_id') ? 'student' : 'visitor';

        // ✅ Step 4: Create user with properly formatted data
        $user = User::create([
            'student_id' => trim($request->student_id),
            'last_name' => trim($request->last_name),
            'first_name' => trim($request->first_name),
            'middle_initial' => trim($request->middle_initial),
            'email' => strtolower(trim($request->email)),
            'password' => Hash::make($request->password),
            'user_type' => $userType, // optional: add this column to your users table
        ]);

        event(new Registered($user));

        // ✅ Step 4: Assign role automatically
        $roleName = $userType === 'student' ? 'user' : 'visitor';
        $role = Role::select('id')->where('name', $roleName)->first();
        if ($role) {
            $user->roles()->attach($role);
        }

        // ✅ Step 4: Log in and redirect
        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
