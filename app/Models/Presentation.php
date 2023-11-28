<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Presentation extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded = [];

    public function polls(): HasMany
    {
        return $this->hasMany(Poll::class);
    }
}
