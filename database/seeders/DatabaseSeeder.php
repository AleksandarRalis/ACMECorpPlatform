<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\DonationDetail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create([
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Full system administrator with all permissions',
        ]);

        $employeeRole = Role::create([
            'name' => 'employee',
            'display_name' => 'Employee',
            'description' => 'Regular employee with basic permissions',
        ]);

        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@acmecorp.com',
            'password' => Hash::make('password'),
            'employee_id' => 'EMP001',
            'department' => 'IT',
            'position' => 'System Administrator',
            'phone' => '+1234567890',
            'bio' => 'System administrator for ACME Corp CSR Platform',
            'is_active' => true,
        ]);

        // Create sample employees
        $employees = [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@acmecorp.com',
                'employee_id' => 'EMP002',
                'department' => 'Marketing',
                'position' => 'Marketing Manager',
                'phone' => '+1234567891',
                'bio' => 'Passionate about social causes and community engagement',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@acmecorp.com',
                'employee_id' => 'EMP003',
                'department' => 'HR',
                'position' => 'HR Specialist',
                'phone' => '+1234567892',
                'bio' => 'Dedicated to employee well-being and corporate social responsibility',
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'mike.johnson@acmecorp.com',
                'employee_id' => 'EMP004',
                'department' => 'Finance',
                'position' => 'Financial Analyst',
                'phone' => '+1234567893',
                'bio' => 'Interested in sustainable finance and social impact investing',
            ],
        ];

        foreach ($employees as $employeeData) {
            User::create([
                ...$employeeData,
                'password' => Hash::make('password'),
                'is_active' => true,
            ]);
        }

        // Also insert into user_role pivot table for consistency
        $users = User::all();
        foreach ($users as $user) {
            DB::table('user_role')->insert([
                'user_id' => $user->id,
                'role_id' => $user['email'] === 'admin@acmecorp.com' ? $adminRole->id : $employeeRole->id,
                'assigned_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create sample campaigns
        $campaigns = [
            [
                'title' => 'Clean Water Initiative',
                'description' => 'Help provide clean drinking water to communities in need. This campaign aims to build water wells and install water purification systems in rural areas.',
                'goal_amount' => 50000.00,
                'current_amount' => 12500.00,
                'image_url' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=800',
                'status' => 'active',
                'start_date' => now()->subDays(30),
                'end_date' => now()->addDays(60),
                'created_by' => User::where('email', 'john.doe@acmecorp.com')->first()->id,
                'category' => 'environment',
            ],
            [
                'title' => 'Education for All',
                'description' => 'Support education programs for underprivileged children. We will provide school supplies, books, and educational resources to schools in developing regions.',
                'goal_amount' => 30000.00,
                'current_amount' => 8500.00,
                'image_url' => 'https://images.unsplash.com/photo-1523240794102-9ebd8deeb8f1?w=800',
                'status' => 'active',
                'start_date' => now()->subDays(15),
                'end_date' => now()->addDays(45),
                'created_by' => User::where('email', 'jane.smith@acmecorp.com')->first()->id,
                'category' => 'education',
            ],
            [
                'title' => 'Tree Planting Project',
                'description' => 'Join us in planting 10,000 trees to combat climate change and restore natural habitats. Each tree planted helps reduce carbon emissions and provides habitat for wildlife.',
                'goal_amount' => 25000.00,
                'current_amount' => 18200.00,
                'image_url' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=800',
                'status' => 'active',
                'start_date' => now()->subDays(45),
                'end_date' => now()->addDays(30),
                'created_by' => User::where('email', 'mike.johnson@acmecorp.com')->first()->id,
                'category' => 'environment',
            ],
        ];

        foreach ($campaigns as $campaignData) {
            Campaign::create($campaignData);
        }

        // Create sample donations
        $donations = [
            [
                'campaign_id' => 1, // Clean Water Initiative
                'donor_id' => User::where('email', 'jane.smith@acmecorp.com')->first()->id,
                'amount' => 500.00,
                'message' => 'Great initiative! Clean water is a basic human right.',
            ],
            [
                'campaign_id' => 1,
                'donor_id' => User::where('email', 'mike.johnson@acmecorp.com')->first()->id,
                'amount' => 1000.00,
                'message' => 'Happy to support this cause.',
            ],
            [
                'campaign_id' => 2, // Education for All
                'donor_id' => User::where('email', 'john.doe@acmecorp.com')->first()->id,
                'amount' => 750.00,
                'message' => 'Education is the key to a better future.',
            ],
            [
                'campaign_id' => 3, // Tree Planting Project
                'donor_id' => User::where('email', 'jane.smith@acmecorp.com')->first()->id,
                'amount' => 1200.00,
                'message' => 'Let\'s make our planet greener!',
            ],
        ];

        foreach ($donations as $donationData) {
            $donation = Donation::create($donationData);

            // Create donation details
            DonationDetail::create([
                'donation_id' => $donation->id,
                'payment_method' => 'dummy',
                'transaction_id' => 'TXN_' . uniqid(),
                'payment_status' => 'completed',
                'gateway_response' => json_encode(['status' => 'success']),
                'metadata' => [
                    'processor' => 'dummy',
                    'simulated' => true,
                ],
                'processed_at' => now(),
                'donor_id' => $donation->donor_id,
            ]);
        }

        // Output seeding information
        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin user: admin@acmecorp.com / password');
        $this->command->info('Employee users: john.doe@acmecorp.com, jane.smith@acmecorp.com, mike.johnson@acmecorp.com / password');
    }
}
