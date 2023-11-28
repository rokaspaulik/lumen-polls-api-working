<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Poll extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded = [];

    public function presentation(): BelongsTo
    {
        return $this->belongsTo(Presentation::class);
    }
}
