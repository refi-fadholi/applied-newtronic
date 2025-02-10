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

                @if ($pilihanMenu == 'lihat')
                <div class="card border-primary">
                    <div class="card-header">Daftar Pengguna</div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Peran</th>
                                    <th class="text-center">Ubah</th>
                                    <th class="text-center">Hapus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($semuaPengguna as $pengguna)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $pengguna->name }}</td>
                                    <td>{{ $pengguna->email }}</td>
                                    <td>{{ $pengguna->peran }}</td>
                                    <td class="text-center">
                                        <button wire:click="pilihUbah({{ $pengguna->id }})" class="btn {{ $pilihanMenu == 'edit' ? 'btn-primary' : 'btn-outline-primary' }}"><i class="fa-solid fa-edit"></i></button>
                                    </td>
                                    <td class="text-center">
                                        <button wire:click="pilihHapus({{ $pengguna->id }})" class="btn {{ $pilihanMenu == 'hapus' ? 'btn-danger' : 'btn-outline-danger' }}"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                                    
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row my-2">
                            <div class="col-12">
                                <button onclick="kosongkanInput();" wire:click="pilihMenu('tambah')" class="float-end btn btn-success"><b>&plus;</b> Tambah Pengguna</button>
                            </div>
                        </div>
                    </div>
                </div>
                @elseif ($pilihanMenu == 'tambah')
                <div class="card border-primary">
                    <div class="card-header">Tambah Pengguna Baru</div>
                    <div class="card-body">
                    <form wire:submit='simpan'>
                        
                        <div class="row mb-3">
                            <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                            <div class="col-sm-6">
                                <input type="text" id="nama" class="form-control form-control-sm resetable" wire:model='nama'/>
                                @error('nama')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-sm-3 col-form-label">E-mail</label>
                            <div class="col-sm-6">
                                <input type="text" id="email" class="form-control form-control-sm resetable" wire:model='email'/>
                                @error('email')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-sm-3 col-form-label">Password</label>
                            <div class="col-sm-6">
                                <input type="password" id="password" class="form-control form-control-sm resetable" wire:model='password'/>
                                @error('password')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="peran" class="col-sm-3 col-form-label">Peran</label>
                            <div class="col-sm-6">
                                <select name="" id="" class="form-control resetable" wire:model='peran'>
                                    <option value="">-- Pilih Peran --</option>
                                    <option value="kasir">Kasir</option>
                                    <option value="admin">Admin</option>
                                </select>                                @error('peran')
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
                    <div class="card-header">Ubah Pengguna</div>
                    <div class="card-body">
                        <form wire:submit='simpanUbah'>
                        
                            <div class="row mb-3">
                                <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                                <div class="col-sm-6">
                                    <input type="text" id="nama" class="form-control form-control-sm" wire:model='nama'/>
                                    @error('nama')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="row mb-3">
                                <label for="email" class="col-sm-3 col-form-label">E-mail</label>
                                <div class="col-sm-6">
                                    <input type="text" id="email" class="form-control form-control-sm" wire:model='email'/>
                                    @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="row mb-3">
                                <label for="password" class="col-sm-3 col-form-label">Password</label>
                                <div class="col-sm-6">
                                    <input type="password" id="password" class="form-control form-control-sm" wire:model='password'/>
                                    @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="row mb-3">
                                <label for="peran" class="col-sm-3 col-form-label">Peran</label>
                                <div class="col-sm-6">
                                    <select name="" id="" class="form-control" wire:model='peran'>
                                        <option value="">-- Pilih Peran --</option>
                                        <option value="kasir">Kasir</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                    @error('peran')
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
                    <div class="card-header  bg-danger text-white">Hapus Pengguna</div>
                    <div class="card-body">
                        Apakah anda yakin akan menghapus pengguna yang bernama <b>{{ $penggunaTerpilih->name }}</b>?<br><br>
                        <button class="btn btn-danger" wire:click="hapus">Hapus</button>
                        <button class="btn btn-secondary" wire:click="batal">Batal</button>
                    </div>
                </div> 
                  
                @endif

            </div>
        </div>

        
    </div>

</div>

<script>

function kosongkanInput() {
        document.querySelectorAll(".resetable").forEach(input => input.value = "");
        document.querySelectorAll(".resetable").forEach(select => select.value = "");
}
</script>
