<?php
// PHP ส่วนนี้ใช้สำหรับกำหนดค่าเริ่มต้นของ sidebar และ header
// ไม่ได้ประมวลผลข้อมูลฟอร์มในไฟล์นี้
// หากต้องการให้ประมวลผลฟอร์ม จะต้องเพิ่มโค้ด PHP สำหรับการจัดการข้อมูลฟอร์มที่นี่

// กำหนดข้อมูลโปรไฟล์และเมนูสำหรับ Sidebar
use Illuminate\Support\Facades\Auth;
$profileName = Auth::check() ? Auth::user()->username : "ผู้เยี่ยมชม";

$menuItems = [
    ['icon' => '🏠', 'text' => 'หน้าหลัก', 'link' => route('app.index')],
    ['icon' => '🔍', 'text' => 'ค้นหาการนัดหมาย', 'link' => route('appointment.search')],
    ['icon' => '➕', 'text' => 'สร้างการนัดหมายใหม่', 'link' => route('appointment.create'), 'active' => true],
    ['icon' => '✏️', 'text' => 'แก้ไขการนัดหมาย', 'link' => '#'],
    ['icon' => '🖨️', 'text' => 'พิมพ์', 'link' => '#'],
    ['icon' => '⚙️', 'text' => 'การตั้งค่า', 'link' => '#'],
    ['icon' => '📄', 'text' => 'ออกจากระบบ', 'link' => route('logout')],
];

// สำหรับปฏิทินขนาดเล็ก (ในรูปภาพเป็น October 2020)
// ในโค้ดนี้จะจำลองการแสดงผลปฏิทินแบบง่ายๆ โดยไม่ใช้ PHP ในการคำนวณวันจริงจัง
// คุณสามารถพัฒนาส่วนนี้ให้เป็นปฏิทินที่ทำงานได้จริงในภายหลัง
$miniCalendarMonth = 'October';
$miniCalendarYear = '2020';
$miniCalendarDays = ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'];
$miniCalendarDates = [
    // Array of days for October 2020 - starting from Monday
    null, null, null, null, 1, 2, 3,
    4, 5, 6, 7, 8, 9, 10,
    11, 12, 13, 14, 15, 16, 17,
    18, 19, 20, 21, 22, 23, 24,
    25, 26, 27, 28, 29, 30, 31
];

