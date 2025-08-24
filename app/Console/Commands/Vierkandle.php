<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Vierkandle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:vierkandle {size} {word?} {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $vierkandle = new \App\Models\Vierkandle();
        $vierkandle->date = $this->argument('date') ?? now();
        $vierkandle->is_daily = false;

        $tries = $vierkandle->generate($this->argument('size'), $this->argument('word'));
        \Laravel\Prompts\info("Generated vierkandle for {$vierkandle->date} with {$tries} tries.");
        for ($i = 0; $i < $vierkandle->size; $i++) {
            $row = [];
            for ($j = 0; $j < $vierkandle->size; $j++) {
                $row[] = str_split($vierkandle->letters)[$i*$vierkandle->size+$j];
            }
            \Laravel\Prompts\info(implode(' ', $row));
        }
    }
}
