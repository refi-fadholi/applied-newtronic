<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaksi;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DetailTransaksi extends Component
{
    public $transaksi_id;
    public $produkList = [];
    public $transaksiList = [];
    public $details = [];
    public $kode_transaksi;
    public $tanggal;

    public function mount()
    {
        $this->transaksiList = Transaksi::all();
        $this->produkList = Produk::all();
        $this->details = [
            ['produk_id' => '', 'quantity' => 1]
        ];
        $this->tanggal = Carbon::now();

    }

    public function hapusItem($index)
    {
        unset($this->details[$index]);
        $this->details = array_values($this->details);
    }

    public function simpan()
    {
        $this->validate([
            'kode_transaksi' => 'required|unique:transaksis,kode_transaksi',
            'details.*.produk_id' => 'required|exists:produks,id',
            'details.*.quantity' => 'required|integer|min:1',
        ]);

        // Mulai transaksi
        DB::transaction(function () {
            
            // Simpan transaksi
            $transaksi = Transaksi::create([
                'kode_transaksi' => $this->kode_transaksi,
                'tanggal' => $this->tanggal,
            ]);

            // Simpan detail transaksi
            foreach ($this->details as $detail) {
                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id,
                    'id_produk' => $detail['produk_id'],
                    'quantity' => $detail['quantity'],
                ]);
            }
        });

        // Reset form setelah submit
        $this->reset(['kode_transaksi', 'tanggal', 'details']);
        $this->details = [['produk_id' => '', 'quantity' => 1]];

        session()->flash('message', 'Detail transaksi berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.detail-transaksi', [
            'detailTransaksi' => DetailTransaksi::with(['transaksi', 'produk'])->get(),
        ]);
    }
}
