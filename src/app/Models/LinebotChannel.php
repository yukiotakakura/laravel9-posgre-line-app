<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LinebotChannel extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'linedeveloper_provider_id',
        'channel_id',
        'name',
        'channel_secret',
    ];

    /**
     * @return BelongsTo
     */
    public function linedevelopersProvider(): BelongsTo
    {
        return $this->belongsTo(LinedevelopersProvider::class);
    }

}
