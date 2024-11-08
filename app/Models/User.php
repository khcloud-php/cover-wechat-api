<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Auth\Authorizable;

class User extends Base implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['avatar', 'nickname', 'gender', 'setting', 'unread'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',
        'salt'
    ];

    protected $casts = [
        'setting' => 'json',
        'unread' => 'json'
    ];

    /**
     * 头像路径处理
     *
     * @param string $value
     * @return string
     */
    public function getAvatarAttribute($value)
    {
        if (!str_contains($value, 'http')) return env('STATIC_FILE_URL') . $value;
        return $value;
    }

    public function getBgFilePathAttribute($value): string
    {
        if (empty($value)) return '';
        if (!str_contains($value, 'http')) return env('STATIC_FILE_URL') . $value;
        return $value;
    }

    public function getMomentBgFilePathAttribute($value): string
    {
        if (empty($value)) return '';
        if (!str_contains($value, 'http')) return env('STATIC_FILE_URL') . $value;
        return $value;
    }

    public static function getUnreadById(int $id): array
    {
        $user = self::query()->find($id, ['unread']);
        return $user->unread;
    }

    public static function incrUnread(array $ids, string $field, int $from = 0, $num = 1): int
    {
        if (empty($ids)) return 0;
        if ($field == 'moment.num') {
            self::query()->whereIn('id', $ids)->update([
                'unread' => DB::raw("JSON_SET(unread, '$.moment.from', {$from})")
            ]);
        }
        return self::query()->whereIn('id', $ids)->update([
            'unread' => DB::raw("JSON_SET(unread, '$.{$field}', JSON_EXTRACT(unread, '$.{$field}') + {$num})")
        ]);
    }

    public static function clearUnread(array $ids, string $field): int
    {
        return self::query()->whereIn('id', $ids)->update([
            'unread' => DB::raw("JSON_SET(unread, '$.{$field}', 0)")
        ]);
    }
}