// วันที่ 9 คือวันที่มีวงกลมสีแดงในรูปตัวอย่าง
$highlightedDay = 9;
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สร้างการนัดหมายใหม่</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Prompt', sans-serif;
            background-color: #f0f2f5; /* สีพื้นหลังตามรูป */
        }
        /* Sidebar Styles - Updated to match the new appointment page theme */
        .sidebar {
            background: linear-gradient(180deg, #b0c4de 0%, #a0b2d0 100%); /* โทนสีม่วงอ่อนตามรูป */
            min-height: 100vh;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        /* Adjustments for responsive sidebar: hidden on small screens, fixed width on medium+ */
        @media (max-width: 767px) { /* On small screens (mobile) */
            .sidebar {
                display: none; /* Hide sidebar by default on mobile */
            }
        }
        @media (min-width: 768px) { /* On medium screens (tablet) and larger */
            .sidebar {
                width: 16rem; /* Tailwind's w-64 is 16rem */
                display: flex; /* Ensure it's displayed on larger screens */
                flex-shrink: 0; /* Prevent it from shrinking */
            }
        }

        .sidebar-item {
            transition: all 0.3s ease;
            border-radius: 12px;
            color: #333; /* สีข้อความปกติ */
            padding: 1rem;
            display: flex;
            align-items: center;
            text-decoration: none; /* remove underline for links */
        }
        .sidebar-item span { /* เพิ่ม: สำหรับข้อความเมนู */
            font-size: 1rem; /* ปรับขนาดตามต้องการ เช่น 1rem, 1.05rem, 1.1rem */
        }
        .sidebar-item:hover {
            background-color: rgba(255, 255, 255, 0.3); /* Hover effect */
            transform: translateX(4px);
        }
        .sidebar-item.active {
            background-color: rgba(255, 255, 255, 0.5); /* Active state */
            font-weight: 500;
        }
        /* Profile Section Styles - Updated to match the new appointment page theme */
        .profile-section {
            background-color: rgba(255, 255, 255, 0.5); /* สีโปรไฟล์ตามรูป */
            border-radius: 12px;
            padding: 1rem;
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .profile-icon-circle {
            background-color: #ffffff;
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .profile-name {
            color: #333;
            font-weight: 500;
            font-size: 1.1rem; /* ปรับขนาดตัวหนังสือสำหรับชื่อโปรไฟล์ */
        }
        .main-content {
            flex: 1;
            padding: 1rem; /* Reduced padding for smaller screens */
            background-color: #f0f2f5;
        }
        @media (min-width: 768px) { /* Padding for medium screens and up */
            .main-content {
                padding: 2rem 3rem;
            }
        }
        .form-section {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 1.5rem; /* Reduced padding for smaller screens */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            display: grid; /* เปลี่ยนจาก flex เป็น grid */
            grid-template-columns: repeat(1, 1fr); /* Default to 1 column on small screens */
            gap: 1rem; /* Reduced gap */
        }
        @media (min-width: 640px) { /* On small screens (sm) and up, 2 columns */
            .form-section {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5rem; /* Standard gap for sm+ */
            }
        }
        .form-group {
            display: flex;
            flex-direction: column;
            /* ไม่จำเป็นต้องมี flex: 1 1 45%; แล้ว เพราะ grid-template-columns จะจัดการเอง */
        }
        .form-group.full-width {
            grid-column: 1 / -1; /* ให้กินพื้นที่เต็มทุกคอลัมน์ใน Grid */
        }
        .form-group label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #555;
            font-size: 0.9rem;
        }
        .form-group input,
        .form-group textarea,
        .form-group select {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            color: #333;
            transition: border-color 0.2s;
            background-color: #f8f8f8; /* สีพื้นหลัง input */
        }
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #8b5cf6; /* Purple for focus */
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
        }
        textarea {
            min-height: 150px; /* ความสูงของ textarea */
            resize: vertical;
        }
        .calendar-container {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 1rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            width: 100%; /* Full width on small screens */
            margin-left: 0; /* No margin on small screens */
            margin-top: 1.5rem; /* Margin when stacked */
        }
        @media (min-width: 768px) { /* Adjust for medium screens and up */
            .calendar-container {
                width: 280px; /* Fixed width on larger screens */
                margin-left: 2rem; /* Restore margin when side-by-side */
                margin-top: 0; /* No top margin when side-by-side */
            }
        }
        .calendar-header-mini {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            color: #333;
            font-weight: 600;
        }
        .calendar-header-mini button {
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: #555;
        }
        .calendar-grid-mini {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.3rem;
            text-align: center;
        }
        .calendar-grid-mini div {
            padding: 0.4rem 0.2rem;
            font-size: 0.8rem;
            color: #666;
        }
        .calendar-grid-mini .day-header {
            font-weight: 600;
            color: #333;
        }
        .calendar-grid-mini .date-cell {
            background-color: #f8f8f8;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .calendar-grid-mini .date-cell:hover {
            background-color: #e0e0e0;
        }
        .calendar-grid-mini .date-cell.highlighted {
            background-color: #ef4444; /* สีแดงตามรูป */
            color: white;
            font-weight: 600;
        }
        .buttons-group {
            display: flex;
            justify-content: flex-end; /* ชิดขวา */
            gap: 1rem;
            margin-top: 2rem;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .btn-confirm {
            background-color: #22c55e; /* สีเขียว */
            color: white;
        }
        .btn-confirm:hover {
            background-color: #16a34a;
            box-shadow: 0 4px 8px rgba(34,197,94,0.3);
        }
        .btn-cancel {
            background-color: #ef4444; /* สีแดง */
            color: white;
        }
        .btn-cancel:hover {
            background-color: #dc2626;
            box-shadow: 0 4px 8px rgba(239,68,68,0.3);
        }
    </style>
</head>
<body class="flex flex-col md:flex-row min-h-screen"> {{-- Adjusted for responsive layout and full height --}}
    <div class="w-full md:w-64 sidebar hidden sm:flex flex-col"> {{-- Adjusted for responsive sidebar --}}
        <div class="profile-section">
            <div class="profile-icon-circle">
                <span class="text-gray-700 text-2xl">👤</span>
            </div>
            <span class="profile-name"><?php echo $profileName; ?></span>
        </div>

        <div class="space-y-3">
            <?php foreach ($menuItems as $item): ?>
                <a href="<?php echo $item['link']; ?>" class="sidebar-item <?php echo isset($item['active']) && $item['active'] ? 'active' : ''; ?>">
                    <span class="mr-4 text-xl"><?php echo $item['icon']; ?></span>
                    <span class="text-sm <?php echo isset($item['active']) && $item['active'] ? 'font-medium' : ''; ?>"><?php echo $item['text']; ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="main-content flex flex-col"> {{-- Adjusted for responsive main content, and flex-col for its internal content --}}
        <h1 class="text-3xl font-semibold text-gray-800 mb-8">สร้างการนัดหมายใหม่</h1>

        <div class="flex flex-col md:flex-row gap-4 md:gap-8"> {{-- Stack on small screens, side-by-side on md+ --}}
            {{-- เปลี่ยน action และ method ให้ส่งไปที่ route('appointment.store') ด้วย POST --}}
            <form action="{{ route('appointment.store') }}" method="POST" class="form-section flex-grow">
                @csrf {{-- เพิ่ม CSRF token เพื่อความปลอดภัยในการส่งฟอร์มแบบ POST --}}
                <div class="form-group">
                    <label for="plan_name">ชื่อแผน</label>
                    <input type="text" id="plan_name" name="plan_name" placeholder="ชื่อแผน">
                </div>
                <div class="form-group">
                    <label for="task_name">ชื่องาน</label>
                    <input type="text" id="task_name" name="task_name" placeholder="ชื่องาน">
                </div>
                <div class="form-group">
                    <label for="contract_number">เลขที่สัญญา</label>
                    <input type="text" id="contract_number" name="contract_number" placeholder="เลขที่สัญญา">
                </div>
                <div class="form-group">
                    <label for="department">หน่วยงาน</label>
                    <input type="text" id="department" name="department" placeholder="หน่วยงาน">
                </div>
                <div class="form-group">
                    <label for="supervisor">ผู้คุมงาน</label>
                    <input type="text" id="supervisor" name="supervisor" placeholder="ชื่อผู้คุมงาน">
                </div>
                <div class="form-group">
                    <label for="start_date">ตั้งแต่วันที่</label>
                    <input type="date" id="start_date" name="start_date">
                </div>
                <div class="form-group">
                    <label for="end_date">ถึงวันที่</label>
                    <input type="date" id="end_date" name="end_date">
                </div>

                <div class="form-group">
                    <label for="time">เวลา</label>
                    <input type="time" id="time" name="time">
                </div>
                <div class="form-group">
                    <label for="status">สถานะนัดหมาย</label>
                    <select id="status" name="status">
                        <option value="">เลือกสถานะ</option>
                        <option value="pending">รอดำเนินการ</option>
                        <option value="confirmed">ยืนยันแล้ว</option>
                        <option value="cancelled">ยกเลิกแล้ว</option>
                        <option value="completed">เสร็จสิ้น</option>
                        <option value="in_progress">อยู่ระหว่างดำเนินการ</option>
                        <option value="not_started">ยังไม่เริ่มดำเนินการ</option>
                    </select>
                </div>

                {{-- เพิ่ม field สำหรับ Name และ Location ตาม migration --}}
                <div class="form-group">
                    <label for="name">ชื่อผู้จอง</label>
                    <input type="text" id="name" name="name" placeholder="ชื่อผู้ติดต่อ">
                </div>
                <div class="form-group">
                    <label for="location">สถานที่</label>
                    <input type="text" id="location" name="location" placeholder="ห้องประชุม / ร้านกาแฟ / ร.พ.">
                </div>
                
                <div class="form-group full-width">
                    <label for="details">รายละเอียด</label>
                    <textarea id="details" name="details" placeholder="รายละเอียดการนัดหมาย"></textarea>
                </div>
                
                <div class="buttons-group full-width">
                    <button type="submit" class="btn btn-confirm">
                        <span class="mr-2 text-xl align-middle">✅</span> ยืนยัน
                    </button>
                    <button type="button" class="btn btn-cancel">
                        <span class="mr-2 text-xl align-middle">❌</span> ยกเลิก
                    </button>
                </div>
            </form>

            <div class="calendar-container">
                <div class="calendar-header-mini">
                    <button>&#9664;</button> <span><?php echo $miniCalendarMonth . ' ' . $miniCalendarYear; ?></span>
                    <button>&#9654;</button> </div>
                <div class="calendar-grid-mini">
                    <?php
                    // Display dates for the mini calendar
                    foreach ($miniCalendarDays as $day): ?>
                        <div class="day-header"><?php echo $day; ?></div>
                    <?php endforeach; ?>
                    <?php
                    foreach ($miniCalendarDates as $date) {
                        if ($date === null) {
                            echo '<div></div>'; // Empty cell
                        } else {
                            $highlightClass = ($date == $highlightedDay) ? 'highlighted' : '';
                            echo '<div class="date-cell ' . $highlightClass . '">' . $date . '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>