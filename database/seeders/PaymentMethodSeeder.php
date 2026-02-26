<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            [
                'name'         => 'Cash on Delivery',
                'type'         => 'cod',
                'description'  => 'Pay when you receive your order',
                'instructions' => 'Please keep the exact amount ready when the delivery person arrives.',
                'is_active'    => true,
            ],
            [
                'name'         => 'Bank Transfer',
                'type'         => 'bank',
                'description'  => 'Direct transfer to our bank account',
                'instructions' => "Bank Name: Dutch-Bangla Bank\nAccount Name: Modern Touch BD\nAccount No: 123.456.78910\nBranch: Mirpur.\n\nPlease use your Order ID as the payment reference.",
                'is_active'    => true,
            ],
            [
                'name'         => 'bKash',
                'type'         => 'bkash',
                'description'  => 'Pay securely via your bKash wallet',
                'instructions' => "Please send the total amount to our bKash Merchant Number: 01700000000.\nAfter sending, enter your TrxID in the payment reference box.",
                'is_active'    => true,
            ],
            [
                'name'         => 'Nagad',
                'type'         => 'nagad',
                'description'  => 'Pay securely via your Nagad wallet',
                'instructions' => "Please send the total amount to our Nagad Merchant Number: 01700000000.\nAfter sending, enter your TrxID in the payment reference box.",
                'is_active'    => true,
            ],
            [
                'name'         => 'Rocket',
                'type'         => 'rocket',
                'description'  => 'Pay securely via your Rocket wallet',
                'instructions' => "Please send the total amount to our Rocket Merchant Number: 01700000000-0.\nAfter sending, enter your TrxID in the payment reference box.",
                'is_active'    => true,
            ],
            [
                'name'         => 'Upay',
                'type'         => 'upay',
                'description'  => 'Pay securely via your Upay wallet',
                'instructions' => "Please send the total amount to our Upay Merchant Number: 01700000000.\nAfter sending, enter your TrxID in the payment reference box.",
                'is_active'    => false, // Disabled by default
            ],
            [
                'name'         => 'SSLCommerz',
                'type'         => 'sslcommerz',
                'description'  => 'Pay online using Cards, Mobile Banking, or Net Banking',
                'instructions' => 'You will be redirected to the secure SSLCommerz payment gateway to complete your purchase.',
                'is_active'    => false, // Disabled by default
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(
                ['name' => $method['name']], 
                $method
            );
        }
    }
}
