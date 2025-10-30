<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function create()
    {
        return view('users.feedback.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_type' => 'required|in:citizen,business,government',
            'sex' => 'required|in:male,female',
            'age' => 'required|integer|min:1|max:150',
            'email' => 'nullable|email',
            'SQD1' => 'required|in:1,2,3,4,5,na',
            'SQD2' => 'required|in:1,2,3,4,5,na',
            'SQD3' => 'required|in:1,2,3,4,5,na',
            'SQD4' => 'required|in:1,2,3,4,5,na',
            'SQD5' => 'required|in:1,2,3,4,5,na',
            'SQD6' => 'required|in:1,2,3,4,5,na',
            'SQD7' => 'required|in:1,2,3,4,5,na',
            'SQD8' => 'required|in:1,2,3,4,5,na',
            'suggestions' => 'nullable|string|max:1000',
        ]);

        try {
            $feedback = new Feedback($validated);
            $feedback->user_id = Auth::id(); // If user is logged in
            $feedback->save();

            return redirect()->back()->with('status', 'Thank you for your feedback!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'There was a problem submitting your feedback. Please try again.']);
        }
    }
}