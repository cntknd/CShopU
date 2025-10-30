<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class UserFeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        // Allow any authenticated user who is not an admin to create feedback
        if (auth()->user()->hasRole('admin')) {
            abort(403, 'Administrators cannot submit feedback.');
        }
        return view('users.feedback.create');
    }

    public function store(Request $request)
    {
        try {
            // Allow any authenticated user who is not an admin to store feedback
            if (auth()->user()->hasRole('admin')) {
                abort(403, 'Administrators cannot submit feedback.');
            }

            // Validate the request
            $request->validate([
                'message' => 'required|string|min:10',
                'rating' => 'required|integer|in:1,3,5',
            ]);

            // Create the feedback
            $feedback = new Feedback();
            $feedback->user_id = auth()->id();
            $feedback->message = $request->message;
            $feedback->rating = $request->rating;
            $feedback->save();

            // Log success
            \Log::info('Feedback submitted successfully by user: ' . auth()->id());

            return redirect()
                ->route('dashboard')
                ->with('status', 'Thank you! Your feedback has been submitted successfully.');
        } catch (\Exception $e) {
            \Log::error('Feedback submission error: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'There was a problem submitting your feedback. Please try again.']);
        }
    }
}
