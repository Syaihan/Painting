<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat / ambil permissions
        $permissions = [
            'access-scan-console'       => 'Akses Konsol Scan',
            'access-stock-card'         => 'Akses Kartu Stok',
            'access-stock-in'           => 'Akses Stok Masuk',
            'access-ng-log'             => 'Akses Log NG Sealing',
            'access-stock-adjustment'   => 'Akses Penyesuaian Stok',
            'access-master-part'        => 'Akses Master Part',
            'access-manajemen-user'     => 'Akses Manajemen User',
        ];

        $permissionIds = [];
        foreach ($permissions as $name => $description) {
            $existing = DB::table('m_permissions')->where('name', $name)->value('id');
            if ($existing) {
                $permissionIds[$name] = $existing;
            } else {
                $id = DB::table('m_permissions')->insertGetId([
                    'name' => $name,
                    'description' => $description,
                    'created_at' => now('Asia/Jakarta'),
                    'updated_at' => now('Asia/Jakarta'),
                ]);
                $permissionIds[$name] = $id;
            }
        }

        // 2. Buat / ambil role admin
        $adminRoleId = DB::table('m_roles')->where('name', 'admin')->value('id');
        if (!$adminRoleId) {
            $adminRoleId = DB::table('m_roles')->insertGetId([
                'name' => 'admin',
                'display_name' => 'Administrator',
                'created_at' => now('Asia/Jakarta'),
                'updated_at' => now('Asia/Jakarta'),
            ]);
        }

        // 3. Assign semua permissions ke role admin (hindari duplikat)
        foreach ($permissionIds as $permissionId) {
            $exists = DB::table('m_role_permission')
                ->where('role_id', $adminRoleId)
                ->where('permission_id', $permissionId)
                ->exists();

            if (!$exists) {
                DB::table('m_role_permission')->insert([
                    'role_id' => $adminRoleId,
                    'permission_id' => $permissionId,
                    'created_at' => now('Asia/Jakarta'),
                    'updated_at' => now('Asia/Jakarta'),
                ]);
            }
        }

        // 4. Buat / perbarui user admin
        $adminUser = DB::table('m_users')->where('username', 'admin')->first();
        $hashedPassword = Hash::make('admin123');

        if ($adminUser) {
            DB::table('m_users')
                ->where('id', $adminUser->id)
                ->update([
                    'name' => 'Administrator',
                    'password' => $hashedPassword,
                    'role_id' => $adminRoleId,
                    'updated_at' => now('Asia/Jakarta'),
                ]);
        } else {
            DB::table('m_users')->insert([
                'username' => 'admin',
                'name' => 'Administrator',
                'password' => $hashedPassword,
                'role_id' => $adminRoleId,
                'created_at' => now('Asia/Jakarta'),
                'updated_at' => now('Asia/Jakarta'),
            ]);
        }
    }
}
