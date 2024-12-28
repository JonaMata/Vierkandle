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

    public function list($type = 'dagelijks')
    {
        $base_query = Vierkandle::query()->with('solutions')->where('date', '<=', Carbon::today());
        $vierkandles = $base_query;
        switch ($type) {
            case 'dagelijks':
                $vierkandles = $vierkandles->where('is_daily', true)->where('is_express', false);
                break;
            case 'perongeluk':
                $vierkandles = $vierkandles->where('is_daily', true)->where('is_express', true);
                break;
            default:
                if(ctype_digit($type)) {
                    $vierkandles = $vierkandles->where('is_daily', false)->where('is_express', false);
                    $vierkandles = $vierkandles->whereRAW('SQRT(LENGTH(letters)) = ?', [intval($type)]);
                    if ($vierkandles->count() > 0) {
                        break;
                    }
                }
                abort(404);
        }
        $vierkandles = $vierkandles->orderBy('date', 'desc')->paginate(50);
        $solves = null;
        if (auth()->check()) {
            $solves = VierkandleSolve::query()->where('user_id', auth()->user()->id)->whereIn('vierkandle_id', $vierkandles->pluck('id'))->get()->groupBy('vierkandle_id');
        }
        $vierkandles->getCollection()->each->setAppends(['size', 'solution_count']);

        return Inertia::render('Vierkandle/List', [
            'types' => [['title' => 'Dagelijkse', 'key' => 'dagelijks'], ['title' => 'Per Ongeluk', 'key' => 'perongeluk'], ...Vierkandle::where('is_daily', false)->where('is_express', false)->where('date', '<=', Carbon::today())->selectRaw('SQRT(LENGTH(letters)) AS type')->groupBy('type')->pluck('type')->map(function ($type) {return ['title' => $type.'x'.$type, 'key' => $type];})],
            'type' => $type,
            'vierkandles' => $vierkandles,
            'solves' => $solves,
        ]);

        $daily = Vierkandle::query()->where('is_daily', true)->where('is_express', false)->where('date', '<=', Carbon::today())->orderBy('date', 'desc')->paginate(5, ['*'], 'daily');
        $daily->getCollection()->each->setAppends(['size', 'solution_count', 'solutions']);
        $daily_collection = $daily->getCollection()->keyBy('id');
        $daily_solutions = VierkandleSolution::whereIn('vierkandle_id', $daily->pluck('id'))->get();
        foreach ($daily_solutions->groupBy('vierkandle_id') as $vierkandle_solution) {
            $daily_collection->get($vierkandle_solution->first()->vierkandle_id)->solutions = $vierkandle_solution;
        }

        $express = Vierkandle::query()->where('is_daily', true)->where('is_express', true)->where('date', '<=', Carbon::today())->orderBy('date', 'desc')->paginate(5, ['*'], 'express');
        $express->getCollection()->each->setAppends(['size', 'solution_count', 'solutions']);
        $express_collection = $express->getCollection()->keyBy('id');
        $express_solutions = VierkandleSolution::whereIn('vierkandle_id', $express->pluck('id'))->get();
        foreach ($express_solutions->groupBy('vierkandle_id') as $vierkandle_solution) {
            $express_collection->get($vierkandle_solution->first()->vierkandle_id)->solutions = $vierkandle_solution;
        }

        return Inertia::render('Vierkandle/List', [
            'daily' => $daily,//->merge($daily_collection),
            'express' => $express,
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
            'mistakes' => 'required|integer',
        ]);
        $mistakesSet = false;
        foreach ($validated['solutions'] as $word) {
            $vierkandleSolution = VierkandleSolution::query()->findOrFail($word['id']);
            if (!$mistakesSet) {
                VierkandleSolve::ofUser(auth()->user(), $vierkandleSolution->vierkandle)->update(['mistakes' => $validated['mistakes']]);
                $mistakesSet = true;
            }
            if ($vierkandleSolution->word !== $word['word']) {
                return response()->json(['success' => false, 'message' => "Word doesn't match solution"]);
            }
            VierkandleSolve::ofUser(auth()->user(), $vierkandleSolution->vierkandle)->addWord($vierkandleSolution);
        }
        return response()->json(['success' => true, 'message' => 'Words added to solution']);
    }
}
