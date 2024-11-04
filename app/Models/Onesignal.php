<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Ladumor\OneSignal\OneSignal as OneSignalManager;

class Onesignal extends Model
{
    use HasFactory;
    protected $fillable = [
        'player_id',
        'user_id'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    // Send to notification by user role
    public static function sendNotifications()
    {
        $roleChefId =  Role::where('name', 'chef')->first()->id;
        $roleCashierId =  Role::where('name', 'cashier')->first()->id;
        // Get all staff from store
        $users = Auth::user()->store->users;
        foreach ($users as $user) {
            if ($user->role_id === $roleChefId) {
                // Push notofication to Chef role
                self::pushNotification($user, env('VUE_APP_BASE_URL') . 'chef');
            } else if ($user->role_id === $roleCashierId) {
                // Push notofication to Cashier role
                self::pushNotification($user, env('VUE_APP_BASE_URL') . 'cashier');
            }
        }
    }

    // Using array filter: https://stackoverflow.com/questions/61410592/laravel-filter-array-based-on-element-value
    // Using array shift: https://stackoverflow.com/questions/1617157/how-to-get-the-first-item-from-an-associative-php-array
    public static function pushNotification($user, $url)
    {
        // Get OneSignal playerIds from an user
        $players = [];
        foreach ($user->onesignals as $onesignal) {
            array_push($players, $onesignal->player_id);
        }
        // Put player id to OneSignal fields
        $fields['include_player_ids'] = $players;;
        // Put notification header to OneSignal fields
        $fields['headings'] = array("en" => "Restaurant Management System");
        // Put message to OneSignal fields
        $message = 'You have a new order. Please check.';
        // Put site url to OneSignal fields
        $fields['url'] = $url;
        $fields['image'] = 'https://firebasestorage.googleapis.com/v0/b/vc-2023.appspot.com/o/chicken_fry.jpg?alt=media&token=89ecdfe3-e153-4828-98b7-ecaa734a0f3d';
        // Push a notification to player to OneSignal app
        OneSignalManager::sendPush($fields, $message);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
