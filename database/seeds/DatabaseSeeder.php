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
        $this->call(CategoryTableSeeder::class);
        $this->command->info('category table loaded');

        $this->call(BrandTableSeeder::class);
        $this->command->info('brand table loaded');

        $this->call(ProductTableSeeder::class);
        $this->command->info('product table loaded');
    }
}
