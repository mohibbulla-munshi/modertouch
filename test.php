<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $roles = \Spatie\Permission\Models\Role::with('permissions')->get();
    echo view('admin.roles.index', compact('roles'))->render();
    echo "\n\n=== View rendered successfully! ===\n";
} catch (\Throwable $e) {
    echo "ERROR OCCURRED:\n";
    echo $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " on line " . $e->getLine() . "\n";
    
    // If it's a view error, we can sometimes extract the exact blade line:
    if (preg_match('/\(View: (.*?)\)/', $e->getMessage(), $matches)) {
        echo "\nFailed View: " . $matches[1] . "\n";
    }
}
