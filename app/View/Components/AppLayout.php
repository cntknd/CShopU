<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

use Auth;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        
        // Check the role of the user and return the appropriate layout
        $user = Auth::user();
        $isAdmin = $user && $user->roles->isNotEmpty() && $user->roles->first()->name === 'admin';

        if ($isAdmin) {
            return view('layouts.Admin.app');
        } else {
            return view('layouts.Users.app');
        }
    }
}
