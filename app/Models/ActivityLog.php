<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $table = 'tblog_activity_logs';

    protected $primaryKey = 'id_activity_log';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'activity',
        'module',
        'description',
        'record_id',
        'old_values',
        'new_values',
    ];

    protected function casts(): array
    {
        return [
            'record_id' => 'integer',
            'old_values' => 'array',
            'new_values' => 'array',
            'created_at' => 'datetime',
        ];
    }

    // Aktivitas dilakukan oleh satu pengguna
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id_user'
        );
    }
}