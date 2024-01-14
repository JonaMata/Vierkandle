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
            'vierkandle' => Vierkandle::query()->where('date', Carbon::today())->first()->setAppends(['solutions']),
        ]);
    }

    public function show(Vierkandle $vierkandle)
    {
        return Inertia::render('Vierkandle/Index', [
            'vierkandle' => $vierkandle->setAppends(['solutions']),
        ]);
    }

    public function list()
    {
        return Inertia::render('Vierkandle/List', [
            'today' => Vierkandle::query()->where('date', Carbon::today())->first()->setAppends(['solutions']),
            'vierkandles' => Vierkandle::query()->where('date', '!=', Carbon::today())->orderBy('date', 'desc')->get(),
        ]);
    }
}
