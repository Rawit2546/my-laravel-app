<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AppointmentsExport;
use Carbon\Carbon;


class AppointmentController extends Controller
{
    public function index()
    {
        // กำหนดเดือนและปีสำหรับปฏิทินหลัก
        $currentMonth = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
        $currentYear = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
        if ($currentMonth < 1) { $currentMonth = 12; $currentYear--; } elseif ($currentMonth > 12) { $currentMonth = 1; $currentYear++; }
        $months = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
        $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $firstDay = date('w', mktime(0, 0, 0, $currentMonth, 1, $currentYear));
        $daysInMonth = date('t', mktime(0, 0, 0, $currentMonth, 1, $currentYear));
        
        // ดึงข้อมูลการนัดหมายสำหรับเดือน/ปีปัจจุบันเพื่อแสดงในปฏิทิน
        $appointmentsForCalendar = Appointment::whereYear('start_date', $currentYear)
                                    ->whereMonth('start_date', $currentMonth)
                                    ->orderBy('start_date')
                                    ->orderBy('time')
                                    ->get();

        // จัดเรียงข้อมูลการนัดหมายตามวันในเดือนเพื่อเตรียมส่งไปแสดงผลในปฏิทิน
        $events = [];
        foreach ($appointmentsForCalendar as $appointment) {
            $dayOfMonth = Carbon::parse($appointment->start_date)->day;
            if (!isset($events[$dayOfMonth])) {
                $events[$dayOfMonth] = [];
            }
            $eventData = [
                'plan_name' => $appointment->plan_name,
                'task_name' => $appointment->task_name,
                'time' => $appointment->time ? Carbon::parse($appointment->time)->format('H:i') : '',
                'status_color' => '',
                'status' => $appointment->status
            ];
            // กำหนดสีสถานะสำหรับแสดงในปฏิทิน
            switch ($appointment->status) {
                case 'pending': $eventData['status_color'] = 'bg-yellow-200 text-yellow-800'; break;
                case 'confirmed': $eventData['status_color'] = 'bg-green-200 text-green-800'; break;
                case 'cancelled': $eventData['status_color'] = 'bg-red-200 text-red-800'; break;
                case 'completed': $eventData['status_color'] = 'bg-blue-200 text-blue-800'; break; // ตัวอย่าง
                case 'in_progress': $eventData['status_color'] = 'bg-purple-200 text-purple-800'; break; // ตัวอย่าง
                case 'not_started': $eventData['status_color'] = 'bg-gray-200 text-gray-800'; break; // ตัวอย่าง
                default: $eventData['status_color'] = 'bg-gray-200 text-gray-800'; break;
            }
            $events[$dayOfMonth][] = $eventData;
        }


        // URL สำหรับเปลี่ยนเดือน
        $prevMonth = $currentMonth - 1; $nextMonth = $currentMonth + 1; $prevYear = $currentYear; $nextYear = $currentYear;
        if ($prevMonth < 1) { $prevMonth = 12; $prevYear--; }
        if ($nextMonth > 12) { $nextMonth = 1; $nextYear++; }

        // โค้ดสำหรับ Mini Calendar (สำหรับ create_appointment.blade.php)
        // เนื่องจาก index.blade.php ไม่ได้ใช้ mini calendar นี้โดยตรง
        // ตัวแปรเหล่านี้จึงไม่จำเป็นต้องส่งไปที่ index.blade.php จาก method นี้
        // แต่ถ้าคุณต้องการให้ Calendar Header ของ index.blade.php แสดงเดือน/ปี
        // หรือมีส่วนอื่นๆ ที่ใช้ mini calendar ใน index.blade.php
        // คุณต้องส่งตัวแปรเหล่านี้ไปด้วย
        $miniCalendarMonth = 'October'; // ตัวอย่างค่า
        $miniCalendarYear = '2020'; // ตัวอย่างค่า
        $miniCalendarDays = ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'];
        $miniCalendarDates = [
            null, null, null, null, 1, 2, 3,
            4, 5, 6, 7, 8, 9, 10,
            11, 12, 13, 14, 15, 16, 17,
            18, 19, 20, 21, 22, 23, 24,
            25, 26, 27, 28, 29, 30, 31
        ];
        $highlightedDay = 9;


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

        // ส่งตัวแปรทั้งหมดที่จำเป็นสำหรับ index.blade.php (รวมถึง events)
        return view('index', compact('currentMonth', 'currentYear', 'months', 'days', 'firstDay', 'daysInMonth', 'events', 'prevMonth', 'nextMonth', 'prevYear', 'nextYear', 'profileName', 'menuItems'));
    }

