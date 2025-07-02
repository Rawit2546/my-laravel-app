<?php

namespace App\Exports;

use App\Models\Appointment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class AppointmentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $appointments;

    public function __construct(Collection $appointments)
    {
        $this->appointments = $appointments;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // ใช้ collection ที่ส่งเข้ามาจาก Controller
        return $this->appointments;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'ชื่อแผน',
            'ชื่องาน',
            'เลขที่สัญญา',
            'หน่วยงาน',
            'ผู้คุมงาน',
            'ชื่อผู้จอง',
            'สถานที่',
            'วันที่เริ่ม',
            'วันที่สิ้นสุด',
            'เวลา',
            'สถานะ',
            'รายละเอียด',
            'สร้างเมื่อ',
            'อัปเดตเมื่อ',
        ];
    }

    /**
     * @var Appointment $appointment
     * @return array
     */
    public function map($appointment): array
    {
        // ปรับแต่งข้อมูลที่จะ Export ให้ตรงกับหัวตาราง
        return [
            $appointment->id,
            $appointment->plan_name,
            $appointment->task_name,
            $appointment->contract_number,
            $appointment->department,
            $appointment->supervisor,
            $appointment->name,
            $appointment->location,
            $appointment->start_date,
            $appointment->end_date,
            $appointment->time ? Carbon::parse($appointment->time)->format('H:i') : '',
            $appointment->status,
            $appointment->details,
            $appointment->created_at,
            $appointment->updated_at,
        ];
    }
}