<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaksi as ModelTransaksi;
use App\Models\DetailTransaksi;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class Transaksi extends Component
{
    public $pilihanMenu = 'lihat';
    public $kode_transaksi, $tanggal;
    public $details = [];
    public $produkList = [];
    public $transaksiTerpilih;
    public $stokProduk;
    public $transaksi;
    public $details2 = [];

    protected $fillable = [
        'kode_transaksi',
    ];

    public function mount()
    {
        $this->produkList = Produk::all(); // Daftar produk

        // $this->tanggal =Carbon::now();
        $this->kode_transaksi = $this->generateKodeTransaksi();


        
        // $this->tanggal = // Set default tanggal transaksi
    }

        // **🔹 Validasi stok secara otomatis ketika quantity berubah**
        public function updatedDetails($value, $key)
{
    // Pecah key menjadi array, akan menghasilkan index dan field
    $keys = explode('.', $key);

    // Pastikan ada 2 elemen: index dan field (produk_id atau quantity)
    if (count($keys) !== 2) return; 

    $index = $keys[0]; // Ambil index (0, 1, dst.)
    $field = $keys[1]; // Ambil field (produk_id atau quantity)


    // Jika field yang berubah adalah produk_id atau quantity
    if ($field === 'produk_id' || $field === 'quantity') {
        // Ambil produk_id dan quantity
        $produkId = $this->details[$index]['produk_id'] ?? null;
        $quantity = $this->details[$index]['quantity'] ?? 0;

        // Jika produk_id tersedia, cari produk berdasarkan ID
        if ($produkId) {
            $produk = \App\Models\Produk::find($produkId);

            // Jika produk tidak ditemukan, beri error pada produk_id
            if (!$produk) {
                $this->addError("details.$index.produk_id", "Produk tidak valid.");
                return;
            }

            $stokProduk = $produk->stok; // Ambil stok produk dari tabel 'produks'
            $this->stokProduk = $produk->stok; // Ambil stok produk dari tabel 'produks'

            // dd($quantity, $stokProduk, $quantity > $stokProduk);


            // Validasi jika quantity lebih besar dari stok
            if ($quantity > $stokProduk) {
                // Tambahkan error jika stok tidak mencukupi
                $this->addError("details.$index.quantity", "Stok tidak mencukupi. Maksimal: {$stokProduk}.");

                // dd($this->getErrors());

            } else {
                // Reset error jika valid
                $this->resetErrorBag("details.$index.quantity");
            }
        }
    }
}

public function lihatDetail($transaksiId)
{
    $this->transaksi = ModelTransaksi::findOrFail($transaksiId); 

    // Set properti untuk transaksi utama
    $this->kode_transaksi = $this->transaksi->kode_transaksi;
    $this->tanggal = $this->transaksi->tanggal;

    // Set properti untuk detail transaksi
    $this->details = $this->transaksi->detailTransaksi->map(function ($detail) {
        return [
            'produk_id' => $detail->id_produk,
            'quantity' => $detail->quantity,
        ];
    })->toArray();

    $this->pilihanMenu = 'detail';
}

public function resetMenu()
{
    $this->pilihanMenu = 'list'; // Kembali ke daftar transaksi
}

        

    //Biar validasi error berfungsi pakai wire
    public function cekNilai() {
        return true;
    }

    public function tambahItem()
    {
        $this->details[] = ['produk_id' => '', 'quantity' => 1];
    }

    public function hapusItem($index)
    {
        unset($this->details[$index]);
        $this->details = array_values($this->details);
    }

    public function batal() {
          
        $this->reset();
    }

    public function pilihHapus($id) {

        $this->transaksiTerpilih = ModelTransaksi::with('detailTransaksi')->findOrFail($id);
    
        // Set properti untuk transaksi utama
        $this->kode_transaksi = $this->transaksiTerpilih->kode_transaksi;
        $this->tanggal = $this->transaksiTerpilih->tanggal;
    
        // Set properti untuk detail transaksi
        $this->details = $this->transaksiTerpilih->detailTransaksi->map(function ($detail) {
            return [
                'produk_id' => $detail->id_produk,
                'quantity' => $detail->quantity,
            ];
        })->toArray();

        $this->pilihanMenu = 'hapus';
    }
    
    public function hapus() {

        if (!$this->transaksiTerpilih) {
            return;
        }
    
        DB::transaction(function () {
            // Ambil semua detail transaksi
            $details = DetailTransaksi::where('id_transaksi', $this->transaksiTerpilih->id)->get();
    
            foreach ($details as $detail) {
                // Ambil produk terkait
                $produk = Produk::find($detail->id_produk);
    
                if ($produk) {
                    // Kembalikan stok yang telah digunakan dalam transaksi
                    $produk->stok += $detail->quantity;
                    $produk->save();
                }
    
                // Hapus detail transaksi
                $detail->delete();
            }
    
            // Hapus transaksi utama
            $this->transaksiTerpilih->delete();
        });
    
        // Reset state Livewire
        $this->reset();
    
        // Flash message
        session()->flash('message', 'Transaksi berhasil dihapus, dan stok telah dikembalikan.');
    }

    
public function simpanUbah()
{
    // Validasi input
    $this->validate([
        'kode_transaksi' => 'required',
        'tanggal' => 'required|date',
        'details' => 'required|array|min:1',
        'details.*.produk_id' => 'required',
        'details.*.quantity' => 'required|integer|min:1',
    ],
    [
        'tanggal.required' => 'Tanggal transaksi harus diisi',
        'details.required' => 'Minimal harus ada satu produk dalam transaksi',
        'details.min' => 'Minimal harus ada satu produk dalam transaksi',
        'details.*.produk_id.required' => 'Pilih produk sebelum menyimpan transaksi.',
        'details.*.quantity.required' => 'Masukkan jumlah produk.',
        'details.*.quantity.min' => 'Jumlah produk minimal 1.',
    ]);

    DB::transaction(function () {
        // Ambil transaksi yang akan diperbarui
        $transaksi = ModelTransaksi::findOrFail($this->transaksiTerpilih->id);

        // Ambil detail transaksi lama
        $detailLama = DetailTransaksi::where('id_transaksi', $transaksi->id)->get();

        // Kembalikan stok lama sebelum update
        foreach ($detailLama as $detail) {
            $produk = Produk::find($detail->id_produk);
            if ($produk) {
                $produk->stok += $detail->quantity; // Kembalikan stok lama
                $produk->save();
            }
        }

        // Hapus detail transaksi lama
        DetailTransaksi::where('id_transaksi', $transaksi->id)->delete();

        // Perbarui transaksi
        $transaksi->tanggal = $this->tanggal;
        $transaksi->save();

        // Simpan detail transaksi baru & update stok
        foreach ($this->details as $detail) {
            $produk = Produk::find($detail['produk_id']);

            if ($produk) {
                // Hitung stok tersedia setelah dikembalikan dari transaksi lama
                $stokTersedia = $produk->stok; // Sudah dikembalikan sebelumnya

                // Validasi stok cukup
                if ($stokTersedia < $detail['quantity']) {
                    throw new \Exception("Stok produk {$produk->produk} tidak mencukupi.");
                }

                // Kurangi stok sesuai quantity baru
                $produk->stok -= $detail['quantity'];
                $produk->save();
            }

            // Simpan detail transaksi baru
            DetailTransaksi::create([
                'id_transaksi' => $transaksi->id,
                'id_produk' => $detail['produk_id'],
                'quantity' => $detail['quantity'],
            ]);
        }
    });

    // Reset form setelah submit
    $this->reset(['tanggal', 'details']);
    $this->details = [['produk_id' => '', 'quantity' => 1]];

    session()->flash('message', 'Transaksi berhasil diperbarui.');
    $this->pilihanMenu = 'lihat';
}


    public function pilihUbah($id)
    {
        // Ambil transaksi yang dipilih
        $this->transaksiTerpilih = ModelTransaksi::with('detailTransaksi')->findOrFail($id);
    
        // Set properti untuk transaksi utama
        $this->kode_transaksi = $this->transaksiTerpilih->kode_transaksi;
        $this->tanggal = $this->transaksiTerpilih->tanggal;
    
        // Set properti untuk detail transaksi
        $this->details = $this->transaksiTerpilih->detailTransaksi->map(function ($detail) {
            return [
                'produk_id' => $detail->id_produk,
                'quantity' => $detail->quantity,
            ];
        })->toArray();
    
        // Pilih menu ubah
        $this->pilihanMenu = 'ubah';
    }
    
    public function simpan()
    {
        // Validasi input
        $this->validate([
            'kode_transaksi' => 'required',
            'tanggal' => 'required|date',
            'details' => 'required|array|min:1', // Detail produk tidak boleh kosong
            'details.*.produk_id' => 'required',
            'details.*.quantity' => 'required|integer|min:1|max:' . $this->stokProduk,
        ],
        [
            'tanggal.required' => 'Tanggal transaksi harus diisi',
            'details.required' => 'Minimal harus ada satu produk dalam transaksi',
            'details.min' => 'Minimal harus ada satu produk dalam transaksi',
            'details.*.produk_id.required' => 'Pilih produk sebelum menyimpan transaksi.',
            'details.*.quantity.required' => 'Masukkan jumlah produk.',
            'details.*.quantity.min' => 'Jumlah produk minimal 1.',
            'details.*.quantity.max' => 'Jumlah produk melebihi jumlah yang tersedia.',
        ]);
    
        // Mulai transaksi database
        DB::transaction(function () {
            // Buat transaksi baru
            $transaksi = ModelTransaksi::create([
                'kode_transaksi' => $this->generateKodeTransaksi(), // Kode transaksi yang sudah di-generate
                'tanggal' => $this->tanggal,
            ]);
    
            // Simpan detail transaksi yang baru
            foreach ($this->details as $detail) {
                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id,
                    'id_produk' => $detail['produk_id'],
                    'quantity' => $detail['quantity'],
                ]);
            }

             // Ambil stok produk sebelum dikurangi
        $produk = Produk::find($detail['produk_id']);

        // Pastikan produk ada
        if ($produk) {
            // Kurangi stok berdasarkan quantity yang dijual
            $produk->stok -= $detail['quantity'];

            // Pastikan stok tidak kurang dari 0
            if ($produk->stok < 0) {
                throw new \Exception("Stok produk {$produk->produk} tidak mencukupi.");
            }

            // Update stok di tabel produk
            $produk->save();
        }


        });
    
        // Reset form setelah submit
        $this->reset(['tanggal', 'details']);
        $this->details = [['produk_id' => '', 'quantity' => 1]];
    
        session()->flash('message', 'Transaksi berhasil ditambahkan.');
        $this->pilihanMenu = 'lihat';

    }
    
    public function generateKodeTransaksi()
    {
        $id = ModelTransaksi::max('id') + 1;
        $date = now()->format('Ymd');
        return $id . $date;
    }

    public function pilihMenu($menu) {

        $this->pilihanMenu = $menu;

    }

    // Relasi ke DetailTransaksi
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi');
    }


    public function render()
    {
        return view('livewire.transaksi', [
            'semuaTransaksi' => ModelTransaksi::all()
        ]);
    }
}
