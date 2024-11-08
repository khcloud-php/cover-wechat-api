<?php

namespace App\Models;

use App\Enums\Database\FriendEnum;
use Illuminate\Database\Eloquent\SoftDeletes;

class Friend extends Base
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['nickname', 'type', 'status', 'unread', 'remark', 'setting'];

    use SoftDeletes;

    protected $casts = [
        'setting' => 'json'
    ];

    public function to(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'friend', 'id');
    }

    public function friend(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'friend', 'id');
    }

    public function owner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'owner', 'id');
    }

    public static function checkIsFriend(int|string $owner, int|string $friend, $returnFriend = false): bool|array
    {
        $friend = Friend::query()
            ->where('friend', $owner)
            ->where('owner', $friend)
            ->where('status', FriendEnum::STATUS_PASS)
            ->first(['nickname'])->toArray();
        return $returnFriend ? [(bool)$friend, $friend] : (bool)$friend;
    }

    public function getBgFilePathAttribute($value): string
    {
        if (empty($value)) return '';
        if (!str_contains($value, 'http')) return env('STATIC_FILE_URL') . $value;
        return $value;
    }

    public static function getMomentCanSeeFriendIds(int $owner, $reverse = false): array
    {
        $seeHimField = $reverse ? 'DontLetHimSeeIt' : 'DontSeeHim';
        $letHimSeeField = $reverse ? 'DontSeeHim' : 'DontLetHimSeeIt';
        $seeHim = Friend::query()->where('owner', $owner)
            ->where('status', FriendEnum::STATUS_PASS)
            ->whereJsonContains('setting', ["FriendPerm" => ["MomentAndStatus" => [$seeHimField => '0']]])
            ->pluck('friend')->toArray();
        $letHimSee = Friend::query()->where('friend', $owner)
            ->where('status', FriendEnum::STATUS_PASS)
            ->whereJsonContains('setting', ["FriendPerm" => ["MomentAndStatus" => [$letHimSeeField => '0']]])
            ->pluck('owner')->toArray();
        return array_intersect($seeHim, $letHimSee);
    }

    public static function getPublicFriendIds(int $owner, int $him): array
    {
        $ownerCanSee = self::getMomentCanSeeFriendIds($owner, true);
        if ($owner == $him) return $ownerCanSee;
        if (empty($ownerCanSee)) return [];
        $himCanSee = self::getMomentCanSeeFriendIds($him, true);
        return array_intersect($ownerCanSee, $himCanSee);
    }
}
