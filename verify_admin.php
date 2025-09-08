<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$user = User::where('email', 'admin@inventory.test')->first();

if ($user) {
    echo "Admin User Found:\n";
    echo "Name: " . $user->name . "\n";
    echo "Email: " . $user->email . "\n";
    echo "Roles: " . $user->roles->pluck('name')->join(', ') . "\n";
    echo "Total Permissions: " . $user->getAllPermissions()->count() . "\n";
    echo "\nAdmin account created successfully!\n";
} else {
    echo "Admin user not found!\n";
}

echo "\nTotal Users: " . User::count() . "\n";
echo "Total Roles: " . \Spatie\Permission\Models\Role::count() . "\n";
echo "Total Permissions: " . \Spatie\Permission\Models\Permission::count() . "\n";