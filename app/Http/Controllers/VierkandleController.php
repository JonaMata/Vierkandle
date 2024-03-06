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
        return $this->show(Vierkandle::query()->where('is_daily', true)->where('is_express', false)->where('date', '<=', Carbon::today())->orderBy('date', 'DESC')->first());
    }

    public function express()
    {
        return $this->show(Vierkandle::query()->where('is_daily', true)->where('is_express', true)->where('date', '<=', Carbon::today())->orderBy('date', 'DESC')->first());
    }

    public function show(Vierkandle $vierkandle)
    {
        return Inertia::render('Vierkandle/Index', [
            'vierkandle' => $vierkandle->setAppends([...$vierkandle->getAppends(), 'solutions']),
        ]);
    }

    public function list()
    {
        return Inertia::render('Vierkandle/List', [
            'daily' => Vierkandle::query()->where('is_daily', true)->where('is_express', false)->where('date', '<=', Carbon::today())->orderBy('date', 'desc')->get()->each->setAppends(['size', 'solutions', 'solution_count']),
            'express' => Vierkandle::query()->where('is_daily', true)->where('is_express', true)->where('date', '<=', Carbon::today())->orderBy('date', 'desc')->get()->each->setAppends(['size', 'solutions', 'solution_count']),
            'vierkandles' => Vierkandle::query()->where('is_daily', false)->orderBy('date', 'desc')->get()->each->setAppends(['size', 'solutions', 'solution_count'])->groupBy('size'),
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
