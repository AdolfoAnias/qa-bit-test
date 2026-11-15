<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'assignee',
        'due_date',
    ];

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee');
    }
        
    protected $casts = [
        'due_date' => 'date',
    ];

    public const STATUSES = ['pending', 'in_progress', 'done'];

}
