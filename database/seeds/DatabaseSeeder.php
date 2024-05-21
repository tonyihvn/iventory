<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('categories')->insert([
            [
            'category_name' => 'Computers',
            'description' => 'All Computers'
            ],
            [
            'category_name' => 'Furnitures',
            'description' => 'All Computers'
            ]
        ]);
        
        // $this->call(AuditSeeder::class);
        // $this->call(CategorySeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(FacilitiesSeeder::class);
        $this->call(InventorySeeder::class);
        // $this->call(InventoryspecSeeder::class);
        // $this->call(MovementSeeder::class);
        // $this->call(PurchasesSeeder::class);
        $this->call(SupplierSeeder::class);
        $this->call(UnitSeeder::class);
        
        $this->call(UserSeeder::class);
        
        DB::table('settings')->insert([
            'organization_name' => 'IHVN, Nigeria',
            'description' => 'Virus Research and Rehabilitation',
            'logo' => 'ihvnlogo.png',
            'address' => 'Central Business District, Abuja',
            'phone_number' => '2348012345678',
            'copyright' => 'IHVN '.date("Y").'&copy; All Rights Reserved',
        ]);       

    }
}
