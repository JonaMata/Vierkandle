<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class MigrateController extends Controller
{
    public function migrate(Request $request) {
        $validated = $request->validate([
            'migrations' => 'required',
        ]);
        return Inertia::render('Migrate/Migrate', [
            'migrations' => $validated['migrations'],
        ]);
    }

    public function child() {
        return Inertia::render('Migrate/Child');
    }
}
