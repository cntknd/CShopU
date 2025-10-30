<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Added for Auth::user() access

use App\Models\Users\Feedbacks;

use Gate;
use DB;

class CTRLFeedbacks extends Controller
{

    public function __construct()
    {
        // Fix typo: should be __construct
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        // denies the gate if 
        if(Gate::denies('user-access')){
            return redirect('errors.403');
        }
        
        return view('users.feedback.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation
        $request->validate([
            // Email is read-only in the view, but validating it doesn't hurt.
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'rate' => ['required', 'integer', 'in:1,3,5'], // Added validation for the 'rate' field
            'comm' => ['required', 'string', 'max:1000'], // Added validation for the 'comments' field
        ]);
        
        // --- START BAD WORD FILTERING ---

        // 2. Define Filipino bad words (use common variations and contractions)
        $badWords = [
            'putangina', 'tangina', 'tang ina', 'pota', 'gago', 'gaga',
            'bobo', 'boba', 'tanga', 'ulol', 'sira ulo', 'walang hiya', 
            'pakyū', 'pakyoo', 'pakyu', 'hayop', 'tarantado', 'punyeta', 
            'leche', 'buwisit', 'kupal', 'burat', 'tae', 'puki', 'titi',
            'inutil', 'hudas', 'yawa', 'piste', 'pisteng yawa'
        ];
        
        // 3. Get the comment text
        $originalComment = $request->input('comm');
        $cleanedComment = $originalComment;

        // 4. Loop through the list and replace any occurrences (case-insensitive)
        foreach ($badWords as $word) {
            // Use a pattern to replace the bad word globally (g) and case-insensitively (i)
            // The \b ensures we only match whole words (e.g., prevents filtering 'gago' inside 'pagoda')
            // but we won't use it here to ensure contractions like 'tangina' are caught.
            $cleanedComment = preg_replace(
                '/' . preg_quote($word, '/') . '/i', // The pattern
                str_repeat('*', strlen($word)),      // Replacement: *'s matching the word length
                $cleanedComment
            );
        }

        // --- END BAD WORD FILTERING ---

        // 5. Store the feedback using the CLEANED comment
        $feedback = Feedbacks::create([
            // Since the email is read-only and pre-filled, we can use the authenticated user's email
            // for security, if possible, but we'll stick to $request->email since the view uses it.
            'email' => $request->email,
            'rate' => $request->rate,
            'comments' => $cleanedComment // *** Use the cleaned comment here ***
        ]);

        return redirect()->back()->withInput()->with('status','Feedback Submitted Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}