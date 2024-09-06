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

    public static function getMomentCanSeeFriends(int|string $owner): array
    {
        $canWatchMine = Friend::query()->where('owner', $owner)
            ->where('status', FriendEnum::STATUS_PASS)
            ->whereJsonContains('setting', ["FriendPerm" => ["MomentAndStatus" => ['DontSeeHim' => '0']]])
            ->pluck('friend')->toArray();
        $friends = Friend::query()->where('friend', $owner)
            ->where('status', FriendEnum::STATUS_PASS)
            ->whereJsonContains('setting', ["FriendPerm" => ["MomentAndStatus" => ['DontLetHimSeeIt' => '0']]])
            ->get(['owner', 'setting'])->toArray();
        $canWatchThem = array_column($friends, 'owner');
        $canWatchFriends = array_intersect($canWatchThem, $canWatchMine);
        $friends = array_column($friends, null, 'owner');
        foreach ($friends as $key => $friend) {
            if (!in_array($key, $canWatchFriends)) {
                unset($friends[$key]);
            }
        }
        return $friends;
    }
}
