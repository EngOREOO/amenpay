<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Food & Dining
            [
                'name_ar' => 'الطعام والمطاعم',
                'name_en' => 'Food & Dining',
                'icon' => 'restaurant',
                'color' => '#FF6B6B',
                'parent_id' => null,
            ],
            [
                'name_ar' => 'مطاعم',
                'name_en' => 'Restaurants',
                'icon' => 'restaurant',
                'color' => '#FF8E8E',
                'parent_id' => 1,
            ],
            [
                'name_ar' => 'تسوق البقالة',
                'name_en' => 'Grocery Shopping',
                'icon' => 'shopping_cart',
                'color' => '#FFB3B3',
                'parent_id' => 1,
            ],
            [
                'name_ar' => 'قهوة ومشروبات',
                'name_en' => 'Coffee & Beverages',
                'icon' => 'local_cafe',
                'color' => '#FFD1D1',
                'parent_id' => 1,
            ],

            // Transportation
            [
                'name_ar' => 'المواصلات',
                'name_en' => 'Transportation',
                'icon' => 'directions_car',
                'color' => '#4ECDC4',
                'parent_id' => null,
            ],
            [
                'name_ar' => 'وقود',
                'name_en' => 'Fuel',
                'icon' => 'local_gas_station',
                'color' => '#6EDDD6',
                'parent_id' => 5,
            ],
            [
                'name_ar' => 'تأمين السيارات',
                'name_en' => 'Car Insurance',
                'icon' => 'security',
                'color' => '#8EE8E1',
                'parent_id' => 5,
            ],
            [
                'name_ar' => 'صيانة السيارات',
                'name_en' => 'Car Maintenance',
                'icon' => 'build',
                'color' => '#AEF2EB',
                'parent_id' => 5,
            ],
            [
                'name_ar' => 'تأجير السيارات',
                'name_en' => 'Car Rental',
                'icon' => 'directions_car',
                'color' => '#CEF6F0',
                'parent_id' => 5,
            ],

            // Shopping
            [
                'name_ar' => 'التسوق',
                'name_en' => 'Shopping',
                'icon' => 'shopping_bag',
                'color' => '#45B7D1',
                'parent_id' => null,
            ],
            [
                'name_ar' => 'ملابس وأحذية',
                'name_en' => 'Clothing & Shoes',
                'icon' => 'checkroom',
                'color' => '#6BC5D9',
                'parent_id' => 10,
            ],
            [
                'name_ar' => 'إلكترونيات',
                'name_en' => 'Electronics',
                'icon' => 'devices',
                'color' => '#91D3E1',
                'parent_id' => 10,
            ],
            [
                'name_ar' => 'أثاث منزلي',
                'name_en' => 'Home & Furniture',
                'icon' => 'home',
                'color' => '#B7E1E9',
                'parent_id' => 10,
            ],
            [
                'name_ar' => 'مستحضرات تجميل',
                'name_en' => 'Beauty & Cosmetics',
                'icon' => 'face',
                'color' => '#DDEFF1',
                'parent_id' => 10,
            ],

            // Entertainment
            [
                'name_ar' => 'الترفيه',
                'name_en' => 'Entertainment',
                'icon' => 'movie',
                'color' => '#96CEB4',
                'parent_id' => null,
            ],
            [
                'name_ar' => 'سينما',
                'name_en' => 'Cinema',
                'icon' => 'movie',
                'color' => '#B0D8C2',
                'parent_id' => 15,
            ],
            [
                'name_ar' => 'سفر',
                'name_en' => 'Travel',
                'icon' => 'flight',
                'color' => '#CAE2D0',
                'parent_id' => 15,
            ],
            [
                'name_ar' => 'رياضة ولياقة',
                'name_en' => 'Sports & Fitness',
                'icon' => 'fitness_center',
                'color' => '#E4ECDE',
                'parent_id' => 15,
            ],

            // Bills & Utilities
            [
                'name_ar' => 'الفواتير والمرافق',
                'name_en' => 'Bills & Utilities',
                'icon' => 'receipt',
                'color' => '#FFEAA7',
                'parent_id' => null,
            ],
            [
                'name_ar' => 'كهرباء',
                'name_en' => 'Electricity',
                'icon' => 'bolt',
                'color' => '#FFEDB8',
                'parent_id' => 19,
            ],
            [
                'name_ar' => 'ماء',
                'name_en' => 'Water',
                'icon' => 'water_drop',
                'color' => '#FFF0C9',
                'parent_id' => 19,
            ],
            [
                'name_ar' => 'غاز',
                'name_en' => 'Gas',
                'icon' => 'local_fire_department',
                'color' => '#FFF3DA',
                'parent_id' => 19,
            ],
            [
                'name_ar' => 'إنترنت',
                'name_en' => 'Internet',
                'icon' => 'wifi',
                'color' => '#FFF6EB',
                'parent_id' => 19,
            ],

            // Income
            [
                'name_ar' => 'الدخل',
                'name_en' => 'Income',
                'icon' => 'account_balance_wallet',
                'color' => '#55A3FF',
                'parent_id' => null,
            ],
            [
                'name_ar' => 'راتب',
                'name_en' => 'Salary',
                'icon' => 'work',
                'color' => '#7BB8FF',
                'parent_id' => 24,
            ],
            [
                'name_ar' => 'تحويلات',
                'name_en' => 'Transfers',
                'icon' => 'account_balance',
                'color' => '#A1CDFF',
                'parent_id' => 24,
            ],
            [
                'name_ar' => 'استثمارات',
                'name_en' => 'Investments',
                'icon' => 'trending_up',
                'color' => '#C7E2FF',
                'parent_id' => 24,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
