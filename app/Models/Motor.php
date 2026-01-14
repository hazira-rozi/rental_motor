<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Motor extends Model
{
    protected $fillable = ['nopol', 'merk', 'harga_sewa', 'status'];

    // Relasi: Satu motor bisa memiliki banyak riwayat rental
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
}