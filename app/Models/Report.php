<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = ['assigned_user_id', 'report_name', 'deadline', 'status'];

    // Each report is assigned to one user
    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    // Each report can have one submission
    public function submission()
    {
        return $this->hasOne(Submission::class);
    }
}
