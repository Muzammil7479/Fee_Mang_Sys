<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EduNexus - Admin Student Admission</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-[#1e2538] min-h-screen p-6 text-gray-800">

<div class="max-w-7xl mx-auto space-y-6">

    <div class="bg-[#f0f4f8] rounded-2xl p-5 shadow-md">
        <div class="flex items-center gap-2 font-bold text-[#1e2538]">
            <span class="bg-[#f26e22] text-white px-2 py-1 rounded-lg text-sm">EN</span>
            EDUNEXUS | <span class="text-gray-500 font-normal text-sm">ADMIN STUDENT ADMISSION</span>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-xl text-sm font-bold">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl text-sm font-bold">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-1 space-y-6">

            <div class="bg-[#f0f4f8] rounded-2xl p-5 shadow-md">
                <h2 class="font-black text-sm uppercase text-[#1e2538] border-b pb-3 mb-4">
                    Available Class Terms
                </h2>

                <div class="space-y-2">
                    @foreach($classTerms as $ct)
                        <div class="bg-white rounded-xl p-3 text-xs">
                            <div class="font-bold text-gray-900">{{ $ct->ClassName }}</div>
                            <div class="text-gray-500">Academic Year: {{ $ct->AcademicYear }}</div>
                            <div class="text-[#f26e22] font-bold">{{ $ct->TermName }}</div>
                            <div class="text-gray-500">
                                Fee: Rs. {{ number_format($ct->TotalFee, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-[#181d2a] rounded-2xl p-5 text-gray-300 text-xs">
                <h3 class="font-bold mb-2">Administration Flow</h3>
                <p class="leading-relaxed text-gray-400">
                    Select class, section, term, and scholarship while admitting the student.
                    The system automatically creates a student fee record using the selected class and term fee structure.
                </p>
            </div>

        </div>

        <div class="lg:col-span-2 space-y-6">

            <div class="bg-[#f0f4f8] rounded-2xl p-5 shadow-md">
                <h2 class="font-black text-sm uppercase text-[#1e2538] border-b pb-3 mb-4">
                    Admit New Student
                </h2>

                <form action="{{ route('admin.students.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf

                    <div>
                        <label class="text-xs font-bold text-gray-500">First Name</label>
                        <input name="first_name" class="w-full border rounded-xl px-3 py-2 text-sm" required>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500">Middle Name</label>
                        <input name="middle_name" class="w-full border rounded-xl px-3 py-2 text-sm">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500">Last Name</label>
                        <input name="last_name" class="w-full border rounded-xl px-3 py-2 text-sm" required>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500">Gender</label>
                        <select name="gender" class="w-full border rounded-xl px-3 py-2 text-sm" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500">Date of Birth</label>
                        <input type="date" name="date_of_birth" class="w-full border rounded-xl px-3 py-2 text-sm">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500">Admission Date</label>
                        <input type="date" name="admission_date" value="{{ date('Y-m-d') }}" class="w-full border rounded-xl px-3 py-2 text-sm">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500">Student Contact No</label>
                        <input name="contact_no" class="w-full border rounded-xl px-3 py-2 text-sm">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500">Class</label>
                        <select name="class_id" class="w-full border rounded-xl px-3 py-2 text-sm" required>
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->ClassID }}">
                                    {{ $class->ClassName }} - {{ $class->AcademicYear }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500">Section</label>
                        <select name="section_id" class="w-full border rounded-xl px-3 py-2 text-sm" required>
                            <option value="">Select Section</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->SectionID }}">
                                    {{ $section->SectionName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500">Term</label>
                        <select name="term_id" class="w-full border rounded-xl px-3 py-2 text-sm" required>
                            <option value="">Select Term</option>
                            @foreach($terms as $term)
                                <option value="{{ $term->TermID }}">
                                    {{ $term->TermName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500">Scholarship</label>
                        <select name="scholarship_id" class="w-full border rounded-xl px-3 py-2 text-sm">
                            <option value="">No Scholarship</option>
                            @foreach($scholarships as $sch)
                                <option value="{{ $sch->ScholarshipID }}">
                                    {{ $sch->ScholarshipName }} - {{ $sch->DiscountPercentage }}%
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500">Fee Due Date</label>
                        <input type="date" name="due_date" class="w-full border rounded-xl px-3 py-2 text-sm">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500">Father Name</label>
                        <input name="father_name" class="w-full border rounded-xl px-3 py-2 text-sm" required>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500">Mother Name</label>
                        <input name="mother_name" class="w-full border rounded-xl px-3 py-2 text-sm">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500">Parent Phone No</label>
                        <input name="phone_no" class="w-full border rounded-xl px-3 py-2 text-sm" required>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500">Parent Email</label>
                        <input type="email" name="email" class="w-full border rounded-xl px-3 py-2 text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs font-bold text-gray-500">Student Address</label>
                        <textarea name="address" rows="2" class="w-full border rounded-xl px-3 py-2 text-sm"></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <button class="bg-[#f26e22] text-white rounded-xl px-6 py-2 text-sm font-bold">
                            Admit Student
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-[#f0f4f8] rounded-2xl p-5 shadow-md">
                <div class="flex flex-col md:flex-row justify-between gap-3 border-b pb-3 mb-4">
                    <h2 class="font-black text-sm uppercase text-[#1e2538]">
                        Student Records
                    </h2>

                    <form method="GET" action="{{ route('admin.students') }}">
                        <select name="class_id" onchange="this.form.submit()" class="border rounded-xl px-3 py-2 text-sm">
                            <option value="">All Classes</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->ClassID }}" {{ $selectedClassId == $class->ClassID ? 'selected' : '' }}>
                                    {{ $class->ClassName }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                        <tr class="text-gray-400 uppercase text-xs border-b">
                            <th class="pb-2">Roll/ID</th>
                            <th class="pb-2">Name</th>
                            <th class="pb-2">Father</th>
                            <th class="pb-2">Class</th>
                            <th class="pb-2">Section</th>
                            <th class="pb-2">Scholarship</th>
                            <th class="pb-2">Phone</th>
                            <th class="pb-2 text-right">Action</th>
                        </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                        @forelse($students as $student)
                            <tr>
                                <td class="py-3 font-bold">{{ $student->StudentID }}</td>
                                <td class="py-3">
                                    {{ $student->First_Name }}
                                    {{ $student->Middle_Name }}
                                    {{ $student->Last_Name }}
                                </td>
                                <td class="py-3">{{ $student->Father_Name ?? 'N/A' }}</td>
                                <td class="py-3">{{ $student->ClassName ?? 'N/A' }}</td>
                                <td class="py-3">{{ $student->SectionName ?? 'N/A' }}</td>
                                <td class="py-3">
                                    {{ $student->ScholarshipName ?? 'None' }}
                                    @if($student->DiscountPercentage)
                                        ({{ $student->DiscountPercentage }}%)
                                    @endif
                                </td>
                                <td class="py-3">{{ $student->Phone_No ?? 'N/A' }}</td>
                                <td class="py-3 text-right">
                                    <a href="{{ route('admin.students.edit', $student->StudentID) }}"
                                       class="bg-indigo-600 text-white px-3 py-1 rounded-lg text-xs font-bold">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-6 text-center text-gray-400">
                                    No student records found.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>