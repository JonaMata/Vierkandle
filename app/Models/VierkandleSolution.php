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
    ];
    public function vierkandle() : BelongsTo
    {
        return $this->belongsTo(Vierkandle::class);
    }
}
