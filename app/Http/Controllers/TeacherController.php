<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Teacher;

class TeacherController extends Controller
{
    /**
     * Teacher module dashboard with live stats.
     */
    public function dashboard()
    {
        $totalTeachers = Teacher::count();
        $activeTeachers = Teacher::where('status', true)->count();
        $inactiveTeachers = $totalTeachers - $activeTeachers;
        $totalSalary = Teacher::sum('salary');
        $recentTeachers = Teacher::latest()->take(5)->get();

        return view('teacher.dashboard', compact(
            'totalTeachers',
            'activeTeachers',
            'inactiveTeachers',
            'totalSalary',
            'recentTeachers'
        ));
    }

    /**
     * List teachers with search + status filter.
     */
    public function index(Request $request)
    {
        $query = Teacher::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('teacher_id', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status') === 'active' ? 1 : 0);
        }

        $teachers = $query->orderByDesc('id')->paginate(10)->withQueryString();

        return view('teacher.index', compact('teachers'));
    }

    public function create()
    {
        return view('teacher.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'gender'         => 'required|in:Male,Female,Other',
            'date_of_birth'  => 'required|date|before:today',
            'cnic'           => 'required|string|max:20|unique:teachers,cnic',
            'phone'          => 'required|string|max:20',
            'email'          => 'required|email|unique:teachers,email',
            'address'        => 'nullable|string',
            'qualification'  => 'required|string|max:150',
            'experience'     => 'required|numeric|min:0',
            'joining_date'   => 'required|date',
            'salary'         => 'required|numeric|min:0',
            'class_id'       => 'nullable|string|max:50',
            'section_id'     => 'nullable|string|max:50',
            'subject'        => 'required|string|max:100',
            'photo'          => 'nullable|image|max:2048',
        ]);

        $teacher = new Teacher();
        $teacher->teacher_id = 'TCH' . date('Y') . sprintf('%04d', Teacher::count() + 1);
        $teacher->first_name = $validated['first_name'];
        $teacher->last_name = $validated['last_name'];
        $teacher->gender = $validated['gender'];
        $teacher->dob = $validated['date_of_birth'];
        $teacher->cnic = $validated['cnic'];
        $teacher->phone = $validated['phone'];
        $teacher->email = $validated['email'];
        $teacher->address = $validated['address'] ?? null;
        $teacher->qualification = $validated['qualification'];
        $teacher->experience = $validated['experience'];
        $teacher->joining_date = $validated['joining_date'];
        $teacher->salary = $validated['salary'];
        $teacher->class_id = $validated['class_id'] ?? null;
        $teacher->section_id = $validated['section_id'] ?? null;
        $teacher->subject = $validated['subject'];
        $teacher->status = true;

        if ($request->hasFile('photo')) {
            $teacher->photo = $request->file('photo')->store('teachers', 'public');
        }

        $teacher->save();

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Teacher added successfully.');
    }

    public function show($id)
    {
        $teacher = Teacher::findOrFail($id);

        return view('teacher.show', compact('teacher'));
    }

    public function edit($id)
    {
        $teacher = Teacher::findOrFail($id);

        return view('teacher.edit', compact('teacher'));
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        $validated = $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'gender'         => 'required|in:Male,Female,Other',
            'date_of_birth'  => 'required|date|before:today',
            'cnic'           => 'required|string|max:20|unique:teachers,cnic,' . $teacher->id,
            'phone'          => 'required|string|max:20',
            'email'          => 'required|email|unique:teachers,email,' . $teacher->id,
            'address'        => 'nullable|string',
            'qualification'  => 'required|string|max:150',
            'experience'     => 'required|numeric|min:0',
            'joining_date'   => 'required|date',
            'salary'         => 'required|numeric|min:0',
            'class_id'       => 'nullable|string|max:50',
            'section_id'     => 'nullable|string|max:50',
            'subject'        => 'required|string|max:100',
            'photo'          => 'nullable|image|max:2048',
            'status'         => 'nullable|boolean',
        ]);

        $teacher->first_name = $validated['first_name'];
        $teacher->last_name = $validated['last_name'];
        $teacher->gender = $validated['gender'];
        $teacher->dob = $validated['date_of_birth'];
        $teacher->cnic = $validated['cnic'];
        $teacher->phone = $validated['phone'];
        $teacher->email = $validated['email'];
        $teacher->address = $validated['address'] ?? null;
        $teacher->qualification = $validated['qualification'];
        $teacher->experience = $validated['experience'];
        $teacher->joining_date = $validated['joining_date'];
        $teacher->salary = $validated['salary'];
        $teacher->class_id = $validated['class_id'] ?? null;
        $teacher->section_id = $validated['section_id'] ?? null;
        $teacher->subject = $validated['subject'];
        $teacher->status = $request->boolean('status');

        if ($request->hasFile('photo')) {
            if ($teacher->photo) {
                Storage::disk('public')->delete($teacher->photo);
            }
            $teacher->photo = $request->file('photo')->store('teachers', 'public');
        }

        $teacher->save();

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);

        if ($teacher->photo) {
            Storage::disk('public')->delete($teacher->photo);
        }

        $teacher->delete();

        return redirect()
            ->route('teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }
}
