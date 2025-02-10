<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User as ModelUser;

class User extends Component
{
    public $pilihanMenu = 'lihat';
    public $nama, $email, $peran, $password;
    public $penggunaTerpilih;

    public function pilihHapus($id) {

        $this->penggunaTerpilih = ModelUser::findOrFail($id);
        $this->pilihanMenu = 'hapus';
    }

    public function hapus() {
        $this->penggunaTerpilih->delete();
        $this->reset();
    }

    public function batal() {
        
        
        $this->reset();
    }

    public function simpanUbah() {

        $this->validate([
            'nama' => 'required',
            'email' => ['required', 'email', 'unique:users,email,' . $this->penggunaTerpilih->id],
            'peran' => 'required',
        ],
        [
            'nama.required' => 'Nama harus diisi',
            'email.required' => 'E-mail harus diisi',
            'email.email' => 'Format e-mail tidak sesuai',
            'email.unique' => 'E-mail sudah digunakan',
            'peran.required' => 'Peran harus diisi',
        ]);

        $simpan = $this->penggunaTerpilih;
        $simpan->name = $this->nama;
        $simpan->email = $this->email;
        if($this->password) {
            $simpan->password = bcrypt($this->password);
        }
        $simpan->peran = $this->peran;
        $simpan->save();

        $this->reset(['nama', 'email', 'password', 'peran', 'penggunaTerpilih']);
        $this->pilihanMenu = 'lihat';
    }

    public function pilihUbah($id) {

        $this->penggunaTerpilih = ModelUser::findOrFail($id);

        $this->nama = $this->penggunaTerpilih->name;
        $this->email = $this->penggunaTerpilih->email;
        $this->peran = $this->penggunaTerpilih->peran;
        $this->pilihanMenu = 'ubah';
    }

    public function simpan() {

        $this->validate([
            'nama' => 'required',
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => 'required',
            'peran' => 'required',
        ],
        [
            'nama.required' => 'Nama harus diisi',
            'email.required' => 'E-mail harus diisi',
            'email.email' => 'Format e-mail tidak sesuai',
            'email.unique' => 'E-mail sudah digunakan',
            'password.required' => 'Password harus diisi',
            'peran.required' => 'Peran harus diisi',
        ]);

        $simpan = new ModelUser();
        $simpan->name = $this->nama;
        $simpan->email = $this->email;
        $simpan->password = bcrypt($this->password);
        $simpan->peran = $this->peran;
        $simpan->save();

        $this->reset(['nama', 'email', 'password', 'peran']);
        $this->pilihanMenu = 'lihat';
    }

    public function pilihMenu($menu) {

        $this->pilihanMenu = $menu;

    }

    public function render()
    {
        return view('livewire.user')->with([
            'semuaPengguna' => ModelUser::all()
        ]);
    }
}
