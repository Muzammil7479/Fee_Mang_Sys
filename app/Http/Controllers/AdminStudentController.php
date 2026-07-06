<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminStudentController extends Controller
{
    public function index(Request $request)
    {
        $selectedClassId = $request->class_id;

        $classes = DB::table('class')
            ->select('ClassID', 'ClassName', 'AcademicYear')
            ->orderBy('ClassName')
            ->get();

        $sections = DB::table('section')
            ->select('SectionID', 'SectionName', 'ClassID')
            ->orderBy('SectionName')
            ->get();

        $terms = DB::table('term')
            ->select('TermID', 'TermName', 'StartDate', 'EndDate')
            ->orderBy('StartDate')
            ->get();

        $scholarships = DB::table('scholarship')
            ->select('ScholarshipID', 'ScholarshipName', 'DiscountPercentage', 'Description')
            ->orderBy('ScholarshipName')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Fee structures show which term is available for each class
        |--------------------------------------------------------------------------
        */
        $classTerms = DB::table('feestructure as fs')
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
        | Students list
        |--------------------------------------------------------------------------
        */
        $studentsQuery = DB::table('student as s')
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
                's.ParentID',
                's.ScholarshipID',
                'c.ClassName',
                'sec.SectionName',
                'sch.ScholarshipName',
                'sch.DiscountPercentage',
                'p.Father_Name',
                'p.Mother_Name',
                'p.Phone_No',
                'p.Email'
            );

        if (!empty($selectedClassId)) {
            $studentsQuery->where('s.ClassID', $selectedClassId);
        }

        $students = $studentsQuery
            ->orderBy('s.StudentID', 'desc')
            ->get();

        return view('admin_students', compact(
            'classes',
            'sections',
            'terms',
            'scholarships',
            'classTerms',
            'students',
            'selectedClassId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'class_id' => 'required',
            'section_id' => 'required',
            'term_id' => 'required',
            'father_name' => 'required',
            'phone_no' => 'required',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Find fee structure for selected class and selected term
        |--------------------------------------------------------------------------
        */
        $feeStructure = DB::table('feestructure')
            ->where('ClassID', $request->class_id)
            ->where('TermID', $request->term_id)
            ->first();

        if (!$feeStructure) {
            return back()->with('error', 'No fee structure found for selected class and term.');
        }

        /*
        |--------------------------------------------------------------------------
        | Create parent
        |--------------------------------------------------------------------------
        */
        $parentId = DB::table('parent')->insertGetId([
            'Father_Name' => $request->father_name,
            'Mother_Name' => $request->mother_name,
            'Phone_No' => $request->phone_no,
            'Email' => $request->email,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create student
        |--------------------------------------------------------------------------
        | StudentID will work as roll/admission number in your current database.
        |--------------------------------------------------------------------------
        */
        $studentId = DB::table('student')->insertGetId([
            'First_Name' => $request->first_name,
            'Middle_Name' => $request->middle_name,
            'Last_Name' => $request->last_name,
            'Gender' => $request->gender,
            'Date_of_Birth' => $request->date_of_birth,
            'Contact_No' => $request->contact_no,
            'Address' => $request->address,
            'Admission_Date' => $request->admission_date ?? now()->format('Y-m-d'),
            'ClassID' => $request->class_id,
            'SectionID' => $request->section_id,
            'ParentID' => $parentId,
            'ScholarshipID' => $request->scholarship_id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Calculate scholarship discount
        |--------------------------------------------------------------------------
        */
        $discountAmount = 0;

        if (!empty($request->scholarship_id)) {
            $scholarship = DB::table('scholarship')
                ->where('ScholarshipID', $request->scholarship_id)
                ->first();

            if ($scholarship) {
                $discountAmount = ($feeStructure->TotalFee * $scholarship->DiscountPercentage) / 100;
            }
        }

        $totalAmount = $feeStructure->TotalFee;
        $remainingBalance = $totalAmount - $discountAmount;

        /*
        |--------------------------------------------------------------------------
        | Create student fee record
        |--------------------------------------------------------------------------
        */
        DB::table('studentfee')->insert([
            'StudentID' => $studentId,
            'FeeStructureID' => $feeStructure->FeeStructureID,
            'DueDate' => $request->due_date,
            'TotalAmount' => $totalAmount,
            'DiscountAmount' => $discountAmount,
            'FineAmount' => 0,
            'RemainingBalance' => $remainingBalance,
            'Status' => 'Pending',
        ]);

        return back()->with('success', 'New student admitted successfully.');
    }

    public function edit($id)
    {
        $student = DB::table('student as s')
            ->leftJoin('parent as p', 's.ParentID', '=', 'p.ParentID')
            ->where('s.StudentID', $id)
            ->select(
                's.*',
                'p.Father_Name',
                'p.Mother_Name',
                'p.Phone_No',
                'p.Email'
            )
            ->first();

        if (!$student) {
            return redirect()->route('admin.students')->with('error', 'Student not found.');
        }

        $classes = DB::table('class')
            ->select('ClassID', 'ClassName', 'AcademicYear')
            ->orderBy('ClassName')
            ->get();

        $sections = DB::table('section')
            ->select('SectionID', 'SectionName', 'ClassID')
            ->orderBy('SectionName')
            ->get();

        $scholarships = DB::table('scholarship')
            ->select('ScholarshipID', 'ScholarshipName', 'DiscountPercentage')
            ->orderBy('ScholarshipName')
            ->get();

        return view('edit_student', compact(
            'student',
            'classes',
            'sections',
            'scholarships'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'class_id' => 'required',
            'section_id' => 'required',
            'father_name' => 'required',
            'phone_no' => 'required',
        ]);

        $student = DB::table('student')
            ->where('StudentID', $id)
            ->first();

        if (!$student) {
            return redirect()->route('admin.students')->with('error', 'Student not found.');
        }

        DB::table('student')
            ->where('StudentID', $id)
            ->update([
                'First_Name' => $request->first_name,
                'Middle_Name' => $request->middle_name,
                'Last_Name' => $request->last_name,
                'Gender' => $request->gender,
                'Date_of_Birth' => $request->date_of_birth,
                'Contact_No' => $request->contact_no,
                'Address' => $request->address,
                'Admission_Date' => $request->admission_date,
                'ClassID' => $request->class_id,
                'SectionID' => $request->section_id,
                'ScholarshipID' => $request->scholarship_id,
            ]);

        DB::table('parent')
            ->where('ParentID', $student->ParentID)
            ->update([
                'Father_Name' => $request->father_name,
                'Mother_Name' => $request->mother_name,
                'Phone_No' => $request->phone_no,
                'Email' => $request->email,
            ]);

        return redirect()->route('admin.students')->with('success', 'Student details updated successfully.');
    }
}