<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')
            ->whereHas('role', function($query) {
                $query->where('role_name', '!=', 'admin');
            })
            ->orderBy('updated_at', 'desc')
            ->get();
        $roles = Role::where('role_name', '!=', 'admin')->get();

        // Konfigurasi SweetAlert untuk delete dengan warna danger
        $confirm = [
            'title' => 'Hapus User?',
            'text' => 'Apakah Anda yakin ingin menghapus user ini? Data yang dihapus tidak dapat dikembalikan.',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonColor' => '#dc3545',
            'cancelButtonColor' => '#6c757d',
            'confirmButtonText' => 'Ya, Hapus',
            'cancelButtonText' => 'Batal'
        ];

        session()->flash('alert.delete', json_encode($confirm, JSON_UNESCAPED_SLASHES));

        return view('users.index', compact('users', 'roles'));
    }

    public function updateRole(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'user_id' => 'required'
        ]);

        $userId = $request->user_id;
        $roleId = $request->role_id;

        $user = User::find($userId);
        $user->role_id = $roleId;
        $user->save();

        Alert::success('Berhasil', 'Role berhasil diubah');
        return redirect()->route('users.index');
    }

    public function create()
    {
        $roles = Role::where('role_name', '!=', 'admin')->get();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
        ]);

        $newRequest = $request->all();

        if (!$id) {
            $newRequest['password'] = Hash::make('12345678');
        }

        User::updateOrCreate(['id' => $id], $newRequest);
        toast('Data user berhasil disimpan!', 'success');
        return Redirect::route('users.index');
    }

    public function gantiPassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:8|confirmed',
            // 'password' => [Password::min(8)->
            //     mixedCase()->
            //     numbers()->
            //     symbols(), 'confirmed'],
        ], [
            'old_password.required' => 'Password saat ini wajib diisi',
            'password.required' => 'Password baru wajib diisi',
            'password.min' => 'Password baru minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',
        ]);

        $user = User::find(Auth::id());

        if (!Hash::check($request->old_password, $user->password)) {
            toast('Password saat ini tidak sesuai.', 'error');
            return redirect()->route('home');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        toast('Password berhasil diubah!', 'success');
        return redirect()->route('home');
    }

    public function destroy(String $id)
    {
        $user = User::find($id);

        if (Auth::id() == $id) {
            toast('Anda tidak dapat menghapus akun yang sedang login', 'error');
            return Redirect::route('users.index');
        }

        $user->delete();
        toast('Data user berhasil dihapus.', 'success');
        return Redirect::route('users.index');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $user = User::find($request->id);
        $user->update([
            'password' => Hash::make('12345678'),
        ]);

        toast('Password berhasil direset!', 'success');
        return redirect()->route('users.index');
    }
}
