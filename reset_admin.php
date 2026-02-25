<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

$user = User::firstOrNew(['email' => 'info@moderntouchbd.com']);
$user->name = 'Super Admin';
$user->role = 'super_admin';
$user->password = Hash::make('mohib123');
$user->email_verified_at = now();
$user->save();

$user->assignRole('Super Admin');

echo "User info@moderntouchbd.com created/updated successfully with password 'mohib123' and role Super Admin.\n";

