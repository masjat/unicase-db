<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Custom extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'custom_cases';

    // Primary key custom
    protected $primaryKey = 'custom_case_id';

    // Tipe data primary key bukan incrementing integer default (false jika bukan)
    public $incrementing = true;

    // Tipe primary key
    protected $keyType = 'int';

    // Kolom-kolom yang boleh diisi secara mass-assignment
    protected $fillable = [
        'user_id',
        'image_url',
        'case_type',
        'print_effect',
        'brand',
        'phone_model',
        'description',
        'price_case',
        'price_print',
        'total_price',
    ];

    // Kolom tanggal
    public $timestamps = true;

    /**
     * Relasi ke tabel users (jika user_id foreign key)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}