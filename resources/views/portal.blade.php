<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduNexus Portal Gateway</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#1e2538] min-h-screen flex flex-col justify-between p-6">

    <header class="max-w-6xl w-full mx-auto bg-[#f0f4f8] rounded-2xl p-4 flex justify-between items-center shadow-lg">
        <div class="flex items-center gap-2 text-[#1e2538] font-bold text-lg tracking-wide">
            <span class="bg-[#f26e22] text-white p-1.5 rounded-lg text-sm">EN</span>
            SCHOOLM | <span class="text-gray-500 font-normal text-sm">MANAGEMENT SYSTEM</span>
        </div>
        <span class="text-xs font-semibold bg-green-100 text-green-700 px-3 py-1 rounded-full border border-green-300">
            <i class="fa-solid fa-bolt mr-1"></i> Direct Access Mode
        </span>
    </header>

    <main class="max-w-6xl w-full mx-auto my-auto py-12">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold text-white tracking-tight sm:text-4xl">Select an Interface Role</h1>
            <p class="mt-2 text-sm text-gray-400">Instantly launch any view to review schema-matched management controls.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <a href="{{ route('account.dashboard') }}" class="group bg-[#f0f4f8] p-6 rounded-2xl shadow-md border-b-4 border-blue-500 hover:translate-y-[-4px] transition-all duration-200 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 text-xl group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <h3 class="text-lg font-bold text-[#1e2538] mt-4">Account Section</h3>
                    <p class="text-xs text-gray-500 mt-1">Manage FeeStructures, tracking StudentFees, Dues, and Receipts.</p>
                </div>
                
            <a href="{{ route('student.dashboard') }}" class="group bg-[#f0f4f8] p-6 rounded-2xl shadow-md border-b-4 border-indigo-500 hover:translate-y-[-4px] transition-all duration-200 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 text-xl group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-user-graduate"></i>
                    </div>
                    <h3 class="text-lg font-bold text-[#1e2538] mt-4">Student View</h3>
                    <p class="text-xs text-gray-500 mt-1">Personal profile details, remaining balance statements, and scholarships.</p>
                </div>
                

            <a href="{{ route('admin.dashboard') }}" class="group bg-[#f0f4f8] p-6 rounded-2xl shadow-md border-b-4 border-orange-500 hover:translate-y-[-4px] transition-all duration-200 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center text-[#f26e22] text-xl group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-sliders"></i>
                    </div>
                    <h3 class="text-lg font-bold text-[#1e2538] mt-4">Admin Dashboard</h3>
                    <p class="text-xs text-gray-500 mt-1">Configure structural schema entities: Classes, Sections, and Fine systems.</p>
                </div>
                
            </a>

            <a href="{{ route('teacher.dashboard') }}" class="group bg-[#f0f4f8] p-6 rounded-2xl shadow-md border-b-4 border-emerald-500 hover:translate-y-[-4px] transition-all duration-200 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 text-xl group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-chalkboard-user"></i>
                    </div>
                    <h3 class="text-lg font-bold text-[#1e2538] mt-4">Teacher View</h3>
                    <p class="text-xs text-gray-500 mt-1">View assigned Class profiles, track Student rosters, and process parameters.</p>
                </div>
                
            <a href="{{ route('principal.dashboard') }}" class="group bg-[#f0f4f8] p-6 rounded-2xl shadow-md border-b-4 border-purple-500 hover:translate-y-[-4px] transition-all duration-200 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600 text-xl group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <h3 class="text-lg font-bold text-[#1e2538] mt-4">Principal Metrics</h3>
                    <p class="text-xs text-gray-500 mt-1">High-level financial summaries, enrollment statistics, and total metrics.</p>
                </div>
               
            </a>

            <div class="bg-[#191e2e] p-6 rounded-2xl shadow-inner border border-gray-700/60 flex flex-col justify-between opacity-85">
                <div>
                    <div class="w-12 h-12 bg-gray-800 rounded-xl flex items-center justify-center text-gray-400 text-xl">
                        <i class="fa-solid fa-database"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-300 mt-4">Database Live Hook</h3>
                    <p class="text-xs text-gray-400 mt-1">Active SQL Schema tables ready: Student, Parent, FeeStructure, and Terms.</p>
                </div>
                <span class="text-gray-500 text-xs mt-4 italic"><i class="fa-solid fa-link-slash"></i> Bypass Authentication Enabled</span>
            </div>

        </div>
    </main>

    <footer class="text-center text-xs text-gray-500 py-4 border-t border-gray-800">
        EduNexus Management Dashboard Prototype Workspace
    </footer>

</body>
</html>