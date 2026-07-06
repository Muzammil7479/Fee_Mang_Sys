<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $studentData = null;
        $feeRecords = [];
        $searchPerformed = false;

        // Trigger search if any parameter is provided
        if ($request->has('search_query') && !empty($request->search_query)) {
            $searchPerformed = true;
            $queryStr = $request->search_query;

            // Find the student record joining Class, Section, and Parent tables
            $student = DB::table('student as s')
                ->leftJoin('class as c', 's.ClassID', '=', 'c.ClassID')
                ->leftJoin('section as sec', 's.SectionID', '=', 'sec.SectionID')
                ->leftJoin('parent as p', 's.ParentID', '=', 'p.ParentID')
                ->select(
                    's.StudentID', 's.First_Name', 's.Middle_Name', 's.Last_Name', 
                    'c.ClassName', 'c.AcademicYear', 'sec.SectionName', 'p.Father_Name'
                )
                ->where('s.StudentID', '=', $queryStr)
                ->orWhere(DB::raw("CONCAT(s.First_Name, ' ', IFNULL(s.Middle_Name,''), ' ', s.Last_Name)"), 'LIKE', "%{$queryStr}%")
                ->orWhere('p.Father_Name', 'LIKE', "%{$queryStr}%")
                ->first();

            if ($student) {
                $studentData = $student;

                // Pull related Term, StudentFee, Payment, and Receipt data
                $feeRecords = DB::table('studentfee as sf')
                    ->join('feestructure as fs', 'sf.FeeStructureID', '=', 'fs.FeeStructureID')
                    ->join('term as t', 'fs.TermID', '=', 't.TermID')
                    ->leftJoin('payment as pay', 'sf.StudentFeeID', '=', 'pay.StudentFeeID')
                    ->leftJoin('receipt as r', 'pay.PaymentID', '=', 'r.PaymentID')
                    ->select(
                        't.TermName',
                        'sf.RemainingBalance',
                        'sf.Status',
                        'r.ReceiptNumber',
                        'r.ReceiptDate',
                        'pay.AmountPaid',
                        // Count separate transaction logs as dynamic installments paid
                        DB::raw('(SELECT COUNT(*) FROM payment WHERE payment.StudentFeeID = sf.StudentFeeID) as InstallmentsPaid')
                    )
                    ->where('sf.StudentID', $student->StudentID)
                    ->get();
            }
        }

        return view('student', compact('studentData', 'feeRecords', 'searchPerformed'));
    }
}