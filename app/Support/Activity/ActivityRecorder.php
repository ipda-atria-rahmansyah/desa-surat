<?php

namespace App\Support\Activity;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

/**
 * Mencatat aktivitas aplikasi melalui satu pintu agar format log konsisten.
 *
 * Recorder ini hanya menerima nilai enum yang tersedia pada migration aktivitas
 * dan menyimpan deskripsi aman tanpa password, token, atau NIK.
 */
class ActivityRecorder
{
    /**
     * Menyimpan satu aktivitas dengan identitas pengguna dari session server.
     *
     * @param  string  $activity  Salah satu enum CREATE, UPDATE, DELETE, LOGIN, LOGOUT.
     * @param  string  $module  Modul sumber aktivitas.
     * @param  string  $description  Keterangan umum yang tidak sensitif.
     * @param  Model|int|string|null  $record  Model atau ID record terkait.
     * @return ActivityLog Log aktivitas yang tersimpan.
     *
     * @throws \InvalidArgumentException Jika activity bukan enum migration.
     */
    public function record(string $activity, string $module, string $description, Model|int|string|null $record = null): ActivityLog
    {
        $allowedActivities = ['CREATE', 'UPDATE', 'DELETE', 'LOGIN', 'LOGOUT'];

        if (! in_array($activity, $allowedActivities, true)) {
            throw new \InvalidArgumentException('Jenis aktivitas tidak didukung.');
        }

        return ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => $activity,
            'module' => $module,
            'description' => $description,
            'record_id' => $record instanceof Model ? $record->getKey() : $record,
        ]);
    }
}
