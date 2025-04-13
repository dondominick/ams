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
use Illuminate\Support\Facades\Log;

class StudentAttendanceController extends Controller
{
    public function view()
    {
        date_default_timezone_set('Asia/Manila');
        $time = date("H:i");
        $event = Event::where('date', '=', date('Y-m-d'))
            ->orderBy('created_at', 'desc')
            ->get()
            ->first();

        // if ($event) {
        //     $this->processAbsentStudents($event, $time);
        // }

        // CHECK IF THERE IS A WHOLE DAY EVENT
        $wholeDay = Event::where('date', '=', date('Y-m-d'))
            ->where("isWholeDay",'true')
            ->orderBy('created_at', 'desc')
            ->get()
            ->first();



        // IF THERE IS AN WHOLEDAY EVENT
        //
        if (empty($event)) {
            $event = null;
            return view('pages.attendance', compact('event'));
        }

        if(!empty($wholeDay)){
            if (
                ($time > $wholeDay->checkIn_start && $time < $wholeDay->checkIn_end)
            || ($time > $wholeDay->checkOut_start && $time < $wholeDay->checkOut_end)
            ||  ($time > $wholeDay->afternoon_checkIn_start  && $time < $wholeDay->afternoon_checkIn_end)
            ||  ($time > $wholeDay->afternoon_checkOut_start  && $time < $wholeDay->afternoon_checkOut_end)
            ) {
                $event = $wholeDay;


        $students = $this->recent();
        return view('pages.attendance', compact('event', 'students'));
            }
        }

        if (
            $time < $event->checkIn_start
        || ($time > $event->checkIn_end && $time < $event->checkOut_start)
        ||  $time > $event->checkOut_end
        ) {
            $event = null;
        }

        $students = $this->recent();
        return view('pages.attendance', compact('event', 'students'));
    }

    protected function processAbsentStudents($event, $currentTime)
    {
        try {
            // Clean and parse time values
            $currentTime = Carbon::createFromFormat('H:i', $currentTime)->format('H:i:00');
            $checkInEnd = Carbon::createFromFormat('H:i', substr($event->checkIn_end, 0, 5))->format('H:i:00');
            $checkOutEnd = Carbon::createFromFormat('H:i', substr($event->checkOut_end, 0, 5))->format('H:i:00');

            // Only process if these times exist in the event
            if (isset($event->afternoon_checkIn_end) && isset($event->afternoon_checkOut_end)) {
                $afternoonCheckInEnd = Carbon::createFromFormat('H:i', substr($event->afternoon_checkIn_end, 0, 5))->format('H:i:00');
                $afternoonCheckOutEnd = Carbon::creasteFromFormat('H:i', substr($event->afternoon_checkOut_end, 0, 5))->format('H:i:00');
            }

            // Don't process if no periods have ended yet
            $currentTime = Carbon::createFromFormat('H:i:s', $currentTime);
            $checkInEnd = Carbon::createFromFormat('H:i:s', $checkInEnd);

            if ($currentTime->lt($checkInEnd)) {
                return;
            }

            // Rest of the function remains the same
            $settings = FineSettings::firstOrCreate(['id' => 1], [
                'fine_amount' => 25.00
            ]);

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
                        'morning_checkin' => true,
                        'morning_checkout' => true,
                        'afternoon_checkin' => true,
                        'afternoon_checkout' => true
                    ]
                );

                // Reset counters
                $fine->absences = 0;

                // Only check morning check-in if that period has ended
                if ($currentTime->gt($checkInEnd)) {
                    if (!$attendance || !$attendance->attend_checkIn) {
                        $fine->morning_checkin = false;
                        $fine->absences++;
                    } else {
                        $fine->morning_checkin = true;
                    }
                }

                // Only check morning check-out if that period has ended
                if ($currentTime->gt($checkOutEnd)) {
                    if (!$attendance || !$attendance->attend_checkOut) {
                        $fine->morning_checkout = false;
                        $fine->absences++;
                    } else {
                        $fine->morning_checkout = true;
                    }
                }

