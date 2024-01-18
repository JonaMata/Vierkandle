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
    protected $signature = 'app:daily-puzzles {size} {--date=}';

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
        info('Creating daily puzzles.');
        Vierkandle::factory()->create([
            'boardsize' => $this->argument('size'),
            'date' => $this->option('date') ?? Carbon::tomorrow()]
        );
        info('Created vierkandle for tomorrow.');
        info('Done.');
    }
}
