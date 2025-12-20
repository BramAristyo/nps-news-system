<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    public function getAllUsers(): Collection
    {
        return User::all();
    }

    public function getPaginated(int $perPage = 10, ?string $search = null)
    {
        $query = User::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        return $query->latest()->paginate($perPage);
    }

    public function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make('password'), 
            'role' => $data['role'] ?? 'user',
            'is_internal' => $data['is_internal'] ?? false,
        ]);
    }

    public function updateUser(int $id, array $data): User
    {
        $user = User::findOrFail($id);
        
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);
        return $user;
    }

    public function deleteUser(int $id): bool
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }

    public function assignRole(int $id, string $role): User
    {
        $user = User::findOrFail($id);
        $user->update(['role' => $role]);
        return $user;
    }

    public function verifyInternalUser(int $id): User
    {
        $user = User::findOrFail($id);
        $user->update(['is_internal' => true]);
        return $user;
    }

    public function unverifyInternalUser(int $id): User
    {
        $user = User::findOrFail($id);
        $user->update(['is_internal' => false]);
        return $user;
    }
}
