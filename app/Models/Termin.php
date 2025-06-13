<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Termin extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_id',
        'termin_number',
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
