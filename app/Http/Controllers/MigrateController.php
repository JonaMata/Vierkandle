<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class MigrateController extends Controller
{
    public function migrate(Request $request) {
        return Inertia::render('Migrate/Migrate');
    }

    public function child() {
        return Inertia::render('Migrate/Child');
    }
}
