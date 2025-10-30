<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;


use Gate;
use DB;

class UserController extends Controller
{


    public function __contruct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // denies the gate if 
        if(Gate::denies('admin-access')){
            return redirect('errors.403');
        }
        
        // Get search query from request (if any)
        $search = $request->input('search');
        $roleFilter = $request->input('role_filter');
        
        // Start building the query
        $query = User::with('roles');
        
        // Apply search filter if provided
        if($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('student_id', 'LIKE', "%{$search}%")
                  ->orWhere('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%");
            });
        }
        
        // Apply role filter if provided
        if($roleFilter) {
            $query->whereHas('roles', function($q) use ($roleFilter) {
                $q->where('name', $roleFilter);
            });
        }
        
        // Get all users with pagination
        $allusers = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get all roles for the filter dropdown
        $roles = \App\Models\Role::all();

        return view('admin.users.index', compact('allusers', 'search', 'roles', 'roleFilter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // denies the gate if 
        if(Gate::denies('admin-access')){
            return redirect('errors.403');
        }

        // Load user with relationships
        $user->load('roles');

        return view('admin.users.show')
            ->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // denies the gate if 
        if(Gate::denies('admin-access')){
            return redirect('errors.403');
        }

        // Load user with relationships
        $user->load('roles');
        
        // Get all available roles
        $roles = \App\Models\Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // denies the gate if 
        if(Gate::denies('admin-access')){
            return redirect('errors.403');
        }

        // Validate the input
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ];

        // Add password validation if a new password is provided
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        // Update user
        $user->name = $request->name;
        $user->email = $request->email;
        
        // Update additional fields if they exist
        if ($request->filled('student_id')) {
            $user->student_id = $request->student_id;
        }
        
        if ($request->filled('first_name')) {
            $user->first_name = $request->first_name;
        }
        
        if ($request->filled('last_name')) {
            $user->last_name = $request->last_name;
        }
        
        if ($request->filled('middle_initial')) {
            $user->middle_initial = $request->middle_initial;
        }
        
        // Update password if provided
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        // Update roles if provided
        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }


    // new function added for viewing all user feedbacks 
    public function userfeedback()
    {
        $allfeedbacks = DB::table('feedbacks')
        ->select('*')
        ->paginate(10);

        return view('admin.users.feedbacks.show')
        ->with('allfeedbacks',$allfeedbacks);
    }
}
