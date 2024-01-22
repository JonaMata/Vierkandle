<?php

namespace App\Http\Controllers;

use App\Models\Vierkandle;
use App\Models\VierkandleSolution;
use App\Models\VierkandleSolve;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VierkandleController extends Controller
{
    public function index()
    {
        return $this->show(Vierkandle::query()->where('date', Carbon::today())->first());
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
            'today' => Vierkandle::query()->where('date', Carbon::today())->first()->setAppends(['solutions', 'solution_count']),
            'vierkandles' => Vierkandle::query()->where('date', '!=', Carbon::today())->orderBy('date', 'desc')->get()->each->setAppends(['solutions', 'solution_count']),
        ]);
    }

    public function vierkandle(Vierkandle $vierkandle) {
        return response()->json($vierkandle->setAppends(['solutions']));
    }

    public function guess(Request $request)
    {
        $validated = $request->validate([
            'solutions' => 'required|array',
            'solutions.*' => 'required|array',
            'solutions.*.id' => 'required|integer|exists:vierkandle_solutions,id',
            'solutions.*.word' => 'required|string',
        ]);
        foreach ($validated['solutions'] as $word) {
            $vierkandleSolution = VierkandleSolution::query()->findOrFail($word['id']);
            if ($vierkandleSolution->word !== $word['word']) {
                return response()->json(['success' => false, 'message' => "Word doesn't match solution"]);
            }
            VierkandleSolve::ofUser(auth()->user(), $vierkandleSolution->vierkandle)->addWord($vierkandleSolution);
        }
        return response()->json(['success' => true, 'message' => 'Words added to solution']);
    }
}
