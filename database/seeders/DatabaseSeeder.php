<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\LeaveType;
use App\Models\OfficeLocation;
use App\Models\User;
use App\Models\WorkShift;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin    = Role::firstOrCreate(['name' => 'admin']);
        $hr       = Role::firstOrCreate(['name' => 'hr']);
        $employee = Role::firstOrCreate(['name' => 'employee']);

        $permissions = [
            'view-users', 'create-users', 'edit-users', 'delete-users',
            'view-reports', 'export-reports',
            'manage-shifts', 'manage-locations',
            'approve-leave',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $admin->syncPermissions($permissions);
        $hr->syncPermissions([
            'view-users', 'view-reports', 'export-reports', 'approve-leave',
        ]);

        $depts = [
            ['name' => 'Technology',       'code' => 'TECH'],
            ['name' => 'Human Resources',  'code' => 'HR'],
            ['name' => 'Finance',          'code' => 'FIN'],
            ['name' => 'Operations',       'code' => 'OPS'],
        ];
        foreach ($depts as $dept) {
            Department::firstOrCreate(['code' => $dept['code']], $dept);
        }

        OfficeLocation::firstOrCreate(
            ['name' => 'Head Office'],
            [
                'address'       => 'Jl. Sudirman No. 1, Jakarta Pusat',
                'latitude'      => -6.208763,
                'longitude'     => 106.845599,
                'radius_meters' => 100,
                'is_active'     => true,
            ]
        );

        WorkShift::firstOrCreate(
            ['name' => 'Morning Shift'],
            [
                'start_time'                       => '08:00:00',
                'end_time'                         => '17:00:00',
                'crosses_midnight'                 => false,
                'late_tolerance_minutes'           => 15,
                'early_checkout_tolerance_minutes' => 15,
                'working_days'                     => [1, 2, 3, 4, 5],
                'is_active'                        => true,
            ]
        );

        WorkShift::firstOrCreate(
            ['name' => 'Night Shift'],
            [
                'start_time'                       => '22:00:00',
                'end_time'                         => '06:00:00',
                'crosses_midnight'                 => true,
                'late_tolerance_minutes'           => 15,
                'early_checkout_tolerance_minutes' => 15,
                'working_days'                     => [1, 2, 3, 4, 5],
                'is_active'                        => true,
            ]
        );

        $leaveTypes = [
            ['name' => 'Annual Leave',  'code' => 'AL',  'max_days_per_year' => 12, 'requires_attachment' => false],
            ['name' => 'Sick Leave',    'code' => 'SL',  'max_days_per_year' => 14, 'requires_attachment' => true],
            ['name' => 'Maternity',     'code' => 'ML',  'max_days_per_year' => 90, 'requires_attachment' => true],
            ['name' => 'Paternity',     'code' => 'PL',  'max_days_per_year' => 3,  'requires_attachment' => false],
            ['name' => 'Emergency',     'code' => 'EL',  'max_days_per_year' => 5,  'requires_attachment' => false],
        ];
        foreach ($leaveTypes as $lt) {
            LeaveType::firstOrCreate(['code' => $lt['code']], $lt + ['is_active' => true]);
        }

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@company.com'],
            [
                'name'        => 'System Admin',
                'password'    => Hash::make('password'),
                'employee_id' => 'EMP001',
                'is_active'   => true,
            ]
        );
        $adminUser->assignRole('admin');

        $hrUser = User::firstOrCreate(
            ['email' => 'hr@company.com'],
            [
                'name'        => 'HR Manager',
                'password'    => Hash::make('password'),
                'employee_id' => 'EMP002',
                'is_active'   => true,
            ]
        );
        $hrUser->assignRole('hr');

        $this->command->info('✅ Seeder completed. Credentials:');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin', 'admin@company.com', 'password'],
                ['HR',    'hr@company.com',    'password'],
            ]
        );
    }
}