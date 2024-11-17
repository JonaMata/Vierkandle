<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VierkandleSolution extends Model
{

    protected $fillable = ['word', 'url', 'chain', 'bonus'];

    protected $casts = [
        'bonus' => 'boolean',
        'chain' => 'array',
    ];

    public function vierkandle() : BelongsTo
    {
        return $this->belongsTo(Vierkandle::class);
    }

//    public function getGuessedAttribute() : bool | null
//    {
//        if (!auth()->check()) {
//            return null;
//        }
//        return in_array($this->id, VierkandleSolve::ofUser(auth()->user(), $this->vierkandle)->solution_ids);
//    }
}
