<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
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
        .login-card {
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
        .password-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem; /* ลดระยะห่างถ้ามี Checkbox ตามมา */
        }
        .forgot-password-link {
            color: #2563eb; /* สีลิงก์ 'ลืมรหัสผ่าน?' */
            font-size: 0.9rem;
            text-decoration: none;
            font-weight: 500;
        }
        .forgot-password-link:hover {
            text-decoration: underline;
        }
        .remember-me-group {
            display: flex;
            align-items: center;
            margin-top: 1rem; /* ระยะห่างจากช่องรหัสผ่าน */
            margin-bottom: 1.5rem; /* ระยะห่างก่อนปุ่ม */
            justify-content: flex-start; /* ชิดซ้าย */
            width: 100%;
        }
        .remember-me-group input[type="checkbox"] {
            margin-right: 0.5rem;
            width: 1.1rem;
            height: 1.1rem;
            accent-color: #2563eb; /* สีของ checkbox เมื่อเลือก */
        }
        .remember-me-group label {
            font-size: 0.95rem;
            color: #4a5568;
        }
        .btn-primary {
            background-color: #2563eb; /* สีปุ่ม 'เข้าสู่ระบบ' (น้ำเงิน) */
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
            margin-top: 1.5rem; /* ปรับ margin-top ให้เข้ากับ layout */
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
        /* Styles for messages */
        .message-box {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            font-size: 0.875rem;
            text-align: left;
            width: 100%;
        }
        .message-box.success {
            background-color: #dcfce7; /* Green-100 */
            border: 1px solid #22c55e; /* Green-500 */
            color: #15803d; /* Green-700 */
        }
        .message-box.error {
            background-color: #fee2e2; /* Red-100 */
            border: 1px solid #ef4444; /* Red-500 */
            color: #b91c1c; /* Red-700 */
        }
        .message-box ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .message-box li {
            margin-bottom: 0.25rem;
        }
    </style>
</head>
<body>  
    <div class="login-card">
        

        @if(session('success'))
            <div class="message-box success" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="message-box error" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/login" method="POST" class="w-full">
            @csrf
            <div class="form-group">
                <label for="username" class="form-label">ชื่อผู้ใช้งาน</label>
                <input type="text" id="username" name="username" class="form-input" placeholder="" value="{{ old('username') }}">
                @error('username')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <div class="password-group">
                    <label for="password" class="form-label">รหัสผ่าน</label>
                    <a href="#" class="forgot-password-link">ลืมรหัสผ่าน?</a>
                </div>
                <input type="password" id="password" name="password" class="form-input" placeholder="">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="remember-me-group">
                <input type="checkbox" id="remember_me" name="remember_me">
                <label for="remember_me">จดจำการเข้าสู่ระบบ</label>
            </div>

            <button type="submit" class="btn-primary">
                เข้าสู่ระบบ <span class="ml-2">→</span>
            </button>
        </form>

        <p class="link-text">
            คุณยังไม่มีบัญชี? <a href="/register">ลงทะเบียนที่นี่</a>
        </p>
    </div>
</body>
</html>