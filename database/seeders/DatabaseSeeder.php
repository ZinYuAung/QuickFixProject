<?php

namespace Database\Seeders;

use App\Models\User;
use DB;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // 1. Create a City & Service Area first
        $cityId = DB::table('cities')->insertGetId([
            'name' => 'Yangon',
            'is_active' => true,
        ]);

        $areaId = DB::table('service_areas')->insertGetId([
            'city_id' => $cityId,
            'name' => 'Downtown',
            'is_active' => true,
        ]);

        //service categories and services
        $categoryId = DB::table('service_categories')->insertGetId([
            'name' => 'Air Conditioning',
            'description' => 'AC repair, maintenance, and installation services',
            'is_active' => true,
        ]);

        $serviceId = DB::table('services')->insertGetId([
            'service_category_id' => $categoryId,
            'name' => 'AC General Servicing',
            'description' => 'Complete cleaning and filter check for standard AC units',
            'base_price' => 35000.00,             
            'estimated_minutes' => 60,            
            'is_active' => true,
        ]);

        //superadmin
        $superAdminUser = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@quickfix.com',
            'phone' => '09111111111',
            'password' => Hash::make('superadmin@123'),
            'role' => 'superadmin',
            'is_active' => true,
        ]);

        $superAdminId = DB::table('admins')->insertGetId([
            'user_id' => $superAdminUser->id,
        ]);

        //admin
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@quickfix.com',
            'phone' => '09222222222',
            'password' => Hash::make('admin@123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $adminId = DB::table('admins')->insertGetId([
            'user_id' => $adminUser->id,
        ]);

        //customer
        $customerUser = User::create([
            'name' => 'John Customer',
            'email' => 'customer@gmail.com',
            'phone' => '09333333333',
            'password' => Hash::make('customer@123'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        DB::table('customers')->insert([
            'user_id' => $customerUser->id,
            'area_id' => $areaId,
            'address' => 'No. 123, Sule Pagoda Road, Yangon',
        ]);

        //provider
        $providerUser = User::create([
            'name' => 'David Technician',
            'email' => 'provider@quickfix.com',
            'phone' => '09444444444',
            'password' => Hash::make('provider@123'),
            'role' => 'provider',
            'is_active' => true,
        ]);

        DB::table('providers')->insert([
            'user_id' => $providerUser->id,
            'area_id' => $areaId,
            'bio' => 'Professional AC and Electrical Repairer with 5 years experience.',
            'experience_years' => 5,
            'rating' => 4.80,
            'availability_status' => 'online',
            'approve_by' => $adminId, // Approved by the admin created above
            'approval_status' => 'approved',
        ]);
    }
}
