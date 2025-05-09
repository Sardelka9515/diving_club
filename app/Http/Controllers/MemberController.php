<?php

// app/Http/Controllers/MemberController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function activities()
    {
        $registrations = auth()->user()->registrations()->with('activity')->latest()->paginate(10);
        return view('member.activities', compact('registrations'));
    }
}