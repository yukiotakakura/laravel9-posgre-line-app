<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LineloginChannel extends Model
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

    public function linedevelopersProvider(): BelongsTo
    {
        return $this->belongsTo(LinedevelopersProvider::class);
    }

    /**
     * 中間テーブル.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
