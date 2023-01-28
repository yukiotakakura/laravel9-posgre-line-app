<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'access_token',
    ];

    /**
     * @return BelongsTo
     */
    public function linedevelopersProvider(): BelongsTo
    {
        return $this->belongsTo(LinedevelopersProvider::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('id', 'user_id', 'linebot_channel_id', 'friend_flag', 'line_user_id');
    }

}
