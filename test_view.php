<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
Illuminate\Support\Facades\Auth::loginUsingId(1); // Login as admin
$response = $kernel->handle(
    $request = Illuminate\Http\Request::create('/admin/permissions', 'GET')
);
file_put_contents('/tmp/test_out.html', $response->getContent());
