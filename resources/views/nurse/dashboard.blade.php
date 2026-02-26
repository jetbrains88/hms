@extends('layouts.app')

@section('title', 'Nurse Dashboard - NHMP HMS')
@section('page-title', 'Nurse Station')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Welcome Card -->
    <div class="lg:col-span-2 bg-gradient-to-r from-pink-500 to-pink-600 rounded-xl text-white p-6 shadow-lg">
        <h2 class="text-2xl font-bold mb-2">Nurse Station</h2>
        <p class="text-pink-100 mb-4">Monitor and record patient vitals</p>
        <div class="flex items-center gap-4">
            <div class="bg-white/20 p-3 rounded-lg">
                <i class="fas fa-heartbeat text-2xl"></i>
            </div>
            <div>
                <p class="text-sm">Ready to record vitals</p>
                <p class="text-xl font-bold">Patient Care</p>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-bold text-gray-700 mb-4">Today's Stats</h3>
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Patients in Ward</span>
                <span class="font-bold text-blue-600">12</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Vitals to Record</span>
                <span class="font-bold text-yellow-600">5</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Medications Due</span>
                <span class="font-bold text-red-600">8</span>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-8 bg-white rounded-xl shadow p-6">
    <h3 class="font-bold text-gray-700 mb-4">Quick Actions</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="#" class="bg-blue-50 border border-blue-200 rounded-lg p-4 hover:bg-blue-100 transition">
            <div class="flex items-center gap-3">
                <div class="bg-blue-100 p-2 rounded">
                    <i class="fas fa-heartbeat text-blue-600"></i>
                </div>
                <div>
                    <p class="font-semibold text-blue-700">Record Vitals</p>
                    <p class="text-xs text-gray-500">Take patient measurements</p>
                </div>
            </div>
        </a>
        <a href="#" class="bg-green-50 border border-green-200 rounded-lg p-4 hover:bg-green-100 transition">
            <div class="flex items-center gap-3">
                <div class="bg-green-100 p-2 rounded">
                    <i class="fas fa-procedures text-green-600"></i>
                </div>
                <div>
                    <p class="font-semibold text-green-700">Ward Rounds</p>
                    <p class="text-xs text-gray-500">Check on patients</p>
                </div>
            </div>
        </a>
        <a href="#" class="bg-purple-50 border border-purple-200 rounded-lg p-4 hover:bg-purple-100 transition">
            <div class="flex items-center gap-3">
                <div class="bg-purple-100 p-2 rounded">
                    <i class="fas fa-pills text-purple-600"></i>
                </div>
                <div>
                    <p class="font-semibold text-purple-700">Medication</p>
                    <p class="text-xs text-gray-500">Administer medications</p>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Recent Vitals -->
<div class="mt-6 bg-white rounded-xl shadow p-6">
    <h3 class="font-bold text-gray-700 mb-4">Recent Vitals Recorded</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50">
                    <th class="p-3 text-left">Patient</th>
                    <th class="p-3 text-left">BP</th>
                    <th class="p-3 text-left">Temp</th>
                    <th class="p-3 text-left">Pulse</th>
                    <th class="p-3 text-left">Time</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b">
                    <td class="p-3">Ali Ahmed</td>
                    <td class="p-3">120/80</td>
                    <td class="p-3">37.2°C</td>
                    <td class="p-3">72</td>
                    <td class="p-3">10:30 AM</td>
                </tr>
                <tr class="border-b">
                    <td class="p-3">Fatima Khan</td>
                    <td class="p-3">130/85</td>
                    <td class="p-3">36.8°C</td>
                    <td class="p-3">68</td>
                    <td class="p-3">11:15 AM</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection