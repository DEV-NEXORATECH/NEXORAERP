<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Company;
use App\Models\User;

$company = Company::firstOrCreate(
    ['code' => 'NX-001'],
    ['name' => 'NEXORA', 'is_active' => true]
);
echo "Company: {$company->name} (code: {$company->code})\n";

$users = User::whereNull('company_id')->get();
$count = 0;
foreach ($users as $user) {
    $user->company_id = $company->id;
    $user->save();
    $count++;
}
echo "Updated {$count} users with company_id.\n";
echo "Done.\n";
