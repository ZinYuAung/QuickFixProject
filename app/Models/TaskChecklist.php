<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskChecklist extends Model
{
    //
    protected $table = 'task_checklists';

    protected $fillable = [
        'booking_id',
        'task_name',
        'is_completed',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
            'completed_at' => 'datetime',
        ];
    }

    public function booking() {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
