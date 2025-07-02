<?php
// กำหนดเดือนและปี
$currentMonth = isset($_GET['month']) ? (int)$_GET['month'] : date('n'); // เริ่มที่เดือนปัจจุบัน
$currentYear = isset($_GET['year']) ? (int)$_GET['year'] : date('Y'); // เริ่มที่ปีปัจจุบัน

// ตรวจสอบขอบเขตเดือน
if ($currentMonth < 1) {
    $currentMonth = 12;
    $currentYear--;
} elseif ($currentMonth > 12) {
    $currentMonth = 1;
    $currentYear++;
}

// รายชื่อเดือนภาษาอังกฤษ
$months = [
    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
];

// รายชื่อวันภาษาอังกฤษ
$days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

// คำนวณวันแรกของเดือนและจำนวนวันในเดือน
$firstDay = date('w', mktime(0, 0, 0, $currentMonth, 1, $currentYear));
$daysInMonth = date('t', mktime(0, 0, 0, $currentMonth, 1, $currentYear));

// ข้อมูลตัวอย่างกิจกรรม
$events = [
    // 5 => ['text' => 'ประชุมโปรเจกต์ X', 'color' => 'bg-blue-200 text-blue-800', 'type' => 'text'],
    // 10 => ['text' => 'โทรหาลูกค้า', 'color' => 'bg-purple-200 text-purple-800', 'type' => 'text'],
    // 15 => ['type' => 'emoji', 'content' => '📅'],
    // 22 => ['text' => 'นำเสนอผลงาน', 'color' => 'bg-orange-200 text-orange-800', 'type' => 'text'],
];


// URL สำหรับเปลี่ยนเดือน
$prevMonth = $currentMonth - 1;
$nextMonth = $currentMonth + 1;
$prevYear = $currentYear;
$nextYear = $currentYear;

if ($prevMonth < 1) {
    $prevMonth = 12;
    $prevYear--;
}
if ($nextMonth > 12) {
    $nextMonth = 1;
    $nextYear++;
}

