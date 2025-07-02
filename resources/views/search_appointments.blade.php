<?php
// กำหนดข้อมูลโปรไฟล์และเมนูสำหรับ Sidebar
use Illuminate\Support\Facades\Auth;
$profileName = Auth::check() ? Auth::user()->username : "ผู้เยี่ยมชม";

$menuItems = [
    ['icon' => '🏠', 'text' => 'หน้าหลัก', 'link' => '/index'],
    ['icon' => '🔍', 'text' => 'ค้นหาการนัดหมาย', 'link' => route('appointment.search'), 'active' => true],
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
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- เพิ่มบรรทัดนี้ --}}
    <title>ค้นหาการนัดหมาย</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- ... rest of your head content ... -->

    <style>
        body {
            font-family: 'Prompt', sans-serif;
            background-color: #f0f2f5;
        }
        /* Adjusted for responsive layout */
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

        .header-section {
            display: flex;
            flex-direction: column; /* Stack on small screens */
            align-items: flex-start; /* Align items to start on small screens */
            margin-bottom: 1.5rem; /* Adjusted margin */
        }
        @media (min-width: 768px) { /* On medium screens and up */
            .header-section {
                flex-direction: row; /* Row on larger screens */
                justify-content: space-between;
                align-items: center;
                margin-bottom: 2rem;
            }
        }
        .header-section h1 {
            font-size: 1.75rem; /* Smaller title on small screens */
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem; /* Margin below title on small screens */
        }
        @media (min-width: 768px) { /* On medium screens and up */
            .header-section h1 {
                font-size: 2.25rem;
                margin-bottom: 0;
            }
        }
        .action-buttons {
            display: flex;
            flex-wrap: wrap; /* Allow buttons to wrap on small screens */
            gap: 0.75rem; /* Reduced gap for smaller screens */
        }
        @media (min-width: 768px) { /* On medium screens and up */
            .action-buttons {
                gap: 1rem;
                flex-wrap: nowrap;
            }
        }

        .new-appointment-btn,
        .export-btn,
        .clear-btn,
        .delete-btn {
            background-color: #6a5acd;
            color: white;
            padding: 0.75rem 1.25rem; /* Adjusted padding */
            border-radius: 8px;
            font-weight: 600;
            transition: background-color 0.2s;
            text-decoration: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border: none;
            cursor: pointer;
            font-size: 0.875rem; /* Adjusted font size for buttons */
        }
        .new-appointment-btn:hover,
        .export-btn:hover,
        .clear-btn:hover,
        .delete-btn:hover {
            background-color: #5b4da5;
        }
        .export-btn {
            background-color: #10B981;
        }
        .export-btn:hover {
            background-color: #059669;
        }
        .clear-btn {
            background-color: #EF4444;
        }
        .clear-btn:hover {
            background-color: #DC2626;
        }
        .delete-btn {
            background-color: #EF4444;
            padding: 0.5rem 0.8rem;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .delete-btn:hover {
            background-color: #DC2626;
        }


        .search-form {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 1.5rem; /* Reduced padding for smaller screens */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
            display: grid;
            grid-template-columns: repeat(1, 1fr); /* Default to 1 column on small screens */
            gap: 1rem; /* Reduced gap */
            align-items: end;
        }
        @media (min-width: 640px) { /* On small screens (sm) and up, 2 columns */
            .search-form {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.25rem;
            }
        }
        @media (min-width: 1024px) { /* On large screens (lg) and up, 3 columns */
            .search-form {
                grid-template-columns: repeat(3, 1fr);
                gap: 1.5rem;
            }
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-group label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #555;
            font-size: 0.9rem;
        }
        .form-group input,
        .form-group select {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            color: #333;
            transition: border-color 0.2s;
            background-color: #f8f8f8;
            height: 44px;
            width: 100%; /* Ensure inputs take full width of their grid cell */
        }
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
        }

        .appointments-table-container {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 1rem; /* Reduced padding for smaller screens */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            overflow-x: auto; /* Allows horizontal scrolling if table overflows */
        }
        @media (min-width: 768px) { /* Padding for medium screens and up */
            .appointments-table-container {
                padding: 2rem;
            }
        }

        .appointments-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 0.75rem;
            min-width: 900px; /* Set a minimum width for the table to prevent squishing on small screens */
            /* This min-width ensures horizontal scroll is available via overflow-x-auto */
        }
        /* Table headers and cells: adjust padding and hidden/display properties for responsiveness */
        .appointments-table thead th,
        .appointments-table tbody td {
            padding: 0.75rem 1rem; /* Slightly reduced padding */
            white-space: nowrap; /* Prevent text wrapping in cells by default if min-width is set */
        }
        @media (min-width: 768px) { /* Standard padding for medium screens and up */
            .appointments-table thead th,
            .appointments-table tbody td {
                padding: 1rem 1.25rem;
            }
        }

        .appointments-table thead th {
            background-color: #e0e6f0;
            color: #555;
            font-weight: 600;
            text-align: left;
            border-bottom: 1px solid #d0d0d0;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }
        .appointments-table tbody tr {
            background-color: #f8f8f8;
            border-radius: 10px;
            transition: background-color 0.2s;
        }
        .appointments-table tbody tr:hover {
            background-color: #f0f0f0;
        }
        .appointments-table tbody td {
            color: #333;
            font-size: 0.9rem;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
        }
        .appointments-table tbody td:first-child {
            border-left: 1px solid #eee;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }
        .appointments-table tbody td:last-child {
            border-right: 1px solid #eee;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 0.4em 0.8em;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-align: center;
            white-space: nowrap;
        }
        .bg-green-100 { background-color: #dcfce7; } .text-green-700 { color: #15803d; }
        .bg-yellow-100 { background-color: #fef9c3; } .text-yellow-700 { color: #a16207; }
        .bg-red-100 { background-color: #fee2e2; } .text-red-700 { color: #b91c1c; }
        .bg-gray-200 { background-color: #e5e7eb; } .text-gray-700 { color: #374151; }
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

    <div class="main-content flex-1"> {{-- Adjusted for responsive main content --}}
        <div class="header-section">
            <h1>ค้นหาการนัดหมาย</h1>
            <div class="action-buttons">
                <a href="/create_appointment" class="new-appointment-btn">
                    สร้างใหม่
                </a>
                <button type="button" id="exportExcelBtn" class="export-btn">
                    Export Excel
                </button>
                @if(Auth::check() && Auth::user()->is_admin)
                <button type="button" id="clearBtn" class="clear-btn">
                    Clear
                </button>
                @endif
            </div>
        </div>

        {{-- เพิ่ม form สำหรับการค้นหา --}}
        <form action="{{ route('appointment.search') }}" method="GET" class="search-form" id="searchForm">
            <div class="form-group">
                <label for="search_plan_name">ชื่อโปรเจค</label>
                <input type="text" id="search_plan_name" name="search_plan_name" placeholder="ชื่อโปรเจค" value="{{ request('search_plan_name') }}">
            </div>
            <div class="form-group">
                <label for="search_task_name">ชื่องาน</label>
                <input type="text" id="search_task_name" name="search_task_name" placeholder="ชื่องาน" value="{{ request('search_task_name') }}">
            </div>
            <div class="form-group">
                <label for="search_contract_number">เลขที่สัญญา</label>
                <input type="text" id="search_contract_number" name="search_contract_number" placeholder="เลขที่สัญญา" value="{{ request('search_contract_number') }}">
            </div>
            <div class="form-group">
                <label for="search_department">หน่วยงาน</label>
                <input type="text" id="search_department" name="search_department" placeholder="หน่วยงาน" value="{{ request('search_department') }}">
            </div>
            <div class="form-group">
                <label for="search_supervisor">ผู้คุมงาน</label>
                <input type="text" id="search_supervisor" name="search_supervisor" placeholder="ชื่อผู้คุมงาน" value="{{ request('search_supervisor') }}">
            </div>

            <div class="form-group">
                <label for="search_name">ชื่อผู้จอง</label>
                <input type="text" id="search_name" name="search_name" placeholder="ชื่อผู้ติดต่อ" value="{{ request('search_name') }}">
            </div>
            <div class="form-group">
                <label for="search_location">สถานที่</label>
                <input type="text" id="search_location" name="search_location" placeholder="ห้องประชุม / ร้านกาแฟ / ร.พ." value="{{ request('search_location') }}">
            </div>
            <div class="form-group">
                <label for="search_status">สถานะนัดหมาย</label>
                <select id="search_status" name="search_status">
                    <option value="">เลือก / ตั้งค่าสถานะการดำเนินการ</option>
                    <option value="pending" {{ request('search_status') == 'pending' ? 'selected' : '' }}>รอดำเนินการ</option>
                    <option value="confirmed" {{ request('search_status') == 'confirmed' ? 'selected' : '' }}>ยืนยันแล้ว</option>
                    <option value="completed" {{ request('search_status') == 'completed' ? 'selected' : '' }}>เสร็จสิ้น</option>
                    <option value="in_progress" {{ request('search_status') == 'in_progress' ? 'selected' : '' }}>อยู่ระหว่างดำเนินการ</option>
                    <option value="not_started" {{ request('search_status') == 'not_started' ? 'selected' : '' }}>ยังไม่เริ่มดำเนินการ</option>
                    <option value="cancelled" {{ request('search_status') == 'cancelled' ? 'selected' : '' }}>ยกเลิกแล้ว</option>
                </select>
            </div>
            <div class="form-group">
                <label for="search_date">ค้นหาวันที่</label>
                <input type="date" id="search_date" name="search_date" placeholder="วัน/เดือน/ปี" value="{{ request('search_date') }}">
            </div>
            <div class="form-group">
                <label for="start_date">ตั้งแต่วันที่</label>
                <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}">
            </div>
            <div class="form-group">
                <label for="end_date">ถึงวันที่</label>
                <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}">
            </div>
            <div class="form-group">
                <label for="search_time">เวลา</label>
                <input type="time" id="search_time" name="search_time" value="{{ request('search_time') }}">
            </div>
            {{-- เพิ่มปุ่ม Submit สำหรับ Form ค้นหา --}}
            <div class="form-group">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200" style="height: 44px;">
                    ค้นหา
                </button>
            </div>
            <div></div> {{-- ช่องว่างให้ layout สวยงาม --}}
            <div></div> {{-- ช่องว่างให้ layout สวยงาม --}}
        </form>

        <div class="appointments-table-container">
            <table class="appointments-table">
                <thead>
                    <tr>
                        <th class="px-4 py-2">ชื่อแผน</th>
                        <th class="px-4 py-2">ชื่องาน</th>
                        <th class="px-4 py-2 hidden sm:table-cell">เลขที่สัญญา</th>
                        <th class="px-4 py-2 hidden md:table-cell">หน่วยงาน</th>
                        <th class="px-4 py-2 hidden lg:table-cell">ผู้คุมงาน</th>
                        <th class="px-4 py-2">ชื่อผู้จอง</th>
                        <th class="px-4 py-2 hidden sm:table-cell">รายละเอียด</th>
                        <th class="px-4 py-2 hidden md:table-cell">สถานที่</th>
                        <th class="px-4 py-2">วันที่ / เวลา</th>
                        <th class="px-4 py-2">สถานะ</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->plan_name }}</td>
                            <td>{{ $appointment->task_name }}</td>
                            <td class="hidden sm:table-cell">{{ $appointment->contract_number }}</td>
                            <td class="hidden md:table-cell">{{ $appointment->department }}</td>
                            <td class="hidden lg:table-cell">{{ $appointment->supervisor }}</td>
                            <td>{{ $appointment->name }}</td>
                            <td class="hidden sm:table-cell">{{ $appointment->details }}</td>
                            <td class="hidden md:table-cell">{{ $appointment->location }}</td>
                            <td>
                                {{ !empty($appointment->start_date) ? \Carbon\Carbon::parse($appointment->start_date)->format('j F') : '' }}
                                @if(!empty($appointment->end_date) && $appointment->start_date != $appointment->end_date)
                                    - {{ \Carbon\Carbon::parse($appointment->end_date)->format('j F') }}
                                @endif
                                <br>
                                {{ !empty($appointment->time) ? \Carbon\Carbon::parse($appointment->time)->format('H:i') : '' }}
                            </td>
                            <td>
                                <span class="status-badge {{ $appointment->status_color }}">
                                    {{ $appointment->status }}
                                </span>
                            </td>
                            <td>
                                @if(Auth::check() && Auth::user()->is_admin)
                                <form action="{{ route('appointment.destroy', $appointment->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="delete-button">ลบ</button>
                                </form>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-8 text-gray-500">ไม่มีข้อมูลการนัดหมายที่ตรงกับการค้นหา</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
        </div>
    </div>

   <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.getElementById('searchForm');
        const exportExcelBtn = document.getElementById('exportExcelBtn');
        const clearBtn = document.getElementById('clearBtn');
        const appointmentsTableContainer = document.querySelector('.appointments-table-container');

        // Event Listener สำหรับปุ่ม Export Excel
        exportExcelBtn.addEventListener('click', function() {
            const params = new URLSearchParams(new FormData(searchForm)).toString();
            window.location.href = "{{ route('appointment.export.excel') }}" + '?' + params;
        });

        // Event Listener สำหรับปุ่ม Clear (ทำงานแบบ Clear All / Reset Table)
        clearBtn.addEventListener('click', function() {
            searchForm.querySelectorAll('input, select').forEach(element => {
                if (element.type === 'text' || element.type === 'date' || element.type === 'time') {
                    element.value = '';
                } else if (element.tagName === 'SELECT') {
                    element.value = '';
                }
            });
            window.location.href = "{{ route('appointment.search') }}";
        });

        // **ปรับปรุง Event Listener สำหรับปุ่ม "ลบ" ด้วย AJAX (fetch API)**
        if (appointmentsTableContainer) {
            appointmentsTableContainer.addEventListener('click', function(event) {
                if (event.target && event.target.matches('.delete-button')) {
                    event.preventDefault(); // ป้องกันการกระทำเริ่มต้นของปุ่ม

                    const form = event.target.closest('form');
                    const actionUrl = form.action; // URL สำหรับลบ
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // ดึง CSRF Token

                    if (confirm('คุณแน่ใจหรือไม่ที่ต้องการลบการนัดหมายนี้?')) {
                        // ส่ง Request แบบ AJAX
                        fetch(actionUrl, {
                            method: 'POST', // ต้องเป็น POST เพื่อให้ @method('DELETE') ทำงาน
                            headers: {
                                'X-CSRF-TOKEN': csrfToken, // ส่ง CSRF Token ใน Header
                                'Content-Type': 'application/x-www-form-urlencoded' // หรือ application/json ถ้าส่งเป็น JSON
                            },
                            // ส่ง _method=DELETE เพื่อให้ Laravel รู้ว่าเป็น DELETE request
                            body: new URLSearchParams({
                                _method: 'DELETE'
                            }).toString()
                        })
                        .then(response => {
                            // ตรวจสอบว่า Request สำเร็จหรือไม่ (เช่น status 200 OK หรือ 302 Redirect)
                            if (response.ok || response.redirected) {
                                // หากสำเร็จ ให้รีเฟรชหน้า
                                window.location.reload();
                                // หรือถ้า Controller ส่ง redirect response กลับมา
                                // window.location.href = response.url;
                            } else {
                                // หากมี Error จาก Server
                                console.error('Error deleting appointment:', response.status, response.statusText);
                                alert('เกิดข้อผิดพลาดในการลบการนัดหมาย!');
                            }
                        })
                        .catch(error => {
                            console.error('Network error or unexpected issue:', error);
                            alert('เกิดข้อผิดพลาดในการเชื่อมต่อ!');
                        });
                    }
                }
            });
        }
    });
</script>
</body>
</html>
