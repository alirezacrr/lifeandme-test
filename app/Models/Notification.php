<?php

namespace App\Models;

use App\Enums\NotificationTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'expire_at',
        'meta',
    ];

    protected $casts = [
        'type' => NotificationTypeEnum::class,
        'expire_at' => 'date',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
