<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $table = 'status'; // Nama tabel di database

    protected $fillable = ['status_name']; // Kolom yang dapat diisi
}
