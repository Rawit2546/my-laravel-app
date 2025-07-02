<?php

use Illuminate\Foundation\Application; // <-- เพิ่มบรรทัดนี้เข้ามา

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Application( // ตอนนี้สามารถใช้แค่ Application ได้แล้ว
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

// Add this block for Vercel Serverless environment
if (isset($_SERVER['VERCEL_ENV']) || env('VERCEL')) { // ตรวจสอบ VERCEL_ENV หรือ VERCEL env variable
    $app->useStoragePath('/tmp/storage');
}

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when they are needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;