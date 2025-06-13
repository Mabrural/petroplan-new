<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spk extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_id',
        'spk_number',
        'spk_date',
        'spk_file',
        'created_by',
    ];

    public function period()
    {
        return $this->belongsTo(Periode::class, 'period_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
