<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Fine;
use App\Models\FineSettings;
use App\Models\Student;
use App\Models\StudentAttendance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentAttendanceController extends Controller
{
    public function view()
    {
        date_default_timezone_set('Asia/Manila');
        $time = $time = date("H:i");
        $event = Event::where('date', '=', date('Y-m-d'))
            ->orderBy('created_at', 'desc')
            ->get()
            ->first();

        if ($event) {
            $this->processAbsentStudents($event, $time);
        }

        $pending = Event::where('date', '=', date('Y-m-d'))
            ->where(function (Builder $query) {
                $query->orWhere(function (Builder $query) {
                    $time = $time = date("H:i");
                    $query->where('checkIn_start', '<', $time)
                        ->where('checkIn_end', '>', $time);
                })
                    ->orWhere(function (Builder $query) {
                        $time = $time = date("H:i");
                        $query->where('checkOut_start', '<', $time)
                            ->where('checkOut_end', '>', $time);
                    });
            })
            ->get();



        if (empty($event)) {
            $event = null;
            return view('pages.attendance', compact('event'));
        }
        if ($time > $event->checkOut_end || $time < $event->checkIn_start || ($time > $event->checkIn_end && $time < $event->checkOut_start)) {
            $event = null;
        }


        if (empty($pending->first())) {
            $pending = null;
        }
        $students = $this->recent();
        return view('pages.attendance', compact('event', 'students', 'pending'));
    }

    protected function processAbsentStudents($event, $currentTime)
    {
        $settings = FineSettings::firstOrCreate(
            ['id' => 1],
            [
                'fine_amount' => 25.00,
                'morning_checkin' => true,
                'morning_checkout' => true,
                'afternoon_checkin' => true,
                'afternoon_checkout' => true
            ]
        );

        $allStudents = Student::all();
        
        foreach ($allStudents as $student) {
            $attendance = StudentAttendance::where('student_rfid', $student->s_rfid)
                ->where('event_id', $event->id)
                ->first();

            $fine = Fine::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'event_id' => $event->id
                ],
                [
                    'absences' => 0,
                    'fine_amount' => $settings->fine_amount,
                    'total_fines' => 0,
                    'morning_checkin' => false,
                    'morning_checkout' => false,
                    'afternoon_checkin' => false,
                    'afternoon_checkout' => false
                ]
            );

            // Calculate missing periods
            if ($currentTime > $event->checkIn_end && !($attendance && $attendance->attend_checkIn)) {
                $fine->morning_checkin = false;
                $fine->absences += 1;
            }

            if ($currentTime > $event->checkOut_end && !($attendance && $attendance->attend_checkOut)) {
                $fine->morning_checkout = false;
                $fine->absences += 1;
            }

            if ($currentTime > $event->afternoon_checkIn_end && !($attendance && $attendance->attend_afternoon_checkIn)) {
                $fine->afternoon_checkin = false;
                $fine->absences += 1;
            }

            if ($currentTime > $event->afternoon_checkOut_end && !($attendance && $attendance->attend_afternoon_checkOut)) {
                $fine->afternoon_checkout = false;
                $fine->absences += 1;
            }

            // Update total fines
            $fine->total_fines = $fine->absences * $settings->fine_amount;
            $fine->save();
        }
    }

    public function recordAttendance(Request $request)
    {
        // FIRST VALIDATE REQUEST FORM
        $fields = $request->validate([
            "s_rfid" => ['required'],
        ]);

        // CHECK IF STUDENT EXIST IN THE MASTERLIST
        if (empty(Student::whereAny(['s_rfid', 's_studentID'], $request->s_rfid)->get()->first())) {
            return response()->json([
                "message" => "I am sorry but the student does not exist in the masterlist",
                "isRecorded" => false,
                "doesntExist" => true,
            ]);
        }


        // INITIALIZE VARIABLES, ETC
        date_default_timezone_set('Asia/Manila');
        $time = date("H:i");
        $currentTimestamp = now();
        $currentTime = date('H:i:s');

        $event = Event::find($request->event_id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->first();

        $student = StudentAttendance::where('student_rfid', $request->s_rfid)
            ->where('event_id', $request->event_id)
            ->get()
            ->first();

        // DETERMINE IF IT IS ALREADY PAST THE SET CHECK IN AND CHECK OUT TIME
        if ($time < $event->checkIn_start || ($time > $event->checkIn_end && $time < $event->checkOut_start) || $time > $event->checkOut_end) {
            return response()->json([
                "message" => "I am sorry, but your attendance can only be recorded during the set time frame",
                "isRecorded" => false
            ]);
        }

        $currentTime = date('H:i');
        $attendance = StudentAttendance::firstOrNew([
            'student_rfid' => $request->s_rfid,
            'event_id' => $request->event_id
        ]);

        // Morning attendance
        if ($time > $event->checkIn_start && $time < $event->checkIn_end) {
            $attendance->attend_checkIn = $currentTime;
            $attendance->morning_attendance = true;
            $attendance->save();
            
            // Update fine record if exists
            Fine::where('student_id', $student->id)
                ->where('event_id', $event->id)
                ->update([
                    'morning_checkin' => true,
                    'absences' => DB::raw('absences - 1'),
                    'total_fines' => DB::raw('total_fines - 25')
                ]);
        }

        if ($time > $event->checkOut_start && $time < $event->checkOut_end) {
            $attendance->attend_checkOut = $currentTime;
            $attendance->morning_attendance = true;
            $attendance->save();
            
            // Update fine record if exists
            Fine::where('student_id', $student->id)
                ->where('event_id', $event->id)
                ->update([
                    'morning_checkout' => true,
                    'absences' => DB::raw('absences - 1'),
                    'total_fines' => DB::raw('total_fines - 25')
                ]);
        }

        // Afternoon attendance
        if ($time > $event->afternoon_checkIn_start && $time < $event->afternoon_checkIn_end) {
            $attendance->attend_afternoon_checkIn = $currentTime;
            $attendance->afternoon_attendance = true;
            $attendance->save();
            
            // Update fine record if exists
            Fine::where('student_id', $student->id)
                ->where('event_id', $event->id)
                ->update([
                    'afternoon_checkin' => true,
                    'absences' => DB::raw('absences - 1'),
                    'total_fines' => DB::raw('total_fines - 25')
                ]);
        }

        if ($time > $event->afternoon_checkOut_start && $time < $event->afternoon_checkOut_end) {
            $attendance->attend_afternoon_checkOut = $currentTime;
            $attendance->afternoon_attendance = true;
            $attendance->save();
            
            // Update fine record if exists
            Fine::where('student_id', $student->id)
                ->where('event_id', $event->id)
                ->update([
                    'afternoon_checkout' => true,
                    'absences' => DB::raw('absences - 1'),
                    'total_fines' => DB::raw('total_fines - 25')
                ]);
        }

        return response()->json([
            "message" => "Attendance recorded successfully!",
            "isRecorded" => true,
        ]);
    }

    public function recent()
    {
        date_default_timezone_set('Asia/Manila');
        $time = $time = date("H:i");
        $event = Event::where('date', '=', date('Y-m-d'))
            ->orderBy('created_at', 'desc')
            ->get()
            ->first();

        $students = StudentAttendance::join('students', 'students.s_rfid', '=', 'student_attendances.student_rfid');

        if (($time < $event->checkIn_end && $time > $event->checkIn_start)) {
            $students = $students
                ->where('attend_checkIn', 'true')
                ->where('event_id', $event->id)
                ->get();
        }
        if ($time < $event->checkOut_end && $time > $event->checkOut_start) {
            $students = $students->where('attend_checkOut', "true")
                ->where('event_id', $event->id)
                ->get();
        }

        if ($time > $event->checkOut_end || $time < $event->checkIn_start || ($time > $event->checkIn_end && $time < $event->checkOut_start)) {
            $event = null;
            $students = null;
        }

        return $students;
    }
}
