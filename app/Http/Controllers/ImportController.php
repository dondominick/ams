<?php

namespace App\Http\Controllers;

use Log;
use Throwable;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Imports\StudentImport;
use League\Csv\Reader;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    // Import the Student
    public function import(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv,txt',
        ]);
        // Determine if the imported file is csv or excel
        if ($file = $request->file('file')) {
            $extension = $file->getClientOriginalExtension();
            if (in_array($extension, ['csv'])) {
                return $this->importCSVFile($request);
            } else {
                return $this->importExcelFile($request);
            }
        }
    }

    private function importCSVFile(Request $request)
    {
        // Retrieve uploaded file
        $file  = $request->file('file');
        $path = $file->getRealPath();

        // Read the CSV file
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); // Set the CSV header offset

        // Proccesss the CSV file
        $records = $csv->getRecords();
        foreach ($records as $record) {
            // Process each record
            // Example: Create or update student records
            Student::updateOrCreate(
                ['s_studentID' => $record['s_studentID']],
                [
                    's_fname' => $record['s_fname'],
                    's_lname' => $record['s_lname'],
                    's_mname' => $record['s_mname'],
                    's_suffix' => $record['s_suffix'],
                    's_program' => $record['s_program'],
                    's_set' => $record['s_set'],
                    's_lvl' => $record['s_lvl'],
                    's_status' => "ENROLLED",
                    // Add other fields as necessary
                ]
            );
        }

        return redirect()->back()->with('success', "CSV Data Imported Successfully");
    }

    private function importExcelFile(Request $request)
    {
        try {


            // Get the uploaded file
            $file = $request->file('file');
            // Process the Excel file
            Excel::import(new StudentImport, $file->store('files'));

            return redirect()->back()->with('success', "Data Imported Successfully");
        } catch (Throwable $error) {
            if ($error->getCode() == 23000) { //23000 is Integrity Constraint error
                // dd($error);
                return redirect()->back()->with('error', $error->getMessage()); //For Duplicate Entries
            } else {
                return redirect()->back()->with('error', $error->getMessage());
            }
        }
    }
}
