<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'name',
        'birthdate',
        'sex',
        'address',
        'ailment',
        'date_registered',
    ];

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'patients_id');
    }
}

