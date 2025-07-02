<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AppointmentController;




Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
})->name('register.show');


Route::post('/register', function (Request $request) {
    $request->validate([
        'username' => 'required|string|max:255|unique:users',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
    ]);

    User::create([
        'username' => $request->username,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('login')->with('success', 'สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ');
})->name('register.store');

// Route สำหรับจัดการการ Submit ฟอร์ม Login (POST)
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    if (Auth::attempt($credentials, $request->has('remember_me'))) {
        $request->session()->regenerate();
        return redirect()->intended('/index');
    }

    return back()->withErrors([
        'username' => 'ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง',
        'password' => 'ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง',
    ])->onlyInput('username');

})->name('login.attempt');


Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/index');
    }
    return redirect()->route('login');
});


Route::get('/index', [AppointmentController::class, 'index'])->middleware('auth')->name('app.index');

// Route สำหรับแสดงฟอร์มสร้างการนัดหมายใหม่ - ต้อง Login ก่อนเข้าถึง
Route::get('/create_appointment', [AppointmentController::class, 'create'])->middleware('auth')->name('appointment.create');

// **Route สำหรับบันทึกข้อมูลการนัดหมายใหม่ (จากฟอร์ม create_appointment) - ใช้ POST**
Route::post('/appointments', [AppointmentController::class, 'store'])->middleware('auth')->name('appointment.store'); // เพิ่มบรรทัดนี้

// Route สำหรับค้นหาและแสดงการนัดหมาย - ต้อง Login ก่อนเข้าถึง
Route::get('/search_appointments', [AppointmentController::class, 'search'])->middleware('auth')->name('appointment.search');

// Route สำหรับ Dashboard (ตัวอย่าง) - ต้อง Login ก่อนเข้าถึง
Route::get('/dashboard', function () {
    return '<h1>ยินดีต้อนรับสู่ Dashboard!</h1><p><a href="/logout">ออกจากระบบ</a></p>';
})->middleware('auth');

// Route สำหรับออกจากระบบ
Route::get('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->name('logout');

Route::get('/appointments/export/excel', [AppointmentController::class, 'exportExcel'])->middleware('auth')->name('appointment.export.excel');

Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy'])->middleware('auth')->name('appointment.destroy');