// กำหนดข้อมูลโปรไฟล์และเมนูสำหรับ Sidebar
$profileName = "นายจิวิลิส คิบหน่วย";
$menuItems = [
    ['icon' => '🏠', 'text' => 'หน้าหลัก', 'link' => '/index', 'active' => true], // กำหนดให้ active เมื่ออยู่ในหน้านี้
    ['icon' => '🔍', 'text' => 'ค้นหาการนัดหมาย', 'link' => '/search_appointments'], // ลิงก์ไปยัง URL /search_appointments
    ['icon' => '➕', 'text' => 'สร้างการนัดหมายใหม่', 'link' => '/create_appointment'], // ลิงก์ไปยังหน้าสร้างการนัดหมาย
    ['icon' => '✏️', 'text' => 'แก้ไขการนัดหมาย', 'link' => '#'],
    ['icon' => '🖨️', 'text' => 'พิมพ์', 'link' => '#'],
    ['icon' => '⚙️', 'text' => 'การตั้งค่า', 'link' => '#'],
];
$bottomMenuItem = ['icon' => '📄', 'text' => 'ออกจากระบบ', 'link' => '#'];

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ปฏิทิน <?php echo $months[$currentMonth] . ' ' . $currentYear; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Prompt', sans-serif;
            background-color: #f0f2f5; /* สีพื้นหลังตามธีมใหม่ */
        }
        /* Sidebar Styles - Updated to match the new appointment page theme */
        .sidebar {
            background: linear-gradient(180deg, #b0c4de 0%, #a0b2d0 100%); /* โทนสีม่วงอ่อนตามรูป */
            min-height: 100vh;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
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
            font-size: 0.95rem;
        }

        /* Calendar Specific Styles - Updated for the new theme */
        .main-content {
            flex: 1;
            padding: 2rem 3rem;
            background-color: #f0f2f5; /* Match body background */
        }
        .calendar-header {
            background: linear-gradient(90deg, #8b5cf6 0%, #a78bfa 100%); /* เปลี่ยนสี Header Calendar เป็นโทนม่วง */
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 15px; /* เพิ่มความโค้งมน */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }
        .calendar-header h1 {
            color: white; /* Make sure title is white */
        }
        .nav-button {
            background: rgba(255, 255, 255, 0.2); /* ปุ่มโปร่งแสง */
            border: none; /* ไม่มีขอบ */
            backdrop-filter: blur(5px);
            color: white;
            transition: background-color 0.2s, transform 0.2s;
        }
        .nav-button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px); /* เพิ่ม effect ยกขึ้นเล็กน้อย */
        }
        .calendar-grid-container {
            background-color: #ffffff; /* พื้นหลังตารางปฏิทินเป็นสีขาว */
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            border: 1px solid #e0e0e0; /* เพิ่มเส้นขอบบางๆ */
        }
        .calendar-days-header {
            background-color: #e0e6f0; /* สีพื้นหลังส่วนหัวของวัน (Sun, Mon...) */
            color: #555;
            font-weight: 600;
            padding: 0.75rem 0;
            border-bottom: 1px solid #d0d0d0;
        }
        .calendar-cell {
            min-height: 110px;
            transition: all 0.2s ease;
            background-color: #ffffff; /* ช่องวันเป็นสีขาว */
            border-right: 1px solid #e0e0e0; /* เส้นขอบช่องวัน */
            border-bottom: 1px solid #e0e0e0; /* เส้นขอบช่องวัน */
            padding: 0.75rem;
            position: relative;
        }
        .calendar-cell:hover {
            background-color: #f5f5f5; /* Hover effect */
        }
        .calendar-cell:nth-child(7n) { /* Remove right border for last column */
            border-right: none;
        }
        /* Adjusted for full 6 rows (42 cells) display logic */
        .calendar-grid-container .grid-cols-7 > div:nth-last-child(-n + 7) {
            border-bottom: none; /* Remove bottom border for the last row of cells */
        }

        .calendar-cell .date-number {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }
        .today-highlight {
            background-color: #d1e0fc !important; /* สีไฮไลต์วันนี้อ่อนลง */
            color: #1a4f8a; /* สีข้อความวันนี้เข้มขึ้นเล็กน้อย */
            font-weight: 700;
            border: 2px solid #60a5fa; /* เพิ่มขอบสีฟ้า */
            border-radius: 8px; /* เพิ่มความโค้งมน */
        }
        .event-card {
            border-radius: 8px; /* เพิ่มความโค้งมน */
            padding: 6px 8px; /* ปรับ padding */
            margin: 3px 0;
            font-size: 10px; /* ขนาดฟอนต์เล็กกว่าเดิม */
            word-wrap: break-word;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08); /* เงาอ่อนลง */
            color: #333; /* สีข้อความกิจกรรม default */
        }
        /* Example Event Colors - adjust these or add new ones */
        .bg-blue-200 { background-color: #bfdbfe; } .text-blue-800 { color: #1e40af; }
        .bg-purple-200 { background-color: #e9d5ff; } .text-purple-800 { color: #6b21a8; }
        .bg-orange-200 { background-color: #fed7aa; } .text-orange-800 { color: #9a3412; }
        .bg-emerald-200 { background-color: #a7f3d0; } .text-emerald-800 { color: #065f46; }
        .emoji-event {
            font-size: 20px; /* ปรับขนาด emoji */
            text-align: center;
            padding: 4px;
        }
    </style>
</head>
<body class="flex">
    <div class="w-64 sidebar">
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

        <div class="absolute bottom-6 left-6 right-6">
            <a href="<?php echo $bottomMenuItem['link']; ?>" class="sidebar-item">
                <span class="mr-4 text-xl"><?php echo $bottomMenuItem['icon']; ?></span>
                <span class="text-sm"><?php echo $bottomMenuItem['text']; ?></span>
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="calendar-header flex justify-between items-center">
            <div class="flex items-center space-x-6">
                <h1 class="text-3xl font-bold">
                    <?php echo $months[$currentMonth] . ' ' . $currentYear; ?>
                </h1>
                <div class="flex space-x-2">
                    <button class="px-4 py-2 nav-button rounded-lg text-sm font-medium">Week</button>
                    <button class="px-4 py-2 nav-button rounded-lg text-sm font-medium">Month</button>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="?month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear; ?>"
                   class="p-3 nav-button rounded-lg">
                    ◀
                </a>
                <span class="px-4 py-2 nav-button rounded-lg font-medium">Today</span>
                <a href="?month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear; ?>"
                   class="p-3 nav-button rounded-lg">
                    ▶
                </a>
            </div>
        </div>

        <div class="calendar-grid-container">
            <div class="grid grid-cols-7 calendar-days-header">
                <?php foreach ($days as $day): ?>
                    <div class="p-4 text-center">
                        <?php echo $day; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="grid grid-cols-7">
                <?php
                $today = date('j');
                $currentDateMonth = date('n');
                $currentDateYear = date('Y');

                // เติมช่องว่างก่อนวันที่ 1
                for ($i = 0; $i < $firstDay; $i++) {
                    echo '<div class="calendar-cell"></div>';
                }

                // แสดงวันที่ในเดือน
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $isToday = ($day == $today && $currentMonth == $currentDateMonth && $currentYear == $currentDateYear);
                    $todayClass = $isToday ? 'today-highlight' : '';

                    echo '<div class="calendar-cell ' . $todayClass . '">';
                    echo '<div class="date-number">' . $day . '</div>';

                    // แสดงกิจกรรม
                    if (isset($events[$day])) {
                        $event = $events[$day];
                        if ($event['type'] == 'text') {
                            echo '<div class="event-card ' . $event['color'] . ' text-xs font-medium">';
                            echo $event['text'];
                            echo '</div>';
                        } elseif ($event['type'] == 'emoji') {
                            echo '<div class="emoji-event">';
                            echo $event['content'];
                            echo '</div>';
                        }
                    }

                    echo '</div>';
                }

                // เติมช่องว่างหลังวันสุดท้าย
                // ตรวจสอบจำนวนเซลล์ที่จำเป็นสำหรับ 6 แถวเต็ม (42 ช่อง)
                $totalDisplayedCells = $firstDay + $daysInMonth;
                $cellsToFill = 0;
                if ($totalDisplayedCells <= 35) { // 5 rows
                    $cellsToFill = 35 - $totalDisplayedCells;
                } else { // 6 rows
                    $cellsToFill = 42 - $totalDisplayedCells;
                }
                
                for ($i = 0; $i < $cellsToFill; $i++) {
                    echo '<div class="calendar-cell"></div>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>