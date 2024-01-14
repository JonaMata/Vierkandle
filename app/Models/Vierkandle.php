<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Vierkandle extends Model
{
    use HasFactory;

    public function solutions(): HasMany
    {
        return $this->hasMany(VierkandleSolution::class);
    }

    public function getSolutionsAttribute(): Collection {
        return $this->solutions()->get();
    }
}
