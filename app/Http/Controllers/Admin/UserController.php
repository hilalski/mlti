<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Team;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['room']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip_lama', 'like', "%{$search}%")
                  ->orWhere('nip_baru', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('room_id')) {
            $query->where('id_ruang', $request->room_id);
        }

        $users = $query->paginate(10)->withQueryString();
        $rooms = Room::orderBy('ruang')->get();

        return view('admin.users.index', compact('users', 'rooms'));
    }

    public function create()
    {
        $teams = Team::all();
        $rooms = Room::all();
        return view('admin.users.create', compact('teams', 'rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip_lama' => 'required|numeric|unique:users,nip_lama',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'nip_baru' => 'nullable|string|max:50',
            'fungsi' => 'required|exists:teams,id',
            'jabatan' => 'nullable|string|max:255',
            'id_ruang' => 'nullable|exists:rooms,id',
            'is_jarkom' => 'nullable|in:0,1',
            'password' => 'nullable|string|min:6',
        ]);

        $password = $request->filled('password') 
            ? Hash::make($request->password) 
            : Hash::make('password'); // Default password

        User::create([
            'nip_lama' => $request->nip_lama,
            'name' => $request->name,
            'email' => $request->email,
            'nip_baru' => $request->nip_baru,
            'fungsi' => $request->fungsi,
            'jabatan' => $request->jabatan,
            'id_ruang' => $request->id_ruang ?: null,
            'is_jarkom' => $request->is_jarkom ?? 0,
            'password' => $password,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Akun pengguna berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $teams = Team::all();
        $rooms = Room::all();
        return view('admin.users.edit', compact('user', 'teams', 'rooms'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nip_lama' => [
                'required',
                'numeric',
                Rule::unique('users', 'nip_lama')->ignore($user->nip_lama, 'nip_lama')
            ],
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'nip_baru' => 'nullable|string|max:50',
            'fungsi' => 'required|exists:teams,id',
            'jabatan' => 'nullable|string|max:255',
            'id_ruang' => 'nullable|exists:rooms,id',
            'is_jarkom' => 'nullable|in:0,1',
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'nip_lama' => $request->nip_lama,
            'name' => $request->name,
            'email' => $request->email,
            'nip_baru' => $request->nip_baru,
            'fungsi' => $request->fungsi,
            'jabatan' => $request->jabatan,
            'id_ruang' => $request->id_ruang ?: null,
            'is_jarkom' => $request->is_jarkom ?? 0,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Akun pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting oneself
        if ($user->nip_lama === auth()->user()->nip_lama) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Akun pengguna berhasil dihapus.');
    }
}
