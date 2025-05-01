<?php

namespace App\Http\Controllers;
// use Illuminate\Http\Request;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
 
    public function index(Request $request)
    {
        // Ambil data users dengan relasi roles
        $users = User::with('roles')
            ->when($request->input('name'), function ($query, $name) {
                return $query->where('name', 'like', '%' . $name . '%')
                             ->orWhere('email', 'like', '%' . $name . '%');
            })
            ->orderByRaw("
                CASE 
                    WHEN EXISTS (SELECT 1 FROM model_has_roles WHERE model_has_roles.model_id = users.id AND model_has_roles.role_id = (SELECT id FROM roles WHERE name = 'admin')) THEN 1
                    ELSE 2 
                END, created_at DESC
            ") // Prioritaskan pemilik_toko dan urutkan dari yang terbaru
            ->paginate(5);
    
        return view('pages.users.index', compact('users'));
    }
    
  //create
  public function create()
  {
      // Ambil semua role untuk dropdown
      $roles = Role::all();
      return view('pages.users.create', compact('roles'));
  }

  public function store(Request $request)
  {
      $request->validate([
          'name' => 'required',
          'email' => 'required|email|unique:users',
          'password' => 'nullable|min:8',
          'role' => 'required|in:admin,karyawan',
      ]);

       // Create user
       $user = User::create([
          'name' => $request->name,
          'email' => $request->email,
          'password' => Hash::make($request->password),
          'role' => $request->role,
      ]);
       // Assign role
       $user->assignRole($request->role);

       return redirect()->route('user.index')->with('success', 'User created successfully.');
  }

 //show
 public function show($id)
 {
     return view ('pages.dashboard');

 }

 //edit
 public function edit($id)
 {
     $user = User::findOrFail($id);
     return view('pages.users.edit', compact('user'));
 }

 //update
 public function update(Request $request, $id)
 {
     $data = $request->all();
     $user = User::findOrFail($id);
 
     // Check if password is not empty
     if ($request->filled('password')) {
         $data['password'] = Hash::make($request->input('password'));
     } else {
         $data['password'] = $user->password;
     }
 
     // Update data user
     $user->update($data);
 
     // Hapus semua role lama berdasarkan ID user yang dipilih
     $user->roles()->detach();
 
     // Assign role baru
     if ($request->has('role')) {
         $user->assignRole($request->role);
     }
 
     return redirect()->route('user.index')->with('success', 'User updated successfully');
 }
 

   //destroy
 public function destroy($id)
 {
     $user = User::findOrFail($id);
     $user->delete();
     return redirect()->route('user.index')->with('success', 'User deleted successfully');
}


public function profil($id)
    {
        $user = User::findOrFail($id);

        return view('pages.users.profile', compact('user'));
    }

    public function updateProfile(Request $request, $id)
    {
        // Validasi input data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required',
        ]);

        // Cari user berdasarkan ID
        $user = User::findOrFail($id);

        // Perbarui data jika ada perubahan
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        // Jika password diisi, hash dan update password
        if ($request->filled('password')) { // Mengecek apakah password diisi
            $user->password = Hash::make($request->input('password'));
        }

        // Simpan perubahan ke database
        $user->save();

        // Redirect ke profil dengan pesan sukses
        return redirect()->route('user.profil', $id)->with('success', 'Profile updated successfully');
   }
   
}