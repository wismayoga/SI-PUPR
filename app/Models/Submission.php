<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = ['report_id', 'file_path'];

    // Each submission belongs to a report
    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
