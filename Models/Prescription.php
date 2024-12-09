<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'patients_id',
        'med_id',
        'prescription_date',
        'qty_taken',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patients_id');
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'med_id');
    }
}
