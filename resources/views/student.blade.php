<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduNexus - Student Information Ledger</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#1e2538] min-h-screen p-4 md:p-8 text-gray-800">

    <div class="max-w-5xl mx-auto space-y-6">
        
        <div class="bg-[#f0f4f8] rounded-2xl p-6 shadow-md flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <span class="bg-[#f26e22] text-white p-2 rounded-xl text-md font-bold">EN</span>
                <div>
                    <h1 class="text-xl font-bold text-[#1e2538]">EDUNEXUS MANAGEMENT SYSTEM</h1>
                    <p class="text-xs text-gray-500">Student Secure Portal • View Only Mode</p>
                </div>
            </div>
            <form action="{{ route('student.dashboard') }}" method="GET" class="w-full md:w-auto flex gap-2">
                <div class="relative w-full md:w-72">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="search_query" value="{{ request('search_query') }}" placeholder="Search ID, Name, or Father Name..." 
                           class="w-full pl-9 pr-3 py-2 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#f26e22] text-sm">
                </div>
                <button type="submit" class="bg-[#f26e22] text-white text-sm font-semibold px-4 py-2 rounded-xl hover:bg-[#d95a16] transition">
                    Search
                </button>
            </form>
        </div>

        @if(!$searchPerformed)
            <div class="bg-[#f0f4f8] rounded-2xl p-12 text-center shadow-md">
                <i class="fa-solid fa-address-card text-5xl text-gray-400 mb-3"></i>
                <h3 class="text-lg font-bold text-[#1e2538]">Search Ledger Profiles</h3>
                <p class="text-xs text-gray-500 max-w-sm mx-auto mt-1">Please type a Student Roll Number ID, full name, or father's name above to safely populate academic data strings.</p>
            </div>
        @elseif($searchPerformed && !$studentData)
            <div class="bg-[#f0f4f8] rounded-2xl p-12 text-center shadow-md border-b-4 border-red-500">
                <i class="fa-solid fa-triangle-exclamation text-5xl text-red-500 mb-3"></i>
                <h3 class="text-lg font-bold text-[#1e2538]">No Matching Record Found</h3>
                <p class="text-xs text-gray-500 mt-1">We couldn't locate any active student under "{{ request('search_query') }}". Double check spelling or database constraints.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="bg-[#f0f4f8] p-6 rounded-2xl shadow-md space-y-4 h-fit">
                    <div class="text-center pb-4 border-b border-gray-200">
                        <div class="w-20 h-20 bg-slate-300 rounded-full mx-auto flex items-center justify-center text-[#1e2538] text-3xl font-bold mb-2 shadow-inner">
                            {{ substr($studentData->First_Name, 0, 1) }}{{ substr($studentData->Last_Name, 0, 1) }}
                        </div>
                        <h2 class="text-lg font-extrabold text-[#1e2538]">{{ $studentData->First_Name }} {{ $studentData->Middle_Name }} {{ $studentData->Last_Name }}</h2>
                        <span class="text-xs font-semibold bg-gray-200 text-gray-700 px-2.5 py-0.5 rounded-full mt-1 inline-block">ID: #{{ $studentData->StudentID }}</span>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="block text-xs uppercase tracking-wider text-gray-400 font-bold">Father's Name</span>
                            <span class="font-medium text-gray-700">{{ $studentData->Father_Name ?? 'N/A' }}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <span class="block text-xs uppercase tracking-wider text-gray-400 font-bold">Class</span>
                                <span class="font-medium text-gray-700">{{ $studentData->ClassName }}</span>
                            </div>
                            <div>
                                <span class="block text-xs uppercase tracking-wider text-gray-400 font-bold">Section</span>
                                <span class="font-medium text-gray-700">{{ $studentData->SectionName }}</span>
                            </div>
                        </div>
                        <div>
                            <span class="block text-xs uppercase tracking-wider text-gray-400 font-bold">Academic Session</span>
                            <span class="font-medium text-gray-700 text-xs">{{ $studentData->AcademicYear }}</span>
                        </div>
                    </div>

                    <div class="pt-2">
                        <span class="text-[11px] block text-amber-600 bg-amber-50 p-2 border border-amber-200 rounded-lg rounded-tl-none">
                            <i class="fa-solid fa-lock mr-1"></i> Security Notice: Changes or data manipulation can only be requested through the Admin desk.
                        </span>
                    </div>
                </div>

                <div class="md:col-span-2 bg-[#f0f4f8] p-6 rounded-2xl shadow-md flex flex-col justify-between">
                    <div>
                        <h3 class="text-md font-bold text-[#1e2538] mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-receipt text-[#f26e22]"></i> Academic Term Fee Breakdowns & Installments
                        </h3>

                        @if($feeRecords->isEmpty())
                            <p class="text-sm text-gray-500 italic p-4 text-center">No structural fee entries assigned for this student record yet.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs">
                                    <thead>
                                        <tr class="border-b border-gray-300 text-gray-400 uppercase tracking-wider">
                                            <th class="py-2 pb-3">Term</th>
                                            <th class="py-2 pb-3">Receipt No.</th>
                                            <th class="py-2 pb-3 text-center">Installments Paid</th>
                                            <th class="py-2 pb-3 text-right">Remaining Dues</th>
                                            <th class="py-2 pb-3 text-right">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 text-gray-700">
                                        @foreach($feeRecords as $fee)
                                            <tr>
                                                <td class="py-3 font-semibold">{{ $fee->TermName }}</td>
                                                <td class="py-3 font-mono text-gray-500">{{ $fee->ReceiptNumber ?? 'No Receipt Issued' }}</td>
                                                <td class="py-3 text-center text-gray-600 font-medium">{{ $fee->InstallmentsPaid }}</td>
                                                <td class="py-3 text-right font-bold text-gray-900">Rs. {{ number_format($fee->RemainingBalance, 2) }}</td>
                                                <td class="py-3 text-right">
                                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider 
                                                        {{ $fee->Status === 'Paid' ? 'bg-green-100 text-green-700' : ($fee->Status === 'Partially Paid' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                                                        {{ $fee->Status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-200 flex justify-between items-center text-xs text-gray-500">
                        <span>Total active items listed: <strong>{{ count($feeRecords) }}</strong></span>
                        <span class="italic text-[11px]"><i class="fa-solid fa-circle-nodes"></i> Connected to SchoolFeeManagement Database Engine</span>
                    </div>

                </div>

            </div>
        @endif

    </div>

</body>
</html>