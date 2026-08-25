<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles & permissions so Spatie picks up fresh data
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ── 1. CREATE ALL PERMISSIONS ──────────────────────────────────
        $permissions = [

            // Dashboard
            'dashboard.view',

            // Posts
            'posts.view',
            'posts.create',
            'posts.edit',
            'posts.delete',
            'posts.publish',
            'posts.feature',
            'posts.media.upload',
            'posts.media.delete',

            // Categories
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',

            // Subcategories
            'subcategories.view',
            'subcategories.create',
            'subcategories.edit',
            'subcategories.delete',

            // Localities
            'localities.view',
            'localities.create',
            'localities.edit',
            'localities.delete',

            // Ads
            'ads.view',
            'ads.create',
            'ads.edit',
            'ads.delete',

            // Users
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            // Roles & Permissions
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'permissions.manage',

            // Settings / System
            'settings.view',
            'settings.edit',

            // Reports / Analytics
            'reports.view',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // ── 2. CREATE ROLES & ASSIGN PERMISSIONS ───────────────────────

        /* ─── Super Admin ─────────────────────────────────────────────
         *  Has every permission. Created via Gate::before() in
         *  AuthServiceProvider, OR just grant all here.
         */
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        /* ─── Admin ───────────────────────────────────────────────────
         *  Full CRUD on all content + users. Cannot manage roles/settings.
         */
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions([
            'dashboard.view',

            'posts.view', 'posts.create', 'posts.edit', 'posts.delete',
            'posts.publish', 'posts.feature',
            'posts.media.upload', 'posts.media.delete',

            'categories.view', 'categories.create', 'categories.edit', 'categories.delete',

            'subcategories.view', 'subcategories.create', 'subcategories.edit', 'subcategories.delete',

            'localities.view', 'localities.create', 'localities.edit', 'localities.delete',

            'ads.view', 'ads.create', 'ads.edit', 'ads.delete',

            'users.view', 'users.create', 'users.edit', 'users.delete',

            'roles.view',

            'reports.view',
        ]);

        /* ─── Editor ──────────────────────────────────────────────────
         *  Can create, edit and publish posts + upload media.
         *  Cannot delete posts or manage users / taxonomy.
         */
        $editor = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $editor->syncPermissions([
            'dashboard.view',

            'posts.view', 'posts.create', 'posts.edit', 'posts.publish', 'posts.feature',
            'posts.media.upload', 'posts.media.delete',

            'categories.view',
            'subcategories.view',
            'localities.view',
        ]);

        /* ─── Author ──────────────────────────────────────────────────
         *  Can create and edit their own posts. Cannot publish or delete.
         */
        $author = Role::firstOrCreate(['name' => 'author', 'guard_name' => 'web']);
        $author->syncPermissions([
            'dashboard.view',

            'posts.view', 'posts.create', 'posts.edit',
            'posts.media.upload',

            'categories.view',
            'subcategories.view',
            'localities.view',
        ]);

        /* ─── Viewer ──────────────────────────────────────────────────
         *  Read-only across the admin panel. No create/edit/delete.
         */
        $viewer = Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'web']);
        $viewer->syncPermissions([
            'dashboard.view',
            'posts.view',
            'categories.view',
            'subcategories.view',
            'localities.view',
            'users.view',
            'reports.view',
        ]);

        /* ─── User (frontend only) ────────────────────────────────────
         *  Regular public user — no admin panel access.
         */
        Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        // No permissions assigned — cannot access any admin route.

        // ── 3. CREATE DEFAULT SUPER-ADMIN USER (if not exists) ─────────
        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@dealshood.com'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('Admin@1234'),  // change after first login
                'status'   => 'Active',
            ]
        );
        $superAdminUser->assignRole('super-admin');

        // ── 4. CREATE DEFAULT ADMIN USER ───────────────────────────────
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@dealshood.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('Admin@1234'),
                'status'   => 'Active',
            ]
        );
        $adminUser->assignRole('admin');

        $this->command->info('✅  Roles, permissions and default users seeded successfully.');
        $this->command->table(
            ['Role', 'Permissions'],
            Role::with('permissions')->get()->map(fn($r) => [
                $r->name,
                $r->permissions->pluck('name')->implode(', ') ?: '—',
            ])->toArray()
        );
    }
}
