<?php

namespace App\Console\Commands;

use App\Models\Vierkandle;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DailyPuzzles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-puzzles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle() : void
    {
        \Laravel\Prompts\info('Creating daily puzzles.');
        if(Vierkandle::where('is_daily', true)->where('is_express', false)->where('date', '=', now()->addDay()->format('Y-m-d'))->count() > 0) {
            \Laravel\Prompts\info('Vierkandle for tomorrow already exists. Skipping.');
        } else {
            \Laravel\Prompts\info('Creating vierkandle.');
            $vierkandle = new Vierkandle();
            $vierkandle->date = now()->addDay();
            $vierkandle->is_daily = true;
            $vierkandle->generate(4);
            \Laravel\Prompts\info('Done');
        }
        if(Vierkandle::where('is_daily', true)->where('is_express', true)->where('date', '=', now()->addDay()->format('Y-m-d'))->count() > 0) {
            \Laravel\Prompts\info('Vierkandle per ongeluk for tomorrow already exists. Skipping.');
        } else {
            \Laravel\Prompts\info('Creating vierkandle per ongeluk.');
            $express = new Vierkandle();
            $express->date = now()->addDay();
            $express->is_daily = true;
            $express->is_express = true;
            $express->generate(3);
            \Laravel\Prompts\info('Done');
        }
        \Laravel\Prompts\info('Created vierkandles for tomorrow.');
        \Laravel\Prompts\info('Done.');
    }
}