                // Only check afternoon check-in if that period has ended
                if (isset($afternoonCheckInEnd) && $currentTime->gt($afternoonCheckInEnd)) {
                    if (!$attendance || !$attendance->attend_afternoon_checkIn) {
                        $fine->afternoon_checkin = false;
                        $fine->absences++;
                    } else {
                        $fine->afternoon_checkin = true;
                    }
                }

                // Only check afternoon check-out if that period has ended
                if (isset($afternoonCheckOutEnd) && $currentTime->gt($afternoonCheckOutEnd)) {
                    if (!$attendance || !$attendance->attend_afternoon_checkOut) {
                        $fine->afternoon_checkout = false;
                        $fine->absences++;
                    } else {
                        $fine->afternoon_checkout = true;
                    }
                }

                // Calculate total fines
                $fine->total_fines = $fine->absences * $settings->fine_amount;
                $fine->save();
            }
        } catch (\Exception $e) {
            Log::error('Error processing absences: ' . $e->getMessage());
            return;
        }
    }

    public function recordAttendance(Request $request)
    {
        // FIRST VALIDATE REQUEST FORM
        $request->validate([
            "s_rfid" => ['required'],
        ]);


        $myStudent = Student::whereAny(['s_rfid', 's_studentID'], $request->s_rfid)->get()->first();
        $studentsEvent = StudentAttendance::where('student_rfid', $request->s_rfid)->get()->first(); //Get the corresponding student_attendance data

        // CHECK IF STUDENT EXIST IN THE MASTERLIST
        if (empty($myStudent)) {
            return response()->json([
                "message" => "I am sorry but the student does not exist in the masterlist",
                "isRecorded" => false,
                "doesntExist" => true,
            ]);
        }

        //Get the details of the student


        // INITIALIZE VARIABLES, ETC
        date_default_timezone_set('Asia/Manila');
        $time = date("H:i");
        $currentTimestamp = now();
        $currentTime = date('H:i:s');
        $event = Event::find($request->event_id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->first();



        if($event->isWholeDay == 'true'){
             return $this->recordWholeDayEvent($request, $event, $myStudent, $studentsEvent); //Updated method to 4 parameters
        }



        // DETERMINE IF IT IS ALREADY PAST THE SET CHECK IN AND CHECK OUT TIME
        if ($time < $event->checkIn_start || ($time > $event->checkIn_end && $time < $event->checkOut_start) || $time > $event->checkOut_end) {
            return response()->json([
                "message" => "I am sorry, but your attendance can only be recorded during the set time frame",
                "isRecorded" => false
            ]);
        }

        $currentTime = date('H:i');

        // NOTE: Code changed by Panzerweb --- 
        // All JSON `data` and `event_data` added are implemented to ensure integrity that data is sent with full disclosure
        // Also helps in debugging and tracing errors, you can also add "bug-line" => "Line number" as a key-pair value
        // of all json response return statements to trace errors.

        if ($time > $event->checkIn_start && $time < $event->checkIn_end && !empty(StudentAttendance::where('event_id',$event->id )->where("student_rfid", $request->s_rfid)->get()->first())
        ) {
            return response()->json([
                "message" =>"Student's attendance is already recorded",
                "data" => $myStudent,
                "isRecorded" => false,
                "AlreadyRecorded"=>true
            ]);
        }

        if ($time > $event->checkOut_start && $time < $event->checkOut_end && !empty(StudentAttendance::where('event_id',$event->id )->where("student_rfid", $request->s_rfid)->whereNotNull('attend_checkOut')->get()->first())) {
            return response()->json([
                "message" =>"Student's attendance is already recorded",
                "isRecorded" => false,
                "data" => $myStudent,
                "AlreadyRecorded"=>true
            ]);
        }

        if ($time > $event->checkIn_start && $time < $event->checkIn_end) {
            StudentAttendance::create([
                'student_rfid' => $request->s_rfid,
                'event_id' => $request->event_id,
                "attend_checkIn"=> $currentTime,
                "id_student"=> $myStudent->id
            ]);

            // RETURNS A JSON THAT ALSO RECORDS THE CHECK IN
            return response()->json([
                "message" => "Attendance recorded successfully!",
                "isRecorded" => true,
                "data" => $myStudent,
                "attend_checkIn"=> $currentTime,
            ]);
        }


        if ($time > $event->checkOut_start && $time < $event->checkOut_end && empty(StudentAttendance::where('event_id',$event->id )->where("student_rfid", $request->s_rfid)->get()->first())) {

            StudentAttendance::create([
                'student_rfid' => $request->s_rfid,
                'event_id' => $request->event_id,
                "attend_checkOut"=> $currentTime,
                "id_student"=> $myStudent->id
            ]);
            // RECORDS THE CHECKOUT AS JSON
            return response()->json([
                "message" => "Attendance recorded successfully!",
                "isRecorded" => true,
                "data" => $myStudent,
                "attend_checkOut"=> $currentTime,
            ]);
        }


        if ($time > $event->checkOut_start && $time < $event->checkOut_end && !empty(StudentAttendance::where('event_id',$event->id )->where("student_rfid", $request->s_rfid)->get()->first())) {
            StudentAttendance::where('event_id',$event->id )
            ->where("id_student", $myStudent->id)
            ->update([
                "attend_checkOut"=> $currentTime
            ]);
            //RECORDS THE CHECKOUT AS JSON
            return response()->json([
                "message" => "Attendance recorded successfully!",
                "isRecorded" => true,
                "data" => $myStudent,
                "attend_checkOut"=> $currentTime,
            ]);
        }


        return response()->json([
            "message" => "Attendance recorded successfully! test!",
            "isRecorded" => true,
            "data" => $myStudent, //RECORDS THE DATA OF THE STUDENT DETAILS
        ]);

    }
    protected function recordWholeDayEvent(Request $request, Event $event, Student $myStudent, StudentAttendance $studentEvent){
        $time = date("H:i:s");

        if (
            $time < $event->checkIn_start
        || ($time > $event->checkIn_end && $time < $event->checkOut_start)
        ||  ($time > $event->checkOut_end  && $time < $event->afternoon_checkIn_start)
        ||  ($time > $event->afternoon_checkIn_end  && $time < $event->afternoon_checkOut_start)
        ||  ($time > $event->afternoon_checkOut_end)
        ) {
            return response()->json([
                "message" => "I am sorry, but your attendance can only be recorded during the set time frame",
                "isRecorded" => false
            ]);
        }


        if ($time > $event->checkIn_start && $time < $event->checkIn_end && !empty(StudentAttendance::where('event_id',$event->id )->where("id_student", $myStudent->id)->get()->first())
        ) {
            return response()->json([
                "message" =>"Student's attendance is already recorded",
                "isRecorded" => false,
                "data" => $myStudent,
                "event_data" => $studentEvent,
                "AlreadyRecorded"=>true
            ]);
        }

        if ($time > $event->checkOut_start && $time < $event->checkOut_end && !empty(StudentAttendance::where('event_id',$event->id )->where("id_student", $myStudent->id)->whereNotNull('attend_checkOut')->get()->first())) {
            return response()->json([
                "message" =>"Student's attendance is already recorded",
                "isRecorded" => false,
                "data" => $myStudent,
                "event_data" => $studentEvent,
                "AlreadyRecorded"=>true
            ]);
        }
        if ($time > $event->afternoon_checkIn_start && $time < $event->afternoon_checkIn_end && !empty(StudentAttendance::where('event_id',$event->id )->where("id_student", $myStudent->id)->whereNotNull('attend_afternoon_checkIn')->get()->first())) {
            return response()->json([
                "message" =>"Student's attendance is already recorded",
                "isRecorded" => false,
                "data" => $myStudent,
                "event_data" => $studentEvent,
                "AlreadyRecorded"=>true
            ]);
        }
        if ($time > $event->afternoon_checkOut_start && $time < $event->afternoon_checkOut_end && !empty(StudentAttendance::where('event_id',$event->id )->where("id_student", $myStudent->id)->whereNotNull('attend_afternoon_checkOut')->get()->first())) {
            return response()->json([
                "message" =>"Student's attendance is already recorded",
                "isRecorded" => false,
                "data" => $myStudent,
                "event_data" => $studentEvent,
                "AlreadyRecorded"=>true
            ]);
        }

        if ($time > $event->checkIn_start && $time < $event->checkIn_end) {
            StudentAttendance::create([
                'student_rfid' => $request->s_rfid,
                'event_id' => $request->event_id,
                "attend_checkIn"=> $time,
                "id_student"=> $myStudent->id
            ]);

            // RETURNS A JSON THAT ALSO RECORDS THE CHECK IN
            return response()->json([
                "message" => "Attendance recorded successfully!",
                "isRecorded" => true,
                "data" => $myStudent,
                "event_data" => $studentEvent,
                "attend_checkIn"=> $time,
            ]);
        }

        // CREATE RECORD IF STUDENT RECORD ALREADY EXIST

        if ($time > $event->checkOut_start && $time < $event->checkOut_end && empty(StudentAttendance::where('event_id',$event->id )->where("id_student", $myStudent->id)->get()->first())) {

            StudentAttendance::create([
                'student_rfid' => $request->s_rfid,
                'event_id' => $request->event_id,
                "attend_checkOut"=> $time,
                "id_student"=> $myStudent->id
            ]);
            // RECORDS THE CHECKOUT AS JSON
            return response()->json([
                "message" => "Attendance recorded successfully!",
                "isRecorded" => true,
                "data" => $myStudent,
                "attend_checkOut"=> $time,
            ]);
        }
        if ($time > $event->afternoon_checkOut_start && $time < $event->afternoon_checkOut_end && empty(StudentAttendance::where('event_id',$event->id )->where("id_student", $myStudent->id)->get()->first())) {

            StudentAttendance::create([
                'student_rfid' => $request->s_rfid,
                'event_id' => $request->event_id,
                "attend_afternoon_checkOut"=> $time,
                "id_student"=> $myStudent->id
            ]);
            // RECORDS THE CHECKOUT AS JSON
            return response()->json([
                "message" => "Attendance recorded successfully!",
                "isRecorded" => true,
                "data" => $myStudent,
                "attend_afternoon_checkOut"=> $time,
            ]);
        } if ($time > $event->afternoon_checkIn_start && $time < $event->afternoon_checkIn_end && empty(StudentAttendance::where('event_id',$event->id )->where("id_student", $myStudent->id)->get()->first())) {

            StudentAttendance::create([
                'student_rfid' => $request->s_rfid,
                'event_id' => $request->event_id,
                "attend_afternoon_checkIn"=> $time,
                "id_student"=> $myStudent->id
            ]);
            // RECORDS THE CHECKOUT AS JSON
            return response()->json([
                "message" => "Attendance recorded successfully!",
                "isRecorded" => true,
                "data" => $myStudent,
                "attend_afternoon_checkIn"=> $time,
            ]);
        }


        // UPDATE STUDENT RECORD IF ALREADy EXIST
        if ($time > $event->checkOut_start && $time < $event->checkOut_end && !empty(StudentAttendance::where('event_id',$event->id )->where("student_rfid", $request->s_rfid)->get()->first())) {
            StudentAttendance::where('event_id',$event->id )
            ->where("id_student", $myStudent->id)
            ->update([
                "attend_checkOut"=> $time
            ]);
            //RECORDS THE CHECKOUT AS JSON
            return response()->json([
                "message" => "Attendance recorded successfully!",
                "isRecorded" => true,
                "data" => $myStudent,
                "attend_checkOut"=> $time,
            ]);
        }


        // FIXED THE CORRECT REQUEST OF DATA in the afternoon
        if ($time > $event->afternoon_checkIn_start && $time < $event->afternoon_checkIn_end && !empty(StudentAttendance::where('event_id',$event->id )->where("student_rfid", $request->s_rfid)->get()->first())) {
            StudentAttendance::where('event_id',$event->id )
            ->where("id_student", $myStudent->id)
            ->update([
                "attend_afternoon_checkIn"=> $time
            ]);
            //RECORDS THE CHECKOUT AS JSON
            return response()->json([
                "message" => "Attendance recorded successfully!",
                "isRecorded" => true,
                "data" => $myStudent,
                "attend_afternoon_checkIn"=> $time,
            ]);
        }
        // FIXED THE CORRECT REQUEST OF DATA in the afternoon
        if ($time > $event->afternoon_checkOut_start && $time < $event->afternoon_checkOut_end && !empty(StudentAttendance::where('event_id',$event->id )->where("student_rfid", $request->s_rfid)->get()->first())) {
            StudentAttendance::where('event_id',$event->id )
            ->where("id_student", $myStudent->id)
            ->update([
                "attend_afternoon_checkOut" => $time
            ]);
            //RECORDS THE CHECKOUT AS JSON
            return response()->json([
                "message" => "Attendance recorded successfully!",
                "isRecorded" => true,
                "data" => $myStudent,
                "attend_afternoon_checkOut"=> $time,
            ]);
        }

        return response()->json([
            "message" => "Attendance recorded successfully! StudentAttendanceController line:468", //Default JSON request when doing attendance
            "isRecorded" => true,
            "Bug" => "Student Recorded but not added to records due to (Had already logged in)",
            "data" => $myStudent, //RECORDS THE DATA OF THE STUDENT DETAILS
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

        $students = StudentAttendance::join('students', 'students.id', '=', 'student_attendances.id_student')->select('*', 'student_attendances.created_at');

        if (($time < $event->checkIn_end && $time > $event->checkIn_start)) {
            $students = $students
                ->where('event_id', $event->id)
                ->get();
        }

        if ($time < $event->checkOut_end && $time > $event->checkOut_start) {

            $students = $students->whereNotNull('attend_checkOut')
                ->where('event_id', $event->id)
                ->get();
        }

        if($event->isWholeDay == "true"){

            if (($time < $event->afternoon_checkIn_end && $time > $event->afternoon_checkIn_start)) {
                $students = $students
                    ->whereNotNull('attend_afternoon_checkIn')
                    ->where('event_id', $event->id)
                    ->get();
            }

            if ($time < $event->afternoon_checkOut_end && $time > $event->afternoon_checkOut_start) {

                $students = $students->whereNotNull('attend_afternoon_checkOut')
                    ->where('event_id', $event->id)
                    ->get();
            }
            if (($time > $event->checkOut_end && $time < $event->afternoon_checkIn_start ) ||
            $time < $event->checkIn_start ||
             ($time > $event->checkIn_end && $time < $event->checkOut_start) ||
           ($time > $event->afternoon_checkIn_end && $time < $event->afternoon_checkOut_start  ) ||
           ($time > $event->afternoon_checkOut_end)) {
               $event = null;
               $students = null;
           }
           return $students;
        }

        if ($time > $event->checkOut_end || $time < $event->checkIn_start ||($time > $event->checkIn_end && $time < $event->checkOut_start) ) {
            $event = null;
            $students = null;
        }

        return $students;
    }

    public function formatAttendance(){
        // FIRST GET STUDENT
        // CHECK IF STUDENT ATTENDED IN THE EVENT
        // COMBINE DUPLICATES OF SAME EVENT
        //
        $attendance = StudentAttendance::where('event_id', 2)->where( function (Builder $query){
            $query->where('student_rfid', "2023-00069")
            ->orWhere('student_rfid', "0002193309");
        })->get();

        $group = $attendance->groupBy(['student_rfid', 'event_id']);

        $student = Student::whereAny(['s_rfid', 's_studentID'], '')->get()->first();

        dd($group);

        dd($student);
    }



}
