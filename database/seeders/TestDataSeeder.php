<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test users if they don't exist
        $users = [
            [
                'name' => 'Ahmed Al-Rashid',
                'phone' => '+966501234568',
                'email' => 'ahmed.test@example.com',
                'is_verified' => true,
                'status' => 'active',
                'language' => 'en',
                'last_login_at' => now()->subHours(2),
            ],
            [
                'name' => 'Sarah Al-Zahra',
                'phone' => '+966507654322',
                'email' => 'sarah.test@example.com',
                'is_verified' => true,
                'status' => 'active',
                'language' => 'en',
                'last_login_at' => now()->subDays(1),
            ],
            [
                'name' => 'Mohammed Al-Sayed',
                'phone' => '+966509876544',
                'email' => 'mohammed.test@example.com',
                'is_verified' => false,
                'status' => 'active',
                'language' => 'ar',
                'last_login_at' => now()->subDays(3),
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate([
                'email' => $userData['email']
            ], $userData);
        }

        // Create categories if they don't exist
        $categories = [
            [
                'name_ar' => 'الطعام والمطاعم',
                'name_en' => 'Food & Dining',
                'icon' => 'utensils',
                'color' => '#FF6B6B',
                'is_active' => true,
            ],
            [
                'name_ar' => 'المواصلات',
                'name_en' => 'Transportation',
                'icon' => 'car',
                'color' => '#4ECDC4',
                'is_active' => true,
            ],
            [
                'name_ar' => 'التسوق',
                'name_en' => 'Shopping',
                'icon' => 'shopping-bag',
                'color' => '#45B7D1',
                'is_active' => true,
            ],
            [
                'name_ar' => 'الراتب',
                'name_en' => 'Salary',
                'icon' => 'dollar-sign',
                'color' => '#96CEB4',
                'is_active' => true,
            ],
            [
                'name_ar' => 'تحويل',
                'name_en' => 'Transfer',
                'icon' => 'repeat',
                'color' => '#FFEAA7',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate([
                'name_en' => $categoryData['name_en']
            ], $categoryData);
        }

        // Create wallets for users
        $users = User::all();
        foreach ($users as $user) {
            $wallet = Wallet::create([
                'user_id' => $user->id,
                'wallet_number' => Wallet::generateWalletNumber(),
                'balance' => rand(1000, 10000),
                'currency' => 'SAR',
                'status' => 'active',
            ]);

            // Create some transactions for each wallet
            $transactionTypes = ['transfer', 'payment', 'deposit', 'withdrawal'];
            $statuses = ['completed', 'pending', 'failed'];
            
            for ($i = 0; $i < rand(3, 8); $i++) {
                $amount = rand(50, 500);
                $status = $statuses[array_rand($statuses)];
                
                Transaction::create([
                    'wallet_id' => $wallet->id,
                    'type' => $transactionTypes[array_rand($transactionTypes)],
                    'amount' => $amount,
                    'currency' => 'SAR',
                    'category_id' => Category::inRandomOrder()->first()->id,
                    'description' => 'Test transaction ' . ($i + 1),
                    'status' => $status,
                    'reference_id' => 'TXN' . uniqid() . rand(1000, 9999),
                    'metadata' => [
                        'source' => 'test',
                        'created_by' => 'seeder'
                    ],
                ]);
            }
        }

        $this->command->info('Test data seeded successfully!');
        $this->command->info('Users: ' . User::count());
        $this->command->info('Wallets: ' . Wallet::count());
        $this->command->info('Transactions: ' . Transaction::count());
    }
}
