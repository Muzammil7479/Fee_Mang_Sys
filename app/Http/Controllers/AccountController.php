<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $selectedClassId = $request->class_id;
        $search = $request->search_student;

        $searchPerformed = false;
        $studentData = null;
        $feeRecords = collect();

        $classes = DB::table('class')
            ->select('ClassID', 'ClassName', 'AcademicYear')
            ->orderBy('ClassName')
            ->get();

        $terms = DB::table('term')
            ->select('TermID', 'TermName', 'StartDate', 'EndDate')
            ->orderBy('StartDate')
            ->get();

        $scholarships = DB::table('scholarship')
            ->select('ScholarshipID', 'ScholarshipName', 'DiscountPercentage')
            ->orderBy('ScholarshipName')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Class fee plans
        |--------------------------------------------------------------------------
        */
        $feePlans = DB::table('feestructure as fs')
            ->join('class as c', 'fs.ClassID', '=', 'c.ClassID')
            ->join('term as t', 'fs.TermID', '=', 't.TermID')
            ->select(
                'fs.FeeStructureID',
                'c.ClassID',
                'c.ClassName',
                'c.AcademicYear',
                't.TermID',
                't.TermName',
                'fs.TuitionFee',
                'fs.ExamFee',
                'fs.TransportFee',
                'fs.MiscFee',
                'fs.TotalFee'
            )
            ->orderBy('c.ClassName')
            ->orderBy('t.StartDate')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Class students list
        |--------------------------------------------------------------------------
        */
        $classStudents = collect();

        if (!empty($selectedClassId)) {
            $classStudents = DB::table('student as s')
                ->leftJoin('parent as p', 's.ParentID', '=', 'p.ParentID')
                ->leftJoin('class as c', 's.ClassID', '=', 'c.ClassID')
                ->leftJoin('section as sec', 's.SectionID', '=', 'sec.SectionID')
                ->leftJoin('scholarship as sch', 's.ScholarshipID', '=', 'sch.ScholarshipID')
                ->where('s.ClassID', $selectedClassId)
                ->select(
                    's.StudentID',
                    's.First_Name',
                    's.Middle_Name',
                    's.Last_Name',
                    'c.ClassName',
                    'sec.SectionName',
                    'sch.ScholarshipName',
                    'sch.DiscountPercentage',
                    'p.Father_Name',
                    'p.Phone_No'
                )
                ->orderBy('s.StudentID')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Search student ledger by student ID, student name, or father name
        |--------------------------------------------------------------------------
        */
        if (!empty($search)) {
            $searchPerformed = true;

            $studentQuery = DB::table('student as s')
                ->leftJoin('parent as p', 's.ParentID', '=', 'p.ParentID')
                ->leftJoin('class as c', 's.ClassID', '=', 'c.ClassID')
                ->leftJoin('section as sec', 's.SectionID', '=', 'sec.SectionID')
                ->leftJoin('scholarship as sch', 's.ScholarshipID', '=', 'sch.ScholarshipID')
                ->select(
                    's.StudentID',
                    's.First_Name',
                    's.Middle_Name',
                    's.Last_Name',
                    's.Gender',
                    's.Date_of_Birth',
                    's.Contact_No',
                    's.Address',
                    's.Admission_Date',
                    's.ClassID',
                    's.SectionID',
                    's.ScholarshipID',
                    'c.ClassName',
                    'c.AcademicYear',
                    'sec.SectionName',
                    'sch.ScholarshipName',
                    'sch.DiscountPercentage',
                    'p.Father_Name',
                    'p.Mother_Name',
                    'p.Phone_No',
                    'p.Email'
                );

            if (!empty($selectedClassId)) {
                $studentQuery->where('s.ClassID', $selectedClassId);
            }

            $student = $studentQuery
                ->where(function ($q) use ($search) {
                    $q->where('s.StudentID', '=', $search)
                        ->orWhere('s.First_Name', 'LIKE', "%{$search}%")
                        ->orWhere('s.Middle_Name', 'LIKE', "%{$search}%")
                        ->orWhere('s.Last_Name', 'LIKE', "%{$search}%")
                        ->orWhere(DB::raw("CONCAT(s.First_Name, ' ', s.Last_Name)"), 'LIKE', "%{$search}%")
                        ->orWhere(DB::raw("CONCAT(s.First_Name, ' ', s.Middle_Name, ' ', s.Last_Name)"), 'LIKE', "%{$search}%")
                        ->orWhere('p.Father_Name', 'LIKE', "%{$search}%");
                })
                ->first();

            if ($student) {
                $studentData = $student;

                $feeRecords = DB::table('studentfee as sf')
                    ->join('feestructure as fs', 'sf.FeeStructureID', '=', 'fs.FeeStructureID')
                    ->join('term as t', 'fs.TermID', '=', 't.TermID')
                    ->select(
                        'sf.StudentFeeID',
                        'sf.StudentID',
                        'sf.FeeStructureID',
                        'sf.DueDate',
                        'sf.TotalAmount',
                        'sf.DiscountAmount',
                        'sf.FineAmount',
                        'sf.RemainingBalance',
                        'sf.Status',
                        't.TermName',
                        'fs.TuitionFee',
                        'fs.ExamFee',
                        'fs.TransportFee',
                        'fs.MiscFee',
                        'fs.TotalFee'
                    )
                    ->where('sf.StudentID', $student->StudentID)
                    ->orderBy('sf.DueDate')
                    ->get();

                foreach ($feeRecords as $fee) {
                    $fee->payments = DB::table('payment')
                        ->where('StudentFeeID', $fee->StudentFeeID)
                        ->orderBy('PaymentDate', 'desc')
                        ->get();

                    $fee->fines = DB::table('fine')
                        ->where('StudentFeeID', $fee->StudentFeeID)
                        ->orderBy('AppliedDate', 'desc')
                        ->get();

                    $fee->PaidAmount = DB::table('payment')
                        ->where('StudentFeeID', $fee->StudentFeeID)
                        ->sum('AmountPaid');
                }
            }
        }

        $totalOutstanding = DB::table('studentfee')->sum('RemainingBalance');
        $totalCollected = DB::table('payment')->sum('AmountPaid');

        $recentPayments = DB::table('payment as p')
            ->join('studentfee as sf', 'p.StudentFeeID', '=', 'sf.StudentFeeID')
            ->join('student as s', 'sf.StudentID', '=', 's.StudentID')
            ->select(
                'p.PaymentID',
                'p.PaymentDate',
                'p.AmountPaid',
                'p.PaymentMethod',
                'p.TransactionReference',
                's.StudentID',
                's.First_Name',
                's.Middle_Name',
                's.Last_Name'
            )
            ->orderBy('p.PaymentDate', 'desc')
            ->take(5)
            ->get();

        return view('account', compact(
            'classes',
            'terms',
            'scholarships',
            'feePlans',
            'classStudents',
            'selectedClassId',
            'search',
            'searchPerformed',
            'studentData',
            'feeRecords',
            'totalOutstanding',
            'totalCollected',
            'recentPayments'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Create or update class fee plan
    |--------------------------------------------------------------------------
    */
    public function createClassPlan(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'term_id' => 'required',
            'tuition_fee' => 'required|numeric|min:0',
            'exam_fee' => 'required|numeric|min:0',
            'transport_fee' => 'required|numeric|min:0',
            'misc_fee' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
        ]);

        $existingPlan = DB::table('feestructure')
            ->where('ClassID', $request->class_id)
            ->where('TermID', $request->term_id)
            ->first();

        if ($existingPlan) {
            DB::table('feestructure')
                ->where('FeeStructureID', $existingPlan->FeeStructureID)
                ->update([
                    'TuitionFee' => $request->tuition_fee,
                    'ExamFee' => $request->exam_fee,
                    'TransportFee' => $request->transport_fee,
                    'MiscFee' => $request->misc_fee,
                ]);

            $feeStructureId = $existingPlan->FeeStructureID;
        } else {
            $feeStructureId = DB::table('feestructure')->insertGetId([
                'ClassID' => $request->class_id,
                'TermID' => $request->term_id,
                'TuitionFee' => $request->tuition_fee,
                'ExamFee' => $request->exam_fee,
                'TransportFee' => $request->transport_fee,
                'MiscFee' => $request->misc_fee,
            ]);
        }

        $feeStructure = DB::table('feestructure')
            ->where('FeeStructureID', $feeStructureId)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Create or update studentfee record for every student in that class
        |--------------------------------------------------------------------------
        */
        $students = DB::table('student')
            ->where('ClassID', $request->class_id)
            ->get();

        foreach ($students as $student) {
            $discountAmount = $this->calculateDiscount($feeStructure->TotalFee, $student->ScholarshipID);

            $existingStudentFee = DB::table('studentfee')
                ->where('StudentID', $student->StudentID)
                ->where('FeeStructureID', $feeStructureId)
                ->first();

            if ($existingStudentFee) {
                $paidAmount = DB::table('payment')
                    ->where('StudentFeeID', $existingStudentFee->StudentFeeID)
                    ->sum('AmountPaid');

                $fineAmount = DB::table('fine')
                    ->where('StudentFeeID', $existingStudentFee->StudentFeeID)
                    ->sum('FineAmount');

                $remainingBalance = ($feeStructure->TotalFee - $discountAmount + $fineAmount) - $paidAmount;
                $status = $this->getFeeStatus($paidAmount, $remainingBalance);

                DB::table('studentfee')
                    ->where('StudentFeeID', $existingStudentFee->StudentFeeID)
                    ->update([
                        'DueDate' => $request->due_date,
                        'TotalAmount' => $feeStructure->TotalFee,
                        'DiscountAmount' => $discountAmount,
                        'FineAmount' => $fineAmount,
                        'RemainingBalance' => max($remainingBalance, 0),
                        'Status' => $status,
                    ]);
            } else {
                DB::table('studentfee')->insert([
                    'StudentID' => $student->StudentID,
                    'FeeStructureID' => $feeStructureId,
                    'DueDate' => $request->due_date,
                    'TotalAmount' => $feeStructure->TotalFee,
                    'DiscountAmount' => $discountAmount,
                    'FineAmount' => 0,
                    'RemainingBalance' => $feeStructure->TotalFee - $discountAmount,
                    'Status' => 'Pending',
                ]);
            }
        }

        return back()->with('success', 'Class fee plan created/updated and assigned to students.');
    }

    /*
    |--------------------------------------------------------------------------
    | Apply scholarship to student
    |--------------------------------------------------------------------------
    */
    public function applyScholarship(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'scholarship_id' => 'nullable',
        ]);

        $student = DB::table('student')
            ->where('StudentID', $request->student_id)
            ->first();

        if (!$student) {
            return back()->with('error', 'Student not found.');
        }

        DB::table('student')
            ->where('StudentID', $request->student_id)
            ->update([
                'ScholarshipID' => $request->scholarship_id,
            ]);

        $studentFees = DB::table('studentfee as sf')
            ->join('feestructure as fs', 'sf.FeeStructureID', '=', 'fs.FeeStructureID')
            ->where('sf.StudentID', $request->student_id)
            ->select('sf.*', 'fs.TotalFee')
            ->get();

        foreach ($studentFees as $fee) {
            $discountAmount = $this->calculateDiscount($fee->TotalFee, $request->scholarship_id);

            DB::table('studentfee')
                ->where('StudentFeeID', $fee->StudentFeeID)
                ->update([
                    'DiscountAmount' => $discountAmount,
                ]);

            $this->recalculateStudentFee($fee->StudentFeeID);
        }

        return back()->with('success', 'Scholarship updated and fee recalculated.');
    }

    /*
    |--------------------------------------------------------------------------
    | Add payment
    |--------------------------------------------------------------------------
    */
    public function addPayment(Request $request)
    {
        $request->validate([
            'student_fee_id' => 'required',
            'amount_paid' => 'required|numeric|min:1',
            'payment_method' => 'required',
            'payment_date' => 'required|date',
            'transaction_reference' => 'nullable',
        ]);

        $studentFee = DB::table('studentfee')
            ->where('StudentFeeID', $request->student_fee_id)
            ->first();

        if (!$studentFee) {
            return back()->with('error', 'Student fee record not found.');
        }

        DB::table('payment')->insert([
            'StudentFeeID' => $request->student_fee_id,
            'PaymentDate' => $request->payment_date,
            'AmountPaid' => $request->amount_paid,
            'PaymentMethod' => $request->payment_method,
            'TransactionReference' => $request->transaction_reference,
        ]);

        $this->recalculateStudentFee($request->student_fee_id);

        return back()->with('success', 'Payment added successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Add fine
    |--------------------------------------------------------------------------
    */
    public function addFine(Request $request)
    {
        $request->validate([
            'student_fee_id' => 'required',
            'fine_amount' => 'required|numeric|min:1',
            'fine_reason' => 'nullable',
            'applied_date' => 'required|date',
        ]);

        $studentFee = DB::table('studentfee')
            ->where('StudentFeeID', $request->student_fee_id)
            ->first();

        if (!$studentFee) {
            return back()->with('error', 'Student fee record not found.');
        }

        DB::table('fine')->insert([
            'FineAmount' => $request->fine_amount,
            'FineReason' => $request->fine_reason,
            'AppliedDate' => $request->applied_date,
            'StudentFeeID' => $request->student_fee_id,
        ]);

        $this->recalculateStudentFee($request->student_fee_id);

        return back()->with('success', 'Fine added successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | Helper: calculate scholarship discount
    |--------------------------------------------------------------------------
    */
    private function calculateDiscount($totalFee, $scholarshipId)
    {
        if (empty($scholarshipId)) {
            return 0;
        }

        $scholarship = DB::table('scholarship')
            ->where('ScholarshipID', $scholarshipId)
            ->first();

        if (!$scholarship) {
            return 0;
        }

        return ($totalFee * $scholarship->DiscountPercentage) / 100;
    }

    /*
    |--------------------------------------------------------------------------
    | Helper: recalculate student fee balance
    |--------------------------------------------------------------------------
    */
    private function recalculateStudentFee($studentFeeId)
    {
        $studentFee = DB::table('studentfee')
            ->where('StudentFeeID', $studentFeeId)
            ->first();

        if (!$studentFee) {
            return;
        }

        $paidAmount = DB::table('payment')
            ->where('StudentFeeID', $studentFeeId)
            ->sum('AmountPaid');

        $fineAmount = DB::table('fine')
            ->where('StudentFeeID', $studentFeeId)
            ->sum('FineAmount');

        $remainingBalance = ($studentFee->TotalAmount - $studentFee->DiscountAmount + $fineAmount) - $paidAmount;

        $status = $this->getFeeStatus($paidAmount, $remainingBalance);

        DB::table('studentfee')
            ->where('StudentFeeID', $studentFeeId)
            ->update([
                'FineAmount' => $fineAmount,
                'RemainingBalance' => max($remainingBalance, 0),
                'Status' => $status,
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper: status
    |--------------------------------------------------------------------------
    */
    private function getFeeStatus($paidAmount, $remainingBalance)
    {
        if ($remainingBalance <= 0) {
            return 'Paid';
        }

        if ($paidAmount > 0) {
            return 'Partially Paid';
        }

        return 'Pending';
    }
}