<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function show(){
        $role = auth()->user()->role;
        
        if($role === 'attendee')
            return view('dashboard.attendee');

        if($role === 'organiser')
            return view('dashboard.organiser');
        abort(403); // fallback if role is not allowed
    }
}
