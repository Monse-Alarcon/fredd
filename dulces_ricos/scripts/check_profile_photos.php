<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Storage;

$users = User::select('id','name','email','profile_photo')->whereNotNull('profile_photo')->get();
if ($users->isEmpty()) {
    echo "No users with profile_photo found.\n";
    exit(0);
}
foreach ($users as $u) {
    $path = $u->profile_photo;
    $exists = Storage::disk('public')->exists($path) ? 'YES' : 'NO';
    $url = Storage::disk('public')->url($path);
    echo "ID: {$u->id} | Name: {$u->name} | profile_photo: {$path} | file_exists: {$exists} | url: {$url}\n";
}
