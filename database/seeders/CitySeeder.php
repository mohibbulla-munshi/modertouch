<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            ['name' => 'Dhaka', 'shipping_cost' => 60],
            ['name' => 'Bagerhat', 'shipping_cost' => 120],
            ['name' => 'Bandarban', 'shipping_cost' => 120],
            ['name' => 'Barguna', 'shipping_cost' => 120],
            ['name' => 'Barisal', 'shipping_cost' => 120],
            ['name' => 'Bhola', 'shipping_cost' => 120],
            ['name' => 'Bogra', 'shipping_cost' => 120],
            ['name' => 'Brahmanbaria', 'shipping_cost' => 120],
            ['name' => 'Chandpur', 'shipping_cost' => 120],
            ['name' => 'Chittagong', 'shipping_cost' => 120],
            ['name' => 'Chuadanga', 'shipping_cost' => 120],
            ['name' => 'Comilla', 'shipping_cost' => 120],
            ['name' => 'Cox\'s Bazar', 'shipping_cost' => 120],
            ['name' => 'Dinajpur', 'shipping_cost' => 120],
            ['name' => 'Faridpur', 'shipping_cost' => 120],
            ['name' => 'Feni', 'shipping_cost' => 120],
            ['name' => 'Gaibandha', 'shipping_cost' => 120],
            ['name' => 'Gazipur', 'shipping_cost' => 120],
            ['name' => 'Gopalganj', 'shipping_cost' => 120],
            ['name' => 'Habiganj', 'shipping_cost' => 120],
            ['name' => 'Jamalpur', 'shipping_cost' => 120],
            ['name' => 'Jessore', 'shipping_cost' => 120],
            ['name' => 'Jhalokati', 'shipping_cost' => 120],
            ['name' => 'Jhenaidah', 'shipping_cost' => 120],
            ['name' => 'Joypurhat', 'shipping_cost' => 120],
            ['name' => 'Khagrachhari', 'shipping_cost' => 120],
            ['name' => 'Khulna', 'shipping_cost' => 120],
            ['name' => 'Kishoreganj', 'shipping_cost' => 120],
            ['name' => 'Kurigram', 'shipping_cost' => 120],
            ['name' => 'Kushtia', 'shipping_cost' => 120],
            ['name' => 'Lakshmipur', 'shipping_cost' => 120],
            ['name' => 'Lalmonirhat', 'shipping_cost' => 120],
            ['name' => 'Madaripur', 'shipping_cost' => 120],
            ['name' => 'Magura', 'shipping_cost' => 120],
            ['name' => 'Manikganj', 'shipping_cost' => 120],
            ['name' => 'Meherpur', 'shipping_cost' => 120],
            ['name' => 'Moulvibazar', 'shipping_cost' => 120],
            ['name' => 'Munshiganj', 'shipping_cost' => 120],
            ['name' => 'Mymensingh', 'shipping_cost' => 120],
            ['name' => 'Naogaon', 'shipping_cost' => 120],
            ['name' => 'Narail', 'shipping_cost' => 120],
            ['name' => 'Narayanganj', 'shipping_cost' => 120],
            ['name' => 'Narsingdi', 'shipping_cost' => 120],
            ['name' => 'Natore', 'shipping_cost' => 120],
            ['name' => 'Nawabganj', 'shipping_cost' => 120],
            ['name' => 'Netrokona', 'shipping_cost' => 120],
            ['name' => 'Nilphamari', 'shipping_cost' => 120],
            ['name' => 'Noakhali', 'shipping_cost' => 120],
            ['name' => 'Pabna', 'shipping_cost' => 120],
            ['name' => 'Panchagarh', 'shipping_cost' => 120],
            ['name' => 'Patuakhali', 'shipping_cost' => 120],
            ['name' => 'Pirojpur', 'shipping_cost' => 120],
            ['name' => 'Rajbari', 'shipping_cost' => 120],
            ['name' => 'Rajshahi', 'shipping_cost' => 120],
            ['name' => 'Rangamati', 'shipping_cost' => 120],
            ['name' => 'Rangpur', 'shipping_cost' => 120],
            ['name' => 'Satkhira', 'shipping_cost' => 120],
            ['name' => 'Shariatpur', 'shipping_cost' => 120],
            ['name' => 'Sherpur', 'shipping_cost' => 120],
            ['name' => 'Sirajganj', 'shipping_cost' => 120],
            ['name' => 'Sunamganj', 'shipping_cost' => 120],
            ['name' => 'Sylhet', 'shipping_cost' => 120],
            ['name' => 'Tangail', 'shipping_cost' => 120],
            ['name' => 'Thakurgaon', 'shipping_cost' => 120]
        ];

        foreach ($cities as $city) {
            City::updateOrCreate(['name' => $city['name']], [
                'shipping_cost' => $city['shipping_cost'],
                'is_active' => true
            ]);
        }
    }
}
