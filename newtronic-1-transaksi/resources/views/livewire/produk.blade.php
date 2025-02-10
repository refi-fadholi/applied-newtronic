<div>
    <div class="container">
        <div class="row my-2">
            <div class="col-12">
                <button wire:click="pilihMenu('lihat')" class="btn {{ $pilihanMenu == 'lihat' ? 'btn-primary' : 'btn-outline-primary' }}" hidden>Daftar Produk</button>
                <button wire:loading class="btn btn-info">Loading ...</button>
            </div>
        </div>
        <div class="row">
            <div class="col-12">

                @if ($pilihanMenu == 'lihat')
                <div class="card border-primary">
                    <div class="card-header">Daftar Produk</div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th>Nama Produk</th>
                                    <th>Stok</th>
                                    <th>Harga</th>
                                    <th class="text-center">Ubah</th>
                                    <th class="text-center">Hapus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($semuaProduk as $produk)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $produk->produk }}</td>
                                    <td>{{ $produk->stok }}</td>
                                    <td>{{ "Rp. " . number_format($produk->harga, 0, ',', '.');  }}</td>
                                    <td class="text-center">
                                        <button wire:click="pilihUbah({{ $produk->id }})" class="btn {{ $pilihanMenu == 'edit' ? 'btn-primary' : 'btn-outline-primary' }}"><i class="fa-solid fa-edit"></i></button>
                                    </td>
                                    <td class="text-center">
                                        <button wire:click="pilihHapus({{ $produk->id }})" class="btn {{ $pilihanMenu == 'hapus' ? 'btn-danger' : 'btn-outline-danger' }}"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                                    
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row my-2">
                            <div class="col-12">
                                <button onclick="kosongkanInput();" wire:click="pilihMenu('tambah')" class="float-end btn btn-success"><b>&plus;</b> Tambah Produk</button>
                            </div>
                        </div>
                    </div>
                </div>
                @elseif ($pilihanMenu == 'tambah')
                <div class="card border-primary">
                    <div class="card-header">Tambah Produk Baru</div>
                    <div class="card-body">
                    <form wire:submit='simpan'>
                        
                        <div class="row mb-3">
                            <label for="produk" class="col-sm-3 col-form-label">Nama Produk</label>
                            <div class="col-sm-6">
                                <input type="text" id="produk" class="form-control form-control-sm" wire:model='produk'/>
                                @error('produk')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="stok" class="col-sm-3 col-form-label">Stok</label>
                            <div class="col-sm-6">
                                <input type="number" id="stok" class="form-control form-control-sm" wire:model='stok'/>
                                @error('stok')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="harga" class="col-sm-3 col-form-label">Harga</label>
                            <div class="col-sm-6">
                                <input type="number" id="harga" class="form-control form-control-sm" wire:model='harga'/>
                                @error('harga')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success mt-3">Simpan</button>
                        <button type="button" class="btn btn-secondary mt-3" wire:click="batal">Batal</button>
                    </form>
                    </div>
                </div>
                @elseif ($pilihanMenu == 'ubah')
                <div class="card border-primary">
                    <div class="card-header">Ubah Produk</div>
                    <div class="card-body">
                        <form wire:submit='simpanUbah'>
                        
                            <div class="row mb-3">
                                <label for="produk" class="col-sm-3 col-form-label">Nama Produk</label>
                                <div class="col-sm-6">
                                    <input type="text" id="produk" class="form-control form-control-sm" wire:model='produk'/>
                                    @error('produk')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="row mb-3">
                                <label for="stok" class="col-sm-3 col-form-label">Stok</label>
                                <div class="col-sm-6">
                                    <input type="number" id="stok" class="form-control form-control-sm" wire:model='stok'/>
                                    @error('stok')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="row mb-3">
                                <label for="harga" class="col-sm-3 col-form-label">Harga</label>
                                <div class="col-sm-6">
                                    <input type="number" id="harga" class="form-control form-control-sm" wire:model='harga'/>
                                    @error('harga')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
    
                            <button type="submit" class="btn btn-success mt-3">Simpan</button>
                            <button type="button" class="btn btn-secondary mt-3" wire:click="batal">Batal</button>
                        </form>
                    </div>
                </div>
                @elseif ($pilihanMenu == 'hapus') 
                <div class="card border-danger">
                    <div class="card-header  bg-danger text-white">Hapus Produk</div>
                    <div class="card-body">
                        Apakah anda yakin akan menghapus produk <b>{{ $produkTerpilih->produk }}</b>?<br><br>
                        <button class="btn btn-danger" wire:click="hapus">Hapus</button>
                        <button class="btn btn-secondary" wire:click="batal">Batal</button>
                    </div>
                </div> 
                  
                @endif

            </div>
        </div>

        
    </div>

</div>
