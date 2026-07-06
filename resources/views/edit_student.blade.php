<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student - EduNexus</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-[#1e2538] min-h-screen p-6 text-gray-800">

<div class="max-w-4xl mx-auto space-y-6">

    <div class="bg-[#f0f4f8] rounded-2xl p-5 shadow-md flex justify-between items-center">
        <div class="font-bold text-[#1e2538]">
            EDUNEXUS | <span class="text-gray-500 font-normal text-sm">EDIT STUDENT DETAILS</span>
        </div>

        <a href="{{ route('admin.students') }}" class="bg-gray-700 text-white px-4 py-2 rounded-xl text-sm font-bold">
            Back
        </a>
    </div>

    <div class="bg-[#f0f4f8] rounded-2xl p-6 shadow-md">
        <form action="{{ route('admin.students.update', $student->StudentID) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf

            <div>
                <label class="text-xs font-bold text-gray-500">Roll/Student ID</label>
                <input value="{{ $student->StudentID }}" class="w-full border rounded-xl px-3 py-2 text-sm bg-gray-100" readonly>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500">Gender</label>
                <select name="gender" class="w-full border rounded-xl px-3 py-2 text-sm" required>
                    <option value="Male" {{ $student->Gender == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ $student->Gender == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500">First Name</label>
                <input name="first_name" value="{{ $student->First_Name }}" class="w-full border rounded-xl px-3 py-2 text-sm" required>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500">Middle Name</label>
                <input name="middle_name" value="{{ $student->Middle_Name }}" class="w-full border rounded-xl px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500">Last Name</label>
                <input name="last_name" value="{{ $student->Last_Name }}" class="w-full border rounded-xl px-3 py-2 text-sm" required>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500">Date of Birth</label>
                <input type="date" name="date_of_birth" value="{{ $student->Date_of_Birth }}" class="w-full border rounded-xl px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500">Admission Date</label>
                <input type="date" name="admission_date" value="{{ $student->Admission_Date }}" class="w-full border rounded-xl px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500">Student Contact No</label>
                <input name="contact_no" value="{{ $student->Contact_No }}" class="w-full border rounded-xl px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500">Class</label>
                <select name="class_id" class="w-full border rounded-xl px-3 py-2 text-sm" required>
                    @foreach($classes as $class)
                        <option value="{{ $class->ClassID }}" {{ $student->ClassID == $class->ClassID ? 'selected' : '' }}>
                            {{ $class->ClassName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500">Section</label>
                <select name="section_id" class="w-full border rounded-xl px-3 py-2 text-sm" required>
                    @foreach($sections as $section)
                        <option value="{{ $section->SectionID }}" {{ $student->SectionID == $section->SectionID ? 'selected' : '' }}>
                            {{ $section->SectionName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500">Scholarship</label>
                <select name="scholarship_id" class="w-full border rounded-xl px-3 py-2 text-sm">
                    <option value="">No Scholarship</option>
                    @foreach($scholarships as $sch)
                        <option value="{{ $sch->ScholarshipID }}" {{ $student->ScholarshipID == $sch->ScholarshipID ? 'selected' : '' }}>
                            {{ $sch->ScholarshipName }} - {{ $sch->DiscountPercentage }}%
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500">Father Name</label>
                <input name="father_name" value="{{ $student->Father_Name }}" class="w-full border rounded-xl px-3 py-2 text-sm" required>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500">Mother Name</label>
                <input name="mother_name" value="{{ $student->Mother_Name }}" class="w-full border rounded-xl px-3 py-2 text-sm">
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500">Parent Phone No</label>
                <input name="phone_no" value="{{ $student->Phone_No }}" class="w-full border rounded-xl px-3 py-2 text-sm" required>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-500">Parent Email</label>
                <input type="email" name="email" value="{{ $student->Email }}" class="w-full border rounded-xl px-3 py-2 text-sm">
            </div>

            <div class="md:col-span-2">
                <label class="text-xs font-bold text-gray-500">Student Address</label>
                <textarea name="address" rows="2" class="w-full border rounded-xl px-3 py-2 text-sm">{{ $student->Address }}</textarea>
            </div>

            <div class="md:col-span-2">
                <button class="bg-[#f26e22] text-white rounded-xl px-6 py-2 text-sm font-bold">
                    Update Student
                </button>
            </div>
        </form>
    </div>

</div>

</body>
</html>