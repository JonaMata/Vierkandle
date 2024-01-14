<?php

namespace App\Http\Controllers;

use App\Models\Vierkandle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VierkandleController extends Controller
{
    public function index()
    {
        return Inertia::render('Vierkandle/Index', [
            'vierkandle' => Vierkandle::query()->where('date', Carbon::today())->first(),
        ]);
    }
}