    public function create()
    {
        // โค้ดสำหรับ Mini Calendar (สำหรับ create_appointment.blade.php)
        $miniCalendarMonth = 'October';
        $miniCalendarYear = '2020';
        $miniCalendarDays = ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'];
        $miniCalendarDates = [
            null, null, null, null, 1, 2, 3,
            4, 5, 6, 7, 8, 9, 10,
            11, 12, 13, 14, 15, 16, 17,
            18, 19, 20, 21, 22, 23, 24,
            25, 26, 27, 28, 29, 30, 31
        ];
        $highlightedDay = 9;

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

        return view('create_appointment', compact('miniCalendarMonth', 'miniCalendarYear', 'miniCalendarDays', 'miniCalendarDates', 'highlightedDay', 'profileName', 'menuItems'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'plan_name' => 'nullable|string|max:255',
            'task_name' => 'nullable|string|max:255',
            'contract_number' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'supervisor' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'time' => 'nullable|date_format:H:i',
            'status' => 'nullable|string|max:50',
            'details' => 'nullable|string',
            'name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);
        Appointment::create($validatedData);
        return redirect()->route('appointment.search')->with('success', 'สร้างการนัดหมายสำเร็จ!');
    }


    public function search(Request $request)
    {
        $query = Appointment::query();

        // ตรวจสอบว่ามีการส่ง query parameters ที่เกี่ยวข้องกับการค้นหามาหรือไม่
        $hasSearchParameters = $request->filled('search_plan_name') ||
                               $request->filled('search_task_name') ||
                               $request->filled('search_contract_number') ||
                               $request->filled('search_department') ||
                               $request->filled('search_supervisor') ||
                               $request->filled('search_name') ||
                               $request->filled('search_location') ||
                               $request->filled('search_status') ||
                               $request->filled('search_date') ||
                               $request->filled('start_date') ||
                               $request->filled('end_date') ||
                               $request->filled('search_time');

        // Logic เดิม: หากมีการเรียกให้ "reset_table" ตารางจะว่างเปล่า
        if ($request->has('reset_table') && $request->input('reset_table') === 'true') {
            $appointments = collect();
        }
        // ปรับปรุง Logic นี้: หากไม่มี search parameters เลย ให้ดึงข้อมูลทั้งหมด
        // มิฉะนั้น (ถ้ามี search parameters) ให้ทำการค้นหาตามเงื่อนไข
        else { 
            if ($hasSearchParameters) { // ถ้ามีการกรอกเงื่อนไขค้นหาจริงๆ
                if ($request->filled('search_plan_name')) {
                    $query->where('plan_name', 'like', '%' . $request->input('search_plan_name') . '%');
                }
                if ($request->filled('search_task_name')) {
                    $query->where('task_name', 'like', '%' . $request->input('search_task_name') . '%');
                }
                if ($request->filled('search_contract_number')) {
                    $query->where('contract_number', 'like', '%' . $request->input('search_contract_number') . '%');
                }
                if ($request->filled('search_department')) {
                    $query->where('department', 'like', '%' . $request->input('search_department') . '%');
                }
                if ($request->filled('search_supervisor')) {
                    $query->where('supervisor', 'like', '%' . $request->input('search_supervisor') . '%');
                }
                if ($request->filled('search_name')) {
                    $query->where('name', 'like', '%' . $request->input('search_name') . '%');
                }
                if ($request->filled('search_location')) {
                    $query->where('location', 'like', '%' . $request->input('search_location') . '%');
                }
                if ($request->filled('search_status')) {
                    $query->where('status', $request->input('search_status'));
                }
                if ($request->filled('search_date')) {
                    $query->where(function($q) use ($request) {
                        $q->whereDate('start_date', $request->input('search_date'))
                          ->orWhereDate('end_date', $request->input('search_date'));
                    });
                }
                if ($request->filled('start_date')) {
                    $query->whereDate('start_date', '>=', $request->input('start_date'));
                }
                if ($request->filled('end_date')) {
                    $query->whereDate('end_date', '<=', $request->input('end_date'));
                }
                if ($request->filled('search_time')) {
                    $query->where('time', $request->input('search_time'));
                }
            }
            // หากไม่มี search parameters จริงๆ (hasSearchParameters เป็น false)
            // หรือมี search parameters ก็ตาม ก็จะดึงข้อมูลที่ตรงเงื่อนไขจาก $query
            $appointments = $query->orderBy('start_date', 'asc')->orderBy('time', 'asc')->get();
        }


        // แปลงข้อมูลวันที่/เวลา และกำหนดสีสถานะ (ทำเหมือนเดิม)
        foreach ($appointments as $key => $appointment) {
            $formatted_date = '';
            if (!empty($appointment->start_date)) {
                $start_date_obj = new \DateTime($appointment->start_date);
                $formatted_date = $start_date_obj->format('j F');
            }
            if (!empty($appointment->end_date) && $appointment->start_date != $appointment->end_date) {
                $end_date_obj = new \DateTime($appointment->end_date);
                $formatted_date .= ' - ' . $end_date_obj->format('j F');
            }
            $appointments[$key]['date'] = $formatted_date;

            $status_color = '';
            switch ($appointment->status) {
                case 'pending': $status_color = 'bg-yellow-100 text-yellow-700'; break;
                case 'confirmed': $status_color = 'bg-green-100 text-green-700'; break;
                case 'cancelled': $status_color = 'bg-red-100 text-red-700'; break;
                case 'completed': $status_color = 'bg-green-100 text-green-700'; break;
                case 'in_progress': $status_color = 'bg-yellow-100 text-yellow-700'; break;
                case 'not_started': $status_color = 'bg-gray-200 text-gray-700'; break;
                default: $status_color = 'bg-gray-200 text-gray-700'; break;
            }
            $appointments[$key]['status_color'] = $status_color;
        }

        $profileName = Auth::check() ? Auth::user()->username : "ผู้เยี่ยมชม";
        $menuItems = [
            ['icon' => '🏠', 'text' => 'หน้าหลัก', 'link' => route('app.index')],
            ['icon' => '🔍', 'text' => 'ค้นหาการนัดหมาย', 'link' => route('appointment.search'), 'active' => true],
            ['icon' => '➕', 'text' => 'สร้างการนัดหมายใหม่', 'link' => route('appointment.create')],
            ['icon' => '✏️', 'text' => 'แก้ไขการนัดหมาย', 'link' => '#'],
            ['icon' => '🖨️', 'text' => 'พิมพ์', 'link' => '#'],
            ['icon' => '⚙️', 'text' => 'การตั้งค่า', 'link' => '#'],
            ['icon' => '📄', 'text' => 'ออกจากระบบ', 'link' => route('logout')],
        ];

        return view('search_appointments', compact('appointments', 'profileName', 'menuItems'));
    }

    // เพิ่ม method สำหรับ Export Excel
    public function exportExcel(Request $request)
    {
        $query = Appointment::query();

        // นำเงื่อนไขการค้นหาทั้งหมดมาใช้กับการ Export ด้วย
        // นี่คือการรับ parameter จาก query string ของ URL เมื่อกดปุ่ม Export
        if ($request->filled('search_plan_name')) {
            $query->where('plan_name', 'like', '%' . $request->input('search_plan_name') . '%');
        }
        if ($request->filled('search_task_name')) {
            $query->where('task_name', 'like', '%' . $request->input('search_task_name') . '%');
        }
        if ($request->filled('search_contract_number')) {
            $query->where('contract_number', 'like', '%' . $request->input('search_contract_number') . '%');
        }
        if ($request->filled('search_department')) {
            $query->where('department', 'like', '%' . $request->input('search_department') . '%');
        }
        if ($request->filled('search_supervisor')) {
            $query->where('supervisor', 'like', '%' . $request->input('search_supervisor') . '%');
        }
        if ($request->filled('search_name')) {
            $query->where('name', 'like', '%' . $request->input('search_name') . '%');
        }
        if ($request->filled('search_location')) {
            $query->where('location', 'like', '%' . $request->input('search_location') . '%');
        }
        if ($request->filled('search_status')) {
            $query->where('status', $request->input('search_status'));
        }
        if ($request->filled('search_date')) {
            $query->whereDate('start_date', $request->input('search_date'))
                  ->orWhereDate('end_date', $request->input('search_date'));
        }
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->input('end_date'));
        }
        if ($request->filled('search_time')) {
            $query->where('time', $request->input('search_time'));
        }

        $appointmentsToExport = $query->orderBy('start_date', 'asc')->orderBy('time', 'asc')->get();

        $fileName = 'appointments_' . Carbon::now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new AppointmentsExport($appointmentsToExport), $fileName);
    }
    public function destroy($id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return redirect()->route('appointment.search')->with('error', 'ไม่พบการนัดหมายที่ต้องการลบ!');
        }

        try {
            $appointment->delete();
            return redirect()->route('appointment.search')->with('success', 'ลบการนัดหมายสำเร็จแล้ว!');
        } catch (\Exception $e) {
            // Log the error for debugging: Log::error($e->getMessage());
            return redirect()->route('appointment.search')->with('error', 'เกิดข้อผิดพลาดในการลบการนัดหมาย: ' . $e->getMessage());
        }
    }
}
