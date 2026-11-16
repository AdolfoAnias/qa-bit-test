<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Task extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'description',
        'status',
        'assignee',
        'due_date',
    ];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assignee');
    }
         
    protected $casts = [
        'due_date' => 'date',
    ];

    public const STATUSES = ['pending', 'in_progress', 'done'];

}
