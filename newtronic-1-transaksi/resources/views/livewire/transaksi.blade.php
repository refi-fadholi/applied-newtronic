<div>
    <div>
        <div class="container">
            <div class="row my-2">
                <div class="col-12">
                    <button onclick="kosongkanInput();" wire:click="pilihMenu('lihat')" class="btn {{ $pilihanMenu == 'lihat' ? 'btn-primary' : 'btn-outline-primary' }}" hidden>Semua Pengguna</button>
                    <button wire:loading class="btn btn-info">Loading ...</button>
                </div>
            </div>
            <div class="row">
                <div class="col-12">

                    {{-- Read 1--}}
                    @if ($pilihanMenu == 'lihat')
                    <div class="card border-primary">
                        <div class="card-header">Daftar Transaksi</div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">No.</th>
                                        <th>Kode Transaksi</th>
                                        <th>Tanggal</th>
                                        <th class="text-center">Detail</th>
                                        <th class="text-center">Ubah</th>
                                        <th class="text-center">Hapus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($semuaTransaksi as $transaksi)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $transaksi->kode_transaksi }}</td>
                                        <td>{{ \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }}</td>
                                        <td class="text-center">
                                            <button wire:click="lihatDetail({{ $transaksi->id }})" class="btn {{ $pilihanMenu == 'detail' ? 'btn-info' : 'btn-outline-info' }}"><i class="fa-solid fa-file"></i></button>
                                        </td>
                                        <td class="text-center">
                                            <button wire:click="pilihUbah({{ $transaksi->id }})" class="btn {{ $pilihanMenu == 'edit' ? 'btn-primary' : 'btn-outline-primary' }}"><i class="fa-solid fa-edit"></i></button>
                                        </td>
                                        <td class="text-center">
                                            <button wire:click="pilihHapus({{ $transaksi->id }})" class="btn {{ $pilihanMenu == 'hapus' ? 'btn-danger' : 'btn-outline-danger' }}"><i class="fa-solid fa-trash"></i></button>
                                        </td>
                                    </tr>
                                        
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row my-2">
                                <div class="col-12">
                                    <button onclick="kosongkanInput();" wire:click="pilihMenu('tambah')" class="float-end btn btn-success"><b>&plus;</b> Tambah Transaksi</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    @elseif ($pilihanMenu == 'detail')
                    <div class="card border-primary">
                        <div class="card-header">Detail Transaksi</div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Kode Transaksi</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control form-control-sm" value="{{ $transaksi->kode_transaksi }}" readonly/>
                                </div>
                            </div>
                        
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Tanggal Transaksi</label>
                                <div class="col-sm-6">
                                    <input type="datetime-local" class="form-control form-control-sm" value="{{ $transaksi->tanggal }}" readonly/>
                                </div>
                            </div>
                        
                            
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Produk</th>
                                        <th>Quantity</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($details as $index => $detail)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <select wire:model="details.{{ $index }}.produk_id" id="produk_{{ $index }}" class="form-control produk-tambah-produk" onchange="cekDuplikatProduk(this);" data-prev-value="" wire:change="cekNilai()">
                                                <option value="">-- Pilih Produk --</option>
                                                @foreach($produkList as $produk)
                                                    <option value="{{ $produk->id }}" data-harga="{{ $produk->harga }}" data-stok="{{ $produk->stok }}">
                                                        {{ $produk->produk }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error("details.$index.produk_id") 
                                                <span class="text-danger">{{ $message }}</span> 
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="number" wire:model="details.{{ $index }}.quantity" id="quantity_{{ $index }}" class="form-control stok-tambah-produk" min="1" wire:blur="cekNilai()">
                                            @error("details.$index.quantity") 
                                                <span class="text-danger">{{ $message }}</span> 
                                            @enderror
                                        </td>
                                        <td>
                                            @php
                                                $produk = collect($produkList)->firstWhere('id', $detail['produk_id']);
                                                $harga = $produk ? $produk['harga'] : 0;
                                            @endphp
                                            Rp {{ number_format($harga, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            @php
                                                $total = $harga * ($detail['quantity'] ?? 0);
                                            @endphp
                                            Rp {{ number_format($total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table> 
                        
                            <button type="button" class="btn btn-secondary mt-3" wire:click="batal">Kembali</button>
                        </div>
                    </div>

                    {{-- Create --}}
                    @elseif ($pilihanMenu == 'tambah')
                    <div class="card border-primary">
                        <div class="card-header">Tambah Transaksi</div>
                        <div class="card-body">
                        <form id="form-tambah-transaksi" wire:submit='simpan'>

                            <div class="row mb-3">
                                <label for="kode_transaksi" class="col-sm-3 col-form-label">Kode Transaksi</label>
                                <div class="col-sm-6">
                                    <input type="text" id="kode_transaksi" class="form-control form-control-sm resetable" wire:model='kode_transaksi' readonly='true'/>
                                    @error('kode_transaksi')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="tanggal" class="col-sm-3 col-form-label">Tanggal Transaksi</label>
                                <div class="col-sm-6">
                                    <input type="datetime-local" id="tanggal" class="form-control form-control-sm resetable" wire:model='tanggal' value="{{ $tanggal }}"/>
                                    @error('tanggal')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Produk</th>
                                        <th>Quantity</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($details as $index => $detail)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <select wire:model="details.{{ $index }}.produk_id" id="produk_{{ $index }}" class="form-control produk-tambah-produk" onchange="cekDuplikatProduk(this);" data-prev-value="" wire:change="cekNilai()">
                                                <option value="">-- Pilih Produk --</option>
                                                @foreach($produkList as $produk)
                                                    <option value="{{ $produk->id }}" data-harga="{{ $produk->harga }}" data-stok="{{ $produk->stok }}">
                                                        {{ $produk->produk }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error("details.$index.produk_id") 
                                                <span class="text-danger">{{ $message }}</span> 
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="number" wire:model="details.{{ $index }}.quantity" id="quantity_{{ $index }}" class="form-control stok-tambah-produk" min="1" wire:blur="cekNilai()">
                                            @error("details.$index.quantity") 
                                                <span class="text-danger">{{ $message }}</span> 
                                            @enderror
                                        </td>
                                        <td>
                                            @php
                                                $produk = collect($produkList)->firstWhere('id', $detail['produk_id']);
                                                $harga = $produk ? $produk['harga'] : 0;
                                            @endphp
                                            Rp {{ number_format($harga, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            @php
                                                $total = $harga * ($detail['quantity'] ?? 0);
                                            @endphp
                                            Rp {{ number_format($total, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm" wire:click="hapusItem({{ $index }})"><i class="fa-solid fa-trash"></i> Hapus</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6">
                                            <div>
                                                <button type="button" class="btn btn-dark" wire:click="tambahItem">&plus; Tambah Transaksi</button>
                                                <div class="text-danger mb-2 warning-stok-tambah-kurang">{{ $errors->first('details') }}</div>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                
                
                            <div class="mt-3">
                                <button type="submit" class="btn btn-success" onclick="submitTambah(this);">Simpan</button>
                                <button type="button" class="btn btn-secondary" wire:click="batal">Kembali</button>
                            </div>
                        </form>
                        </div>
                    </div>
                    @elseif ($pilihanMenu == 'ubah')
                    <div class="card border-primary">
                        <div class="card-header">Ubah Transaksi</div>
                        <div class="card-body">
                        <form id="form-ubah-transaksi" wire:submit='simpanUbah'>

                            <div class="row mb-3">
                                <label for="kode_transaksi" class="col-sm-3 col-form-label">Kode Transaksi</label>
                                <div class="col-sm-6">
                                    <input type="text" id="kode_transaksi" class="form-control form-control-sm resetable" wire:model='kode_transaksi' readonly='true'/>
                                    @error('kode_transaksi')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="tanggal" class="col-sm-3 col-form-label">Tanggal Transaksi</label>
                                <div class="col-sm-6">
                                    <input type="datetime-local" id="tanggal" class="form-control form-control-sm resetable" wire:model='tanggal' value="{{ $tanggal }}"/>
                                    @error('tanggal')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                
                
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Produk</th>
                                        <th>Quantity</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($details as $index => $detail)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <select wire:model="details.{{ $index }}.produk_id" id="produk_{{ $index }}" class="form-control produk-tambah-produk" onchange="cekDuplikatProduk(this);" data-prev-value="" wire:change="cekNilai()">
                                                <option value="">-- Pilih Produk --</option>
                                                @foreach($produkList as $produk)
                                                    <option value="{{ $produk->id }}" data-harga="{{ $produk->harga }}" data-stok="{{ $produk->stok }}">
                                                        {{ $produk->produk }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error("details.$index.produk_id") 
                                                <span class="text-danger">{{ $message }}</span> 
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="number" wire:model="details.{{ $index }}.quantity" id="quantity_{{ $index }}" class="form-control stok-tambah-produk" min="1" wire:blur="cekNilai()">
                                            @error("details.$index.quantity") 
                                                <span class="text-danger">{{ $message }}</span> 
                                            @enderror
                                        </td>
                                        <td>
                                            @php
                                                $produk = collect($produkList)->firstWhere('id', $detail['produk_id']);
                                                $harga = $produk ? $produk['harga'] : 0;
                                            @endphp
                                            Rp {{ number_format($harga, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            @php
                                                $total = $harga * ($detail['quantity'] ?? 0);
                                            @endphp
                                            Rp {{ number_format($total, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm" wire:click="hapusItem({{ $index }})"><i class="fa-solid fa-trash"></i> Hapus</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6" class="">
                                            <div class="">
                                                <button type="button" class="btn btn-dark" wire:click="tambahItem">&plus; Tambah Transaksi</button>
                                                <div class="text-danger mb-2 warning-stok-tambah-kurang">{{ $errors->first('details') }}</div>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>      
                
                
                            <div class="mt-3">
                                <button type="submit" class="btn btn-success" onclick="submitTambah(this);">Simpan</button>
                                <button type="button" class="btn btn-secondary" wire:click="batal">Kembali</button>
                            </div>
                        </form>
                        </div>
                    </div>
                    @elseif ($pilihanMenu == 'hapus') 
                    <div class="card border-danger">
                        <div class="card-header  bg-danger text-white">Hapus Pengguna</div>
                        <div class="card-body">
                            Apakah anda yakin akan menghapus transaksi ini?<br>
                            <div class="card-body">        
                                    <div class="row mb-3">
                                        <label for="kode_transaksi" class="col-sm-3 col-form-label">Kode Transaksi</label>
                                        <div class="col-sm-6">
                                            <input type="text" id="kode_transaksi" class="form-control form-control-sm resetable" wire:model='kode_transaksi' readonly='true'/>
                                            @error('kode_transaksi')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
        
                                    <div class="row mb-3">
                                        <label for="tanggal" class="col-sm-3 col-form-label">Tanggal Transaksi</label>
                                        <div class="col-sm-6">
                                            <input type="datetime-local" id="tanggal" class="form-control form-control-sm resetable" wire:model='tanggal' value="{{ $tanggal }}"/>
                                            @error('tanggal')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                        
                        
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Produk</th>
                                                <th>Quantity</th>
                                                <th>Harga</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($details as $index => $detail)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <select wire:model="details.{{ $index }}.produk_id" id="produk_{{ $index }}" class="form-control produk-tambah-produk" onchange="cekDuplikatProduk(this);" data-prev-value="" wire:change="cekNilai()">
                                                        <option value="">-- Pilih Produk --</option>
                                                        @foreach($produkList as $produk)
                                                            <option value="{{ $produk->id }}" data-harga="{{ $produk->harga }}" data-stok="{{ $produk->stok }}">
                                                                {{ $produk->produk }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error("details.$index.produk_id") 
                                                        <span class="text-danger">{{ $message }}</span> 
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="number" wire:model="details.{{ $index }}.quantity" id="quantity_{{ $index }}" class="form-control stok-tambah-produk" min="1" wire:blur="cekNilai()">
                                                    @error("details.$index.quantity") 
                                                        <span class="text-danger">{{ $message }}</span> 
                                                    @enderror
                                                </td>
                                                <td>
                                                    @php
                                                        $produk = collect($produkList)->firstWhere('id', $detail['produk_id']);
                                                        $harga = $produk ? $produk['harga'] : 0;
                                                    @endphp
                                                    Rp {{ number_format($harga, 0, ',', '.') }}
                                                </td>
                                                <td>
                                                    @php
                                                        $total = $harga * ($detail['quantity'] ?? 0);
                                                    @endphp
                                                    Rp {{ number_format($total, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>      
                        

                                </div>
                            <button class="btn btn-danger mt-3" wire:click="hapus">Hapus</button>
                            <button type="button" class="btn btn-secondary mt-3" wire:click="batal">Kembali</button>
                        </div>
                    </div> 
                      
                    @endif
    
                </div>
            </div>
    
            
        </div>
    
    </div>
    
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>
    
    function kosongkanInput() {
            document.querySelectorAll(".resetable").forEach(input => input.value = "");
            document.querySelectorAll(".resetable").forEach(select => select.value = "");
    }

    function submitTambah(obj) {

        let valid = true; // Status validasi

        // Iterasi untuk mengecek produk dan quantity
        $(obj).closest('form').find('.det-produk').each(function (index) {
            const quantityInput = $(this).find('.stok-tambah-produk'); // Mendapatkan input quantity berdasarkan index
            var stok = $(this).find('.produk-tambah-produk').find('option:selected').data('stok');
            const quantity = parseInt(quantityInput.val());

            // Jika quantity lebih besar dari stok
            if (quantity > parseInt(stok)) {
                const errorMessage = $('<span></span>').addClass('text-danger').text(`Stok tidak mencukupi. Maksimal: ${stok}`);
                quantityInput.parent().append(errorMessage); // Menambahkan pesan error setelah input quantity
                $('.text-error-stok').html(`Stok tidak mencukupi. Maksimal: ${stok}`);
                valid = false; // Validasi gagal
            }

            var jmlp = $('.produk-tambah-produk').length;

            console.log('nama: ' + stok + ' - jmlp: ' + jmlp);   

        });


    }

    function cekDuplikatProduk(obj) {
    var selectedValue = $(obj).val(); // Nilai yang dipilih di select saat ini
    var prevValue = $(obj).data('prev-value'); //Nilai sebelumnya
    var allValues = []; // Array untuk menyimpan semua nilai yang dipilih

    // Iterasi semua select untuk mengecek jika ada nilai yang sama
    $('.produk-tambah-produk').not(obj).each(function() {
        if ($(this).val() !== "") {
            allValues.push($(this).val());
        }
    });

    console.log('Selected Value: ' + selectedValue);
    console.log('All Values: ' + allValues);

    // Jika ada duplikat (nilai sudah ada di array allValues)
    if ($.inArray(selectedValue, allValues) !== -1) {
        console.log('Produk: ' + selectedValue + ' sudah dipilih di detail lainnya');
        $(obj).val(prevValue); // Ubah nilai ke nilai sebelumnya jika ada duplikat
        alert('Produk yang sama sudah dipilih di detail lainnya');
    } else {
        $(obj).data('prev-value', selectedValue);
    }
}

    
    </script>    
    
    </div>
