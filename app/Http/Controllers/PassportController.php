<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class PassportController extends Controller
{
    public function edit()
    {
        return Inertia::render('Passport/Dashboard', []);
    }
}
