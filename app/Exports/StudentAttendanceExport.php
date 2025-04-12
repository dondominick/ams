<?php

namespace App\Exports;

use App\Models\StudentAttendance;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentAttendanceExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $students;

    public function collection()
    {
        return $this->students;
    }

    public function setCollection($request)
    {
        $this->students = $request;
    }

    public function headings(): array
    {
        return ["Student ID", "Last Name", "First Name", "Program", "Set", "Year Level", "Check In", "Check Out", "Event Name", "Date"];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4CAF50']
                ]
            ] // Make headers bold
        ];
    }
}
