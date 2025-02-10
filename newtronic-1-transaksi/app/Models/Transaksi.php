<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Transaksi extends Model
{

    use HasFactory;

    protected $fillable = [
        'kode_transaksi', // Tambahkan kode_transaksi di sini
        'tanggal', // kolom lain yang ingin Anda izinkan untuk mass assignment
    ];

    public function details()
{
    return $this->hasMany(DetailTransaksi::class, 'id_transaksi');
}
public function detailTransaksi()
{
    return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id');
}
}
