<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:auto,light,dark',
        ]);
        $user = auth()->user();
        $user->theme = $request->theme;
        $user->save();
        return redirect()->back();
    }
}
