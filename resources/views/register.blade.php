<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สร้างบัญชี</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Prompt', sans-serif;
            /* พื้นหลังและรูปภาพที่คุณจะใส่เอง */
            background-color: #1a202c; /* ตัวอย่างสีพื้นหลัง */
            background-image: url('your-background-image.jpg'); /* ใส่ URL รูปพื้นหลังของคุณที่นี่ */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .register-card {
            background-color: rgba(255, 255, 255, 0.95); /* สีขาวกึ่งโปร่งแสงตามรูป */
            border-radius: 1rem; /* โค้งมน */
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15); /* เงา */
            padding: 2.5rem; /* Padding ภายใน */
            width: 100%;
            max-width: 400px; /* ความกว้างสูงสุด */
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .logo-container {
            margin-bottom: 2rem; /* ระยะห่างจากฟอร์ม */
            padding: 1.5rem 2rem; /* Padding ภายในกรอบโลโก้ */
            background-color: white; /* พื้นหลังสีขาวของกรอบโลโก้ */
            border-radius: 0.75rem; /* โค้งมน */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); /* เงาสำหรับกรอบโลโก้ */
            display: inline-block; /* ทำให้กรอบปรับตามขนาดเนื้อหา */
        }
        .logo-container img {
            max-width: 150px; /* ขนาดของรูปโลโก้ */
            height: auto;
        }
        .form-group {
            margin-bottom: 1.25rem; /* ระยะห่างระหว่างช่องกรอกข้อมูล */
            width: 100%;
            text-align: left; /* จัด Label ให้อยู่ชิดซ้าย */
        }
        .form-label {
            display: block; /* ทำให้ Label ขึ้นบรรทัดใหม่ */
            font-size: 1rem;
            color: #4a5568; /* สีข้อความ Label */
            margin-bottom: 0.5rem; /* ระยะห่างระหว่าง Label กับ Input */
            font-weight: 500; /* ทำให้ตัวอักษรหนาขึ้นเล็กน้อย */
        }
        .form-input {
            width: 100%;
            padding: 1rem;
            border: 1px solid #e2e8f0; /* สีขอบ input */
            border-radius: 0.5rem; /* โค้งมน input */
            background-color: #f8f8f8; /* สีพื้นหลัง input */
            font-size: 1rem;
            color: #333;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: #6366f1; /* สีขอบเมื่อ focus (สีม่วง) */
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }
        .btn-primary {
            background-color: #2563eb; /* สีปุ่ม 'สร้างบัญชี' (น้ำเงิน) */
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: background-color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            margin-top: 1.5rem;
            text-decoration: none;
        }
        .btn-primary:hover {
            background-color: #1d4ed8;
        }
        .link-text {
            color: #4a5568; /* สีข้อความลิงก์ (เทาเข้ม) */
            font-size: 0.9rem;
            margin-top: 1.5rem;
        }
        .link-text a {
            color: #2563eb; /* สีลิงก์ (น้ำเงิน) */
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }
        .link-text a:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }
        /* Styles for error messages */
        .error-message {
            background-color: #fee2e2; /* Red-100 */
            border: 1px solid #ef4444; /* Red-500 */
            color: #b91c1c; /* Red-700 */
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            font-size: 0.875rem;
            text-align: left;
        }
        .error-message ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .error-message li {
            margin-bottom: 0.25rem;
        }
    </style>
</head>
<body>
      <div class="register-card">
        <div class="logo-container">
            <img src="your-logo.png" alt="ITBT Corporation Co.,LTD. Logo">
        </div>

        <!-- ส่วนสำหรับแสดงข้อความผิดพลาดจาก Validation (ถ้ามี) -->
        @if ($errors->any())
            <div class="error-message">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ส่วนสำหรับแสดงข้อความ Success (ถ้ามี) -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- นี่คือฟอร์มที่ถูกต้อง: มีเพียงฟอร์มเดียวและครอบคลุมทุก Input -->
        <form action="/register" method="POST" class="w-full">
            @csrf <!-- สำคัญมากสำหรับ Laravel POST requests -->

            <div class="form-group">
                <label for="username" class="form-label">ชื่อผู้ใช้งาน</label>
                <input type="text" id="username" name="username" class="form-input" placeholder="" value="{{ old('username') }}">
                @error('username')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="" value="{{ old('email') }}">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <label for="password" class="form-label">รหัสผ่าน</label>
                <input type="password" id="password" name="password" class="form-input" placeholder="">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <!-- แนะนำให้เพิ่มช่องยืนยันรหัสผ่านเพื่อความปลอดภัย -->
            <div class="form-group">
                <label for="password_confirmation" class="form-label">ยืนยันรหัสผ่าน</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="">
            </div>

            <button type="submit" class="btn-primary">
                สร้างบัญชี <span class="ml-2">→</span>
            </button>
        </form>

        <p class="link-text">
            ถ้าคุณมีบัญชีอยู่แล้ว <a href="/login">เข้าสู่ระบบ</a>
        </p>
    </div>
</body>
</html>