<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $pengguna = User::with('roles')
            ->when($request->filled('cari'), function ($query) use ($request) {
                $cari = $request->cari;

                $query->where(function ($q) use ($cari) {
                    $q->where('name', 'like', "%{$cari}%")
                        ->orWhere('email', 'like', "%{$cari}%");
                });
            })
            ->when($request->filled('role'), function ($query) use ($request) {
                $query->role($request->role);
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('users.index', [
            'pengguna' => $pengguna,
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('users.create', [
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', Rule::exists('roles', 'name')],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'no_telepon' => $validated['no_telepon'] ?? null,
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        $user->syncRoles([$validated['role']]);

        return redirect()
            ->route('users.index')
            ->with('status', 'Akun pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('users.edit', [
            'pengguna' => $user->load('roles'),
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'role' => ['required', Rule::exists('roles', 'name')],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->no_telepon = $validated['no_telepon'] ?? null;

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $user->syncRoles([$validated['role']]);

        return redirect()
            ->route('users.index')
            ->with('status', 'Akun dan role pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($request->user()->is($user)) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Akun yang sedang login tidak dapat dihapus.');
        }

        $user->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()
            ->route('users.index')
            ->with('status', 'Akun pengguna berhasil dihapus.');
    }
}
