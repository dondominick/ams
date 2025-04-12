<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Fine;
use App\Models\Student;
use App\Models\StudentAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Collection as Collection;
use Illuminate\Support\Facades\DB;
use stdClass;

class FineController extends Controller
{
    private const FINE_AMOUNT = 25.00;

    public function view()
    {
        // RETRIEVE ALL STUDENT
        $logs = DB::table('students')
            ->leftJoin('fines', 'students.id', '=', 'fines.student_id')
            ->leftJoin('events', 'events.id', '=', 'fines.event_id')
            ->select('students.*', 'fines.*', 'events.event_name')
            ->get();
        // $logs = Fine::with("student", "event")->get();

        $events = Event::select('*')->orderBy('created_at')->get();

        return view('pages.fines', compact('logs', 'events'));
    }

    public function calculateEventFines(Event $event)
    {

        // Check if student has attendance record for this event
        $logs = DB::table('students')
            ->leftJoin('student_attendances', 'students.id', '=', 'student_attendances.id_student')
            ->select('*', 'students.id')
            ->get();

        // Calculate missed actions and fines
        foreach ($logs as $attendance) {

            if ($event->isWholeDay == "true") {
                $missedActions = $this->calculateMissedActionsWholeDay($attendance);
            } else {
                $missedActions = $this->calculateMissedActions($attendance);
            }
            $missedCount = array_sum(array_map(fn($v) => $v ? 1 : 0, $missedActions));

            if ($missedCount > 0) {
                $totalFines = $missedCount * self::FINE_AMOUNT;
                // Create or update fine record
                Fine::updateOrCreate(
                    [
                        'event_id' => $event->id,
                        'student_id' => $attendance->id
                    ],
                    [
                        'student_id' => $attendance->id,
                        'event_id' => $event->id,
                        'fines_amount' => self::FINE_AMOUNT,
                        'morning_checkIn_missed' => $missedActions['morning_checkIn_missed'],
                        'morning_checkOut_missed' => $missedActions['morning_checkOut_missed'],
                        'afternoon_checkIn_missed' => $missedActions['afternoon_checkIn_missed'],
                        'afternoon_checkOut_missed' => $missedActions['afternoon_checkOut_missed'],
                        'total_fines' => $totalFines
                    ]
                );
            }
        }
    }

    private function calculateMissedActions(?stdClass $attendance): array
    {


        if (!$attendance) {
            return [
                'morning_checkIn_missed' => true,
                'morning_checkOut_missed' => true,
                'afternoon_checkIn_missed' => false,
                'afternoon_checkOut_missed' => false
            ];
        }


        return [
            'morning_checkIn_missed' => !$attendance->attend_checkIn,
            'morning_checkOut_missed' => !$attendance->attend_checkOut,
            'afternoon_checkIn_missed' => false,
            'afternoon_checkOut_missed' => false
        ];
    }
    private function calculateMissedActionsWholeDay(?stdClass $attendance): array
    {


        if (!$attendance) {
            return [
                'morning_checkIn_missed' => true,
                'morning_checkOut_missed' => true,
                'afternoon_checkIn_missed' => true,
                'afternoon_checkOut_missed' => true
            ];
        }


        return [
            'morning_checkIn_missed' => !$attendance->attend_checkIn,
            'morning_checkOut_missed' => !$attendance->attend_checkOut,
            'afternoon_checkIn_missed' => !$attendance->attend_afternoon_checkIn,
            'afternoon_checkOut_missed' => !$attendance->attend_afternoon_checkOut
        ];
    }


    public function viewFines()
    {
        $fines = Fine::with(['student', 'event'])->get();
        return view('pages.fines', compact('fines'));
    }


    public function search_via_event() {}
    public function filterByCategory(Request $request)
    {
        $students = Student::select('*');

        if ($request->query('set')) {
            $set = explode(',', $request->query('set'));
            $students = $students->where('s_set', $set);
        }
        if ($request->query('lvl')) {
            $lvl = explode(',', $request->query('lvl'));
            $students = $students->where('s_lvl', $lvl);
        }
        if ($request->query('program')) {
            $program = explode(',', $request->query('program'));
            $students = $students->where('s_program', $program);
        }
        if ($request->query('status')) {
            $status = explode(',', $request->query('status'));
            $students = $students->where('s_status', $status);
        }
        $students = $students->get();


        if (empty($students->first())) {
            return response()->json([
                'message' => 'Student not found',
                'students' => null,
                'query' => $request->query(),
            ]);
        }

        return response()->json([
            'message' => 'Working fine',
            'students' => $students,
            'query' => $request->query(),
        ]);
    }
    public function filter_via_name(Request $request)
    {
        $students = Student::whereAny(['s_fname', 's_studentID', 's_mname', 's_lname'], 'like', $request->query('search') . '%')->get();

        if (empty($students->first())) {
            return response()->json([
                'message' => 'Student not found',
                'students' => null,
            ]);
        }

        return response()->json([
            'message' => 'Working fine',
            'students' => $students,
        ]);
    }
}
