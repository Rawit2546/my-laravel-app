<?php

use Illuminate\Foundation\Application;

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
*/

$app = new Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

// Add this block for Vercel Serverless environment
if (isset($_SERVER['VERCEL_ENV']) || env('VERCEL')) {
    $app->useStoragePath('/tmp/storage');
}

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
*/

// *** บรรทัดนี้คือส่วนที่ต้องแก้ไข ***
$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

// *** บรรทัดนี้คือส่วนที่ต้องแก้ไข (แก้จาก Illuminate\Contracts\Console เป็น Illuminate\Contracts\Console\Kernel) ***
$app->singleton(
    Illuminate\Contracts\Console\Kernel::class, // <--- แก้ตรงนี้
    App\Console\Kernel::class
);

// *** บรรทัดนี้คือส่วนที่ต้องแก้ไข (แก้จาก Illuminate\Contracts\Debug เป็น Illuminate\Contracts\Debug\ExceptionHandler) ***
$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class, // <--- แก้ตรงนี้
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
*/

return $app;