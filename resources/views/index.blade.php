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

// ข้อมูลตัวอย่างกิจกรรม (จะถูกดึงมาจาก Controller แล้ว)
// $events = []; // บรรทัดนี้จะไม่มีแล้ว เพราะ Controller ส่งมาให้

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
use Illuminate\Support\Facades\Auth;
$profileName = Auth::check() ? Auth::user()->username : "ผู้เยี่ยมชม";

$menuItems = [
    ['icon' => '🏠', 'text' => 'หน้าหลัก', 'link' => '/index', 'active' => true],
    ['icon' => '🔍', 'text' => 'ค้นหาการนัดหมาย', 'link' => '/search_appointments'],
    ['icon' => '➕', 'text' => 'สร้างการนัดหมายใหม่', 'link' => '/create_appointment'],
    ['icon' => '✏️', 'text' => 'แก้ไขการนัดหมาย', 'link' => '#'],
    ['icon' => '🖨️', 'text' => 'พิมพ์', 'link' => '#'],
    ['icon' => '⚙️', 'text' => 'การตั้งค่า', 'link' => '#'],
    ['icon' => '📄', 'text' => 'ออกจากระบบ', 'link' => '/logout'],
];

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
            background-color: #f0f2f5;
        }
        /* Sidebar Styles - Updated to match the new appointment page theme */
        .sidebar {
            background: linear-gradient(180deg, #b0c4de 0%, #a0b2d0 100%);
            min-height: 100vh;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        /* Adjustments for responsive sidebar: hidden on small screens, fixed width on medium+ */
        @media (max-width: 767px) { /* On small screens (mobile) */
            .sidebar {
                display: none;
            }
        }
        @media (min-width: 768px) { /* On medium screens (tablet) and larger */
            .sidebar {
                width: 16rem;
                display: flex;
                flex-shrink: 0;
            }
        }

        .sidebar-item {
            transition: all 0.3s ease;
            border-radius: 12px;
            color: #333;
            padding: 1rem;
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        .sidebar-item span {
            font-size: 1rem; /* Adjusted for consistency */
        }
        .sidebar-item:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: translateX(4px);
        }
        .sidebar-item.active {
            background-color: rgba(255, 255, 255, 0.5);
            font-weight: 500;
        }
        /* Profile Section Styles - Updated to match the new appointment page theme */
        .profile-section {
            background-color: rgba(255, 255, 255, 0.5);
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
            font-size: 1.1rem; /* Adjusted for consistency */
        }

        /* Calendar Specific Styles - Updated for the new theme */
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
        .calendar-header {
            background: linear-gradient(90deg, #8b5cf6 0%, #a78bfa 100%); /* เปลี่ยนสี Header Calendar เป็นโทนม่วง */
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }
        .calendar-header h1 {
            color: white;
        }
        .nav-button {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            backdrop-filter: blur(5px);
            color: white;
            transition: background-color 0.2s, transform 0.2s;
        }
        .nav-button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        .calendar-grid-container {
            background-color: #ffffff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            border: 1px solid #e0e0e0;
        }
        .calendar-days-header {
            background-color: #e0e6f0;
            color: #555;
            font-weight: 600;
            padding: 0.75rem 0;
            border-bottom: 1px solid #d0d0d0;
        }
        .calendar-cell {
            min-height: 110px; /* เพิ่มความสูงขั้นต่ำของช่องวัน */
            transition: all 0.2s ease;
            background-color: #ffffff;
            border-right: 1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            padding: 0.75rem;
            position: relative;
        }
        .calendar-cell:hover {
            background-color: #f5f5f5;
        }
        .calendar-cell:nth-child(7n) {
            border-right: none;
        }
        .calendar-grid-container .grid-cols-7 > div:nth-last-child(-n + 7) {
            border-bottom: none;
        }

        .calendar-cell .date-number {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }
        .today-highlight {
            background-color: #d1e0fc !important;
            color: #1a4f8a;
            font-weight: 700;
            border: 2px solid #60a5fa;
            border-radius: 8px;
        }
        .event-card {
            border-radius: 8px;
            padding: 6px 8px;
            margin: 3px 0;
            font-size: 10px; /* ขนาดฟอนต์เล็กสำหรับกิจกรรม */
            word-wrap: break-word;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            color: #333;
        }
        /* Event Colors (ใช้ใน Controller เพื่อกำหนดสี) */
        .bg-blue-200 { background-color: #bfdbfe; } .text-blue-800 { color: #1e40af; }
        .bg-purple-200 { background-color: #e9d5ff; } .text-purple-800 { color: #6b21a8; }
        .bg-orange-200 { background-color: #fed7aa; } .text-orange-800 { color: #9a3412; }
        .bg-emerald-200 { background-color: #a7f3d0; } .text-emerald-800 { color: #065f46; }
        /* สีเพิ่มเติมสำหรับสถานะ */
        .bg-yellow-200 { background-color: #fef9c3; } .text-yellow-800 { color: #854d09; } /* pending */
        .bg-red-200 { background-color: #fee2e2; } .text-red-800 { color: #991b1b; } /* cancelled */
        .bg-gray-200 { background-color: #e5e7eb; } .text-gray-800 { color: #374151; } /* not_started */

        .emoji-event {
            font-size: 20px;
            text-align: center;
            padding: 4px;
        }
    </style>
</head>
<body class="flex flex-col md:flex-row min-h-screen">
    <div class="w-full md:w-64 sidebar hidden sm:flex flex-col">
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

    <div class="main-content flex-1">
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

                    // **แสดงกิจกรรมจากการนัดหมายที่ดึงมา**
                    if (isset($events[$day]) && is_array($events[$day])) {
                        foreach ($events[$day] as $event) {
                            echo '<div class="event-card ' . htmlspecialchars($event['status_color']) . ' text-xs font-medium">';
                            echo htmlspecialchars($event['plan_name']) . (isset($event['task_name']) ? ': ' . htmlspecialchars($event['task_name']) : '');
                            if (!empty($event['time'])) {
                                echo ' (' . htmlspecialchars($event['time']) . ')';
                            }
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