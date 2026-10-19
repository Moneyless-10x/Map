<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\Regency;
use App\Models\District; 
use App\Models\Village;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * List all users
     */
    public function index()
    {
        $users = User::with(['role', 'province', 'regency', 'district', 'village'])->paginate(5);
        $roles = Role::all(); 
        $provinces = Province::orderBy('name')->get();
        
        return view('pages.user', compact('users', 'roles', 'provinces'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username'    => 'required|string|max:100|unique:users,username',   
            'email'       => 'nullable|email|unique:users,email', 
            'password'    => 'required|string|min:6',
            'role_id'     => 'required|exists:roles,role_id',
            'is_active'   => 'sometimes|boolean',
            'phone'       => 'nullable|string|max:20',
            'birth_date'  => 'nullable|date|before_or_equal:today',
            'address'     => 'nullable|string|max:1000',
            'province_id' => 'nullable|exists:provinces,id',
            'regency_id'  => 'nullable|exists:regencies,id', 
            'district_id' => 'nullable|exists:districts,id',
            'village_id'  => 'nullable|exists:villages,id',
        ]);

        $validated['password_hash'] = Hash::make($request->password);
        $validated['is_active'] = $request->input('is_active', true);
        unset($validated['password']);

        User::create($validated);

        return redirect()->route('user')->with('success', 'User berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username'    => 'required|string|max:100|unique:users,username,' . $id . ',user_id', 
            'email'       => 'nullable|email|unique:users,email,' . $id . ',user_id', 
            'role_id'     => 'required|exists:roles,role_id',
            'is_active'   => 'sometimes|boolean',
            'phone'       => 'nullable|string|max:20',                    
            'birth_date'  => 'nullable|date|before_or_equal:today',        
            'address'     => 'nullable|string|max:1000',
            'province_id' => 'nullable|exists:provinces,id',
            'regency_id'  => 'nullable|exists:regencies,id',
            'district_id' => 'nullable|exists:districts,id', 
            'village_id'  => 'nullable|exists:villages,id',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->filled('password')) {
            $validated['password_hash'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->back()->with('success', 'User berhasil diperbarui!');
    }

    public function getRegencies($provinceId)
    {
        Validator::make(['province_id' => $provinceId], [
            'province_id' => 'required|exists:provinces,id'
        ])->validate();

        $regencies = Regency::where('province_id', $provinceId)
            ->orderBy('name', 'asc')
            ->select('id', 'name', 'province_id')
            ->get();

        return response()->json($regencies);
    }

    public function getDistricts($regencyId)
    {
        Validator::make(['regency_id' => $regencyId], [
            'regency_id' => 'required|exists:regencies,id'
        ])->validate();

        $districts = District::where('regency_id', $regencyId)
            ->orderBy('name', 'asc')
            ->select('id', 'name', 'regency_id')
            ->get();

        return response()->json($districts);
    }

    public function getVillages($districtId)
    {
        Validator::make(['district_id' => $districtId], [
            'district_id' => 'required|exists:districts,id'
        ])->validate();

        $villages = Village::where('district_id', $districtId)
            ->orderBy('name', 'asc') 
            ->select('id', 'name', 'district_id')
            ->get();

        return response()->json($villages);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('user')->with('success', 'User berhasil dihapus!');
    }

    public function search(Request $request)
    {
        $query = User::with(['role', 'province', 'regency', 'district', 'village']);

        // Search text filter
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $role = $request->role;
            $query->whereHas('role', function ($q) use ($role) {
                if (is_numeric($role)) {
                    $q->where('role_id', $role);
                } else {
                    $q->whereRaw('LOWER(role_name) = ?', [strtolower($role)]);
                }
            });
        }

        // Status filter (Added handling for status parameter)
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'active' || $status === '1') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive' || $status === '0') {
                $query->where('is_active', false);
            }
        }

        $users = $query->paginate(5);
        $currentMenuId = $request->input('menu_id', 1);

        return response()->json([
            'items' => $users->map(function ($user, $index) use ($users, $currentMenuId) {
                $alamatWilayah = collect([
                    optional($user->village)->name,
                    optional($user->district)->name,
                    optional($user->regency)->name,
                    optional($user->province)->name,
                ])->filter()->implode(', ');
                
                $alamatDisplay = $alamatWilayah 
                    ? ($user->address ? "{$alamatWilayah} - {$user->address}" : $alamatWilayah)
                    : ($user->address ?? '-');

                return [
                    'number'     => $users->firstItem() + $index,
                    'user'       => [
                        'username' => $user->username ?? '-',
                        'email'    => $user->email ?? '-'
                    ],
                    'phone'      => $user->phone ?? '-',
                    'date_birth' => $user->birth_date ? Carbon::parse($user->birth_date)->format('d M Y') : '-',
                    'alamat'     => $alamatDisplay,
                    'role'       => $user->role->role_name ?? 'No Role',
                    'status'     => $user->is_active ? 'Active' : 'Inactive',
                    'actions'    => $this->getUserActions($user, $currentMenuId)
                ];
            })->toArray(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page'    => $users->lastPage(),
                'from'         => $users->firstItem(),
                'to'           => $users->lastItem(),
                'total'        => $users->total()
            ]
        ]);
    }

    private function getUserActions($user, $currentMenuId = 1)
    {
        $authUser = auth()->user();
        $isSuperAdmin = $authUser && $authUser->role && (strtolower($authUser->role->role_name) === 'superadmin');
        $isTargetSuperAdmin = $user->role && (strtolower($user->role->role_name) === 'superadmin');
        
        $canEdit = $authUser && $authUser->canAccess($currentMenuId, 'edit') && (!$isTargetSuperAdmin || $isSuperAdmin);
        $canDelete = $authUser && $authUser->canAccess($currentMenuId, 'delete') && (!$isTargetSuperAdmin || $isSuperAdmin);

        $actions = [];

        if ($canEdit) {
            $actions[] = [
                'type' => 'edit', 
                'onclick' => "openEditModal(" . implode(', ', [
                    "'{$user->user_id}'",
                    "'" . addslashes($user->username ?? '') . "'",
                    "'" . addslashes($user->email ?? '') . "'",
                    "'{$user->role_id}'",
                    $user->is_active ? 'true' : 'false',
                    "'" . addslashes($user->phone ?? '') . "'",
                    "'" . addslashes($user->birth_date ?? '') . "'",
                    "`" . addslashes($user->address ?? '') . "`",
                    "'{$user->province_id}'",
                    "'{$user->regency_id}'",
                    "'{$user->district_id}'",
                    "'{$user->village_id}'"
                ]) . ")",
                'title' => 'Edit User'
            ];
        }

        if ($canDelete) {
            $csrfToken = csrf_token();
            $deleteRoute = route('users.destroy', $user->user_id);
            
            $actions[] = [
                'type' => 'delete',
                'onclick' => "deleteUser('{$user->user_id}', '{$deleteRoute}', '{$csrfToken}')",
                'title' => 'Delete User'
            ];
        }

        return $actions;
    }
}