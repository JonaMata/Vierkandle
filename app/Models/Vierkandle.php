<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Vierkandle extends Model
{
    use HasFactory;

    protected $appends = ['solution_count'];

    public function solutions(): HasMany
    {
        return $this->hasMany(VierkandleSolution::class);
    }

    public function getSolutionsAttribute(): Collection {
        return $this->solutions()->get();
    }

    public function getSolutionCountAttribute(): int {
        return $this->solutions()->count();
    }
}
