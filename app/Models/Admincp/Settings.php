<?php

namespace App\Models\Admincp;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $guarded = [];
    protected $table = 'settings';

    public static function retrieve(string $name): string
    {
        $setting = Settings::query()
            ->where('name', $name)
            ->select('value')
            ->first();

        if ($setting && $setting->value) {
            return $setting->value;
        }

        return '';
    }
}
