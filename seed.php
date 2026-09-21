<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// Now we can use Laravel classes
use Database\Seeders\DatabaseSeeder;

$seeder = new DatabaseSeeder();
$seeder->run();

echo "✓ Database seeding completed successfully!\n";
