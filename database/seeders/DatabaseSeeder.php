<?php

namespace Database\Seeders;

use App\Models\{User, Category, Setting, Tag};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Super Admin ──────────────────────────────────────────────────
        User::firstOrCreate(['email' => 'admin@moderntouchbd.com'], [
            'name'              => 'Super Admin',
            'password'          => Hash::make('Admin@12345'),
            'role'              => 'super_admin',
            'email_verified_at' => now(),
        ]);

        // ── Default Settings ─────────────────────────────────────────────
        $settings = [
            'app_name'         => 'Modern Touch BD',
            'tagline'          => 'Premium Industrial Furniture & Racking Solutions in Bangladesh.',
            'email'            => 'info@moderntouchbd.com',
            'phone'            => '+880 1700-000000',
            'address'          => 'Dhaka, Bangladesh',
            'facebook'         => 'https://facebook.com/moderntouchbd',
            'whatsapp'         => '8801700000000',
            'maintenance_mode' => '0',
            'currency'         => 'BDT',
            'currency_symbol'  => '৳',
            'bank_name'        => 'Dutch-Bangla Bank Limited',
            'bank_account'     => 'N/A',
        ];

        foreach ($settings as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        // ── Categories ───────────────────────────────────────────────────
        $cats = [
            ['name' => 'Steel Racking Systems',     'slug' => 'steel-racking-systems'],
            ['name' => 'Industrial Shelving',        'slug' => 'industrial-shelving'],
            ['name' => 'Office Furniture',           'slug' => 'office-furniture'],
            ['name' => 'Warehouse Equipment',        'slug' => 'warehouse-equipment'],
            ['name' => 'Storage Cabinets',           'slug' => 'storage-cabinets'],
            ['name' => 'Metal Lockers',              'slug' => 'metal-lockers'],
            ['name' => 'Pallet Racking',             'slug' => 'pallet-racking'],
            ['name' => 'Mezzanine Floors',           'slug' => 'mezzanine-floors'],
        ];

        foreach ($cats as $i => $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], [
                'name'       => $cat['name'],
                'is_active'  => true,
                'sort_order' => $i + 1,
            ]);
        }

        // ── Tags ─────────────────────────────────────────────────────────
        $tags = ['Heavy Duty', 'Industrial', 'Galvanized', 'Powder Coated', 'Adjustable', 'Stackable', 'Fire Resistant'];
        foreach ($tags as $tag) {
            Tag::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($tag)],
                ['name' => $tag]
            );
        }
    }
}
