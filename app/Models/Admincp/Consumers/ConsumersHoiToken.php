<?php

namespace App\Models\Admincp\Consumers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsumersHoiToken extends Model
{
    protected $guarded = [];

    protected $table = 'consumers__hoi_token';

    public function consumer(): BelongsTo
    {
        return $this->belongsTo('\App\Admincp\Consumers\Consumers');
    }
}
