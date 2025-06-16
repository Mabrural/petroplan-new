<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_id',
        'termin_id',
        'shipment_number',
        'vessel_id',
        'spk_id',
        'location',
        'fuel_id',
        'volume',
        'p',
        'a',
        'b',
        'completion_date',
        'lo',
        'status_shipment',
        'created_by',
    ];

    public function period() {
        return $this->belongsTo(Periode::class, 'period_id');
    }

    public function termin() {
        return $this->belongsTo(Termin::class, 'termin_id');
    }

    public function vessel() {
        return $this->belongsTo(Vessel::class, 'vessel_id');
    }

    public function spk() {
        return $this->belongsTo(Spk::class, 'spk_id');
    }

    public function fuel() {
        return $this->belongsTo(Fuel::class, 'fuel_id');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
