<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VierkandleSolve extends Model
{
    use HasFactory;

    protected $casts = [
        'solution_ids' => 'array',
    ];

    protected $fillable = ['vierkandle_id', 'user_id', 'solution_ids'];

    public function vierkandle()
    {
        return $this->belongsTo(Vierkandle::class);
    }

    public static function ofUser(User $user, Vierkandle $vierkandle)
    {
        $solve = static::query()->where('vierkandle_id', $vierkandle->id)->where('user_id', $user->id)->first();
        if (!$solve) {
            $solve = static::query()->create([
                'vierkandle_id' => $vierkandle->id,
                'user_id' => $user->id,
                'solution_ids' => [],
            ]);
        }
        return $solve;
    }

    public function addWord(VierkandleSolution $solution)
    {
        if (in_array($solution->id, $this->solution_ids)) {
            return;
        }
        $this->solution_ids = [...$this->solution_ids, $solution->id];
        $this->save();
    }
}
