<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadShipmentDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'document_type_id',
        'attachment',
        'created_by',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
