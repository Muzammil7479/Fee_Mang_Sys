<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EduNexus - Account Section</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-[#1e2538] min-h-screen p-6 text-gray-800">

<div class="max-w-7xl mx-auto space-y-6">

    <div class="bg-[#f0f4f8] rounded-2xl p-5 shadow-md">
        <div class="flex items-center gap-2 font-bold text-[#1e2538] mb-4">
            <span class="bg-[#f26e22] text-white px-2 py-1 rounded-lg text-sm">EN</span>
            EDUNEXUS | <span class="text-gray-500 font-normal text-sm">ACCOUNT SECTION</span>
        </div>

        <form action="{{ route('account.dashboard') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <select name="class_id" class="border rounded-xl px-3 py-2 text-sm">
                <option value="">All Classes</option>
                @foreach($classes as $class)
                    <option value="{{ $class->ClassID }}" {{ $selectedClassId == $class->ClassID ? 'selected' : '' }}>
                        {{ $class->ClassName }} - {{ $class->AcademicYear }}
                    </option>
                @endforeach
            </select>

            <input type="text"
                   name="search_student"
                   value="{{ request('search_student') }}"
                   placeholder="Search Student ID, Name, or Father Name"
                   class="md:col-span-2 border rounded-xl px-3 py-2 text-sm">

            <button class="bg-[#f26e22] text-white rounded-xl px-4 py-2 text-sm font-bold">
                Search / Load
            </button>
        </form>
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

        <div class="lg:col-span-2 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-[#f0f4f8] rounded-2xl p-5 shadow-md border-l-4 border-green-500">
                    <p class="text-xs font-bold text-gray-400 uppercase">Total Revenue Collected</p>
                    <h2 class="text-2xl font-black">Rs. {{ number_format($totalCollected, 2) }}</h2>
                </div>

                <div class="bg-[#f0f4f8] rounded-2xl p-5 shadow-md border-l-4 border-red-500">
                    <p class="text-xs font-bold text-gray-400 uppercase">Total Active Dues</p>
                    <h2 class="text-2xl font-black">Rs. {{ number_format($totalOutstanding, 2) }}</h2>
                </div>
            </div>

            <div class="bg-[#f0f4f8] rounded-2xl p-5 shadow-md">
                <h2 class="font-black text-sm uppercase text-[#1e2538] border-b pb-3 mb-4">
                    Create / Update Class Fee Plan
                </h2>

                <form action="{{ route('account.createClassPlan') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @csrf

                    <select name="class_id" class="border rounded-xl px-3 py-2 text-sm" required>
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->ClassID }}">
                                {{ $class->ClassName }} - {{ $class->AcademicYear }}
                            </option>
                        @endforeach
                    </select>

                    <select name="term_id" class="border rounded-xl px-3 py-2 text-sm" required>
                        <option value="">Select Term</option>
                        @foreach($terms as $term)
                            <option value="{{ $term->TermID }}">
                                {{ $term->TermName }}
                            </option>
                        @endforeach
                    </select>

                    <input type="date" name="due_date" class="border rounded-xl px-3 py-2 text-sm">

                    <input type="number" step="0.01" name="tuition_fee" placeholder="Tuition Fee" class="border rounded-xl px-3 py-2 text-sm" required>
                    <input type="number" step="0.01" name="exam_fee" placeholder="Exam Fee" class="border rounded-xl px-3 py-2 text-sm" required>
                    <input type="number" step="0.01" name="transport_fee" placeholder="Transport Fee" class="border rounded-xl px-3 py-2 text-sm" required>
                    <input type="number" step="0.01" name="misc_fee" placeholder="Misc Fee" class="border rounded-xl px-3 py-2 text-sm" required>

                    <div class="md:col-span-2">
                        <button class="bg-[#f26e22] text-white rounded-xl px-5 py-2 text-sm font-bold">
                            Save Plan and Assign to Class Students
                        </button>
                    </div>
                </form>
            </div>

            @if(!empty($selectedClassId))
                <div class="bg-[#f0f4f8] rounded-2xl p-5 shadow-md">
                    <h2 class="font-black text-sm uppercase text-[#1e2538] border-b pb-3 mb-4">
                        Students in Selected Class
                    </h2>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead>
                            <tr class="text-gray-400 uppercase text-xs border-b">
                                <th class="pb-2">Student ID</th>
                                <th class="pb-2">Name</th>
                                <th class="pb-2">Father</th>
                                <th class="pb-2">Section</th>
                                <th class="pb-2">Scholarship</th>
                                <th class="pb-2">Phone</th>
                                <th class="pb-2 text-right">Action</th>
                            </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                            @forelse($classStudents as $student)
                                <tr>
                                    <td class="py-3 font-bold">{{ $student->StudentID }}</td>
                                    <td class="py-3">
                                        {{ $student->First_Name }}
                                        {{ $student->Middle_Name }}
                                        {{ $student->Last_Name }}
                                    </td>
                                    <td class="py-3">{{ $student->Father_Name ?? 'N/A' }}</td>
                                    <td class="py-3">{{ $student->SectionName ?? 'N/A' }}</td>
                                    <td class="py-3">
                                        {{ $student->ScholarshipName ?? 'None' }}
                                        @if($student->DiscountPercentage)
                                            ({{ $student->DiscountPercentage }}%)
                                        @endif
                                    </td>
                                    <td class="py-3">{{ $student->Phone_No ?? 'N/A' }}</td>
                                    <td class="py-3 text-right">
                                        <a href="{{ route('account.dashboard', ['class_id' => $selectedClassId, 'search_student' => $student->StudentID]) }}"
                                           class="bg-[#f26e22] text-white px-3 py-1 rounded-lg text-xs font-bold">
                                            View Ledger
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-6 text-center text-gray-400">
                                        No students found in this class.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <div class="bg-[#f0f4f8] rounded-2xl p-5 shadow-md">
                <h2 class="font-black text-sm uppercase text-[#1e2538] border-b pb-3 mb-4">
                    Student Fee Ledger
                </h2>

                @if(!$searchPerformed)
                    <p class="text-center text-gray-400 text-sm py-8">
                        Search student by ID, name, or father name to view fee record.
                    </p>
                @elseif($searchPerformed && !$studentData)
                    <p class="text-center text-red-500 text-sm py-8 font-bold">
                        No student found.
                    </p>
                @else
                    <div class="bg-white rounded-xl p-4 mb-5 grid grid-cols-1 md:grid-cols-4 gap-3 text-sm">
                        <div>
                            <span class="text-gray-400 text-xs">Student ID</span>
                            <div class="font-bold">{{ $studentData->StudentID }}</div>
                        </div>

                        <div>
                            <span class="text-gray-400 text-xs">Student Name</span>
                            <div class="font-bold">
                                {{ $studentData->First_Name }}
                                {{ $studentData->Middle_Name }}
                                {{ $studentData->Last_Name }}
                            </div>
                        </div>

                        <div>
                            <span class="text-gray-400 text-xs">Father Name</span>
                            <div class="font-bold">{{ $studentData->Father_Name ?? 'N/A' }}</div>
                        </div>

                        <div>
                            <span class="text-gray-400 text-xs">Class / Section</span>
                            <div class="font-bold">
                                {{ $studentData->ClassName ?? 'N/A' }} /
                                {{ $studentData->SectionName ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-4 mb-5">
                        <form action="{{ route('account.applyScholarship') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            @csrf

                            <input type="hidden" name="student_id" value="{{ $studentData->StudentID }}">

                            <select name="scholarship_id" class="border rounded-xl px-3 py-2 text-sm">
                                <option value="">No Scholarship</option>
                                @foreach($scholarships as $sch)
                                    <option value="{{ $sch->ScholarshipID }}" {{ $studentData->ScholarshipID == $sch->ScholarshipID ? 'selected' : '' }}>
                                        {{ $sch->ScholarshipName }} - {{ $sch->DiscountPercentage }}%
                                    </option>
                                @endforeach
                            </select>

                            <button class="bg-indigo-600 text-white rounded-xl px-4 py-2 text-sm font-bold">
                                Apply Scholarship
                            </button>
                        </form>
                    </div>

                    @forelse($feeRecords as $fee)
                        <div class="bg-white rounded-2xl p-4 mb-5 border border-gray-200">
                            <div class="flex flex-col md:flex-row justify-between gap-3 border-b pb-3 mb-4">
                                <div>
                                    <h3 class="font-black text-[#1e2538]">{{ $fee->TermName }}</h3>
                                    <p class="text-xs text-gray-400">Due Date: {{ $fee->DueDate ?? 'N/A' }}</p>
                                </div>

                                <span class="w-fit px-3 py-1 rounded-full text-xs font-bold
                                    {{ $fee->Status == 'Paid' ? 'bg-green-100 text-green-700' : ($fee->Status == 'Partially Paid' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ $fee->Status }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-6 gap-3 text-sm mb-5">
                                <div>
                                    <span class="text-gray-400 text-xs">Total Fee</span>
                                    <div class="font-bold">Rs. {{ number_format($fee->TotalAmount, 2) }}</div>
                                </div>

                                <div>
                                    <span class="text-gray-400 text-xs">Discount</span>
                                    <div class="font-bold text-red-500">-Rs. {{ number_format($fee->DiscountAmount, 2) }}</div>
                                </div>

                                <div>
                                    <span class="text-gray-400 text-xs">Fine</span>
                                    <div class="font-bold text-amber-600">+Rs. {{ number_format($fee->FineAmount, 2) }}</div>
                                </div>

                                <div>
                                    <span class="text-gray-400 text-xs">Paid</span>
                                    <div class="font-bold text-green-600">Rs. {{ number_format($fee->PaidAmount, 2) }}</div>
                                </div>

                                <div>
                                    <span class="text-gray-400 text-xs">Remaining</span>
                                    <div class="font-black">Rs. {{ number_format($fee->RemainingBalance, 2) }}</div>
                                </div>

                                <div>
                                    <span class="text-gray-400 text-xs">Ledger ID</span>
                                    <div class="font-bold">{{ $fee->StudentFeeID }}</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                <form action="{{ route('account.addPayment') }}" method="POST" class="bg-gray-50 rounded-xl p-3">
                                    @csrf

                                    <input type="hidden" name="student_fee_id" value="{{ $fee->StudentFeeID }}">

                                    <h4 class="font-bold text-sm mb-2">Add Payment</h4>

                                    <input type="number" step="0.01" name="amount_paid" placeholder="Amount Paid"
                                           class="w-full border rounded-lg px-3 py-2 text-sm mb-2" required>

                                    <div class="grid grid-cols-2 gap-2">
                                        <select name="payment_method" class="border rounded-lg px-3 py-2 text-sm" required>
                                            <option value="Cash">Cash</option>
                                            <option value="Bank Transfer">Bank Transfer</option>
                                            <option value="Credit Card">Credit Card</option>
                                            <option value="Debit Card">Debit Card</option>
                                            <option value="JazzCash">JazzCash</option>
                                            <option value="EasyPaisa">EasyPaisa</option>
                                        </select>

                                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}"
                                               class="border rounded-lg px-3 py-2 text-sm" required>
                                    </div>

                                    <input name="transaction_reference" placeholder="Transaction Reference"
                                           class="w-full border rounded-lg px-3 py-2 text-sm mt-2">

                                    <button class="bg-green-600 text-white rounded-lg px-4 py-2 text-sm font-bold mt-2">
                                        Save Payment
                                    </button>
                                </form>

                                <form action="{{ route('account.addFine') }}" method="POST" class="bg-gray-50 rounded-xl p-3">
                                    @csrf

                                    <input type="hidden" name="student_fee_id" value="{{ $fee->StudentFeeID }}">

                                    <h4 class="font-bold text-sm mb-2">Add Fine</h4>

                                    <input type="number" step="0.01" name="fine_amount" placeholder="Fine Amount"
                                           class="w-full border rounded-lg px-3 py-2 text-sm mb-2" required>

                                    <input name="fine_reason" placeholder="Fine Reason"
                                           class="w-full border rounded-lg px-3 py-2 text-sm mb-2">

                                    <input type="date" name="applied_date" value="{{ date('Y-m-d') }}"
                                           class="w-full border rounded-lg px-3 py-2 text-sm" required>

                                    <button class="bg-red-600 text-white rounded-lg px-4 py-2 text-sm font-bold mt-2">
                                        Add Fine
                                    </button>
                                </form>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-5">
                                <div>
                                    <h4 class="font-bold text-sm mb-2">Payment History</h4>

                                    @if($fee->payments->isEmpty())
                                        <p class="text-xs text-gray-400">No payment record.</p>
                                    @else
                                        <table class="w-full text-xs">
                                            <thead>
                                            <tr class="border-b text-gray-400">
                                                <th class="text-left pb-1">Date</th>
                                                <th class="text-left pb-1">Method</th>
                                                <th class="text-right pb-1">Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($fee->payments as $payment)
                                                <tr class="border-b border-gray-100">
                                                    <td class="py-1">{{ $payment->PaymentDate }}</td>
                                                    <td class="py-1">{{ $payment->PaymentMethod }}</td>
                                                    <td class="py-1 text-right font-bold text-green-600">
                                                        Rs. {{ number_format($payment->AmountPaid, 2) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>

                                <div>
                                    <h4 class="font-bold text-sm mb-2">Fine History</h4>

                                    @if($fee->fines->isEmpty())
                                        <p class="text-xs text-gray-400">No fine record.</p>
                                    @else
                                        <table class="w-full text-xs">
                                            <thead>
                                            <tr class="border-b text-gray-400">
                                                <th class="text-left pb-1">Date</th>
                                                <th class="text-left pb-1">Reason</th>
                                                <th class="text-right pb-1">Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($fee->fines as $fine)
                                                <tr class="border-b border-gray-100">
                                                    <td class="py-1">{{ $fine->AppliedDate }}</td>
                                                    <td class="py-1">{{ $fine->FineReason ?? 'N/A' }}</td>
                                                    <td class="py-1 text-right font-bold text-red-600">
                                                        Rs. {{ number_format($fine->FineAmount, 2) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-400 text-sm py-6">
                            No fee ledger found for this student.
                        </p>
                    @endforelse
                @endif
            </div>

        </div>

        <div class="space-y-6">

            <div class="bg-[#f0f4f8] rounded-2xl p-5 shadow-md">
                <h2 class="font-black text-sm uppercase text-[#1e2538] border-b pb-3 mb-4">
                    Recent Payment Logs
                </h2>

                @forelse($recentPayments as $payment)
                    <div class="bg-white rounded-xl p-3 mb-3 text-sm">
                        <div class="font-bold">
                            {{ $payment->First_Name }}
                            {{ $payment->Middle_Name }}
                            {{ $payment->Last_Name }}
                        </div>
                        <div class="text-xs text-gray-400">
                            {{ $payment->PaymentMethod }} • {{ $payment->PaymentDate }}
                        </div>
                        <div class="text-green-600 font-black">
                            Rs. {{ number_format($payment->AmountPaid, 2) }}
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400">No payment logs.</p>
                @endforelse
            </div>

            <div class="bg-[#f0f4f8] rounded-2xl p-5 shadow-md">
                <h2 class="font-black text-sm uppercase text-[#1e2538] border-b pb-3 mb-4">
                    Existing Class Fee Plans
                </h2>

                @foreach($feePlans as $plan)
                    <div class="bg-white rounded-xl p-3 mb-3 text-xs">
                        <div class="font-bold">{{ $plan->ClassName }} - {{ $plan->TermName }}</div>
                        <div>Academic Year: {{ $plan->AcademicYear }}</div>
                        <div>Tuition: Rs. {{ number_format($plan->TuitionFee, 2) }}</div>
                        <div>Exam: Rs. {{ number_format($plan->ExamFee, 2) }}</div>
                        <div>Transport: Rs. {{ number_format($plan->TransportFee, 2) }}</div>
                        <div>Misc: Rs. {{ number_format($plan->MiscFee, 2) }}</div>
                        <div class="font-black text-[#f26e22]">
                            Total: Rs. {{ number_format($plan->TotalFee, 2) }}
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

    </div>
</div>

</body>
</html>