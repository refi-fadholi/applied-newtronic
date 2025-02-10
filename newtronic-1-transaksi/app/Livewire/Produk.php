<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Produk as ModelProduk;

class Produk extends Component
{

    public $pilihanMenu = 'lihat';
    public $produk, $stok, $harga;
    public $produkTerpilih;

    public function pilihHapus($id) {

        $this->produkTerpilih = ModelProduk::findOrFail($id);
        $this->pilihanMenu = 'hapus';
    }

    public function hapus() {
        $this->produkTerpilih->delete();
        $this->reset();
    }

    public function batal() {
          
        $this->reset();
    }

    public function simpanUbah() {

        $this->validate([
            'produk' => ['required', 'unique:produks,produk' . $this->produkTerpilih->id],
            'stok' => 'required',
            'harga' => 'required',
        ],
        [
            'produk.required' => 'Nama produk harus diisi',
            'produk.unique' => 'Nama produk sudah ada',
            'stok.required' => 'Stok harus diisi',
            'harga.required' => 'Harga harus diisi',
        ]);

        $simpan = $this->produkTerpilih;
        $simpan->produk = $this->produk;
        $simpan->stok = $this->stok;
        $simpan->harga = $this->harga;

        $simpan->save();

        $this->reset(['produk', 'stok', 'harga', 'produkTerpilih']);
        $this->pilihanMenu = 'lihat';
    }

    public function pilihUbah($id) {

        $this->produkTerpilih = ModelProduk::findOrFail($id);

        $this->produk = $this->produkTerpilih->produk;
        $this->stok = $this->produkTerpilih->stok;
        $this->harga = $this->produkTerpilih->harga;
        $this->pilihanMenu = 'ubah';
    }

    public function simpan() {

        $this->validate([
            'produk' => ['required', 'unique:produks,produk'],
            'stok' => 'required',
            'harga' => 'required',
        ],
        [
            'produk.required' => 'Nama produk harus diisi',
            'stok.required' => 'Stok harus diisi',
            'harga.required' => 'Peran harus diisi',
        ]);

        $simpan = new ModelProduk();
        $simpan->produk = $this->produk;
        $simpan->stok = $this->stok;
        $simpan->harga = $this->harga;
        $simpan->save();

        $this->reset(['produk', 'stok', 'harga']);
        $this->pilihanMenu = 'lihat';
    }

    public function pilihMenu($menu) {

        $this->pilihanMenu = $menu;

    }
    
    public function render()
    {
        return view('livewire.produk')->with([
            'semuaProduk' => ModelProduk::all()

        ]);
    }
}
