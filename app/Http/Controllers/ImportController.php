<?php

namespace App\Http\Controllers;

use Log;
use Throwable;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Imports\StudentImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    // Import the Student
    public function import(Request $request)
    {
        try {
            // Validate the uploaded file
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv', //Adjusted by Panzerweb: includes .csv
            ]);

            // Get the uploaded file
            $file = $request->file('file');
            // Process the Excel file
            Excel::import(new StudentImport, $file->store('files'));

            return redirect()->back()->with('success', "Data Imported Successfully");
        } catch (Throwable $error) {
            if ($error->getCode() == 23000) { //23000 is Integrity Constraint error
                // dd($error);
                return redirect()->back()->with('error', $error->getMessage()); //For Duplicate Entries
            }else{
                return redirect()->back()->with('error', $error->getMessage());
                
            }
        }
    }
}
