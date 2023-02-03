<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LinedevelopersProvider extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'provider_id',
        'name',
    ];

    public function lineloginChannels(): HasMany
    {
        return $this->hasMany(LineloginChannel::class);
    }

    public function linebotChannels(): HasMany
    {
        return $this->hasMany(LinebotChannel::class);
    }
}
