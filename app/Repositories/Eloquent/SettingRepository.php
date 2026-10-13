<?php

namespace App\Repositories\Eloquent;

use App\Models\Settings as Setting;
use App\Repositories\Contracts\SettingRepositoryInterface;
use Illuminate\Support\Facades\DB;

class SettingRepository implements SettingRepositoryInterface
{
    public function allKeyed(): array
    {
        return Setting::query()->pluck('value', 'key')->all();
    }

    public function setMany(array $rows): void
    {
        DB::transaction(function () use ($rows) {
            foreach ($rows as $key => $row) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['group' => $row['group'], 'value' => $row['value']]
                );
            }
        });
    }
}
