@extends('layouts.app')

@section('title', 'E-Consultancy')
@section('page-title', 'Online Consultations')
@section('breadcrumb', 'Doctor / E-Consultancy')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-100 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
            <h2 class="text-2xl font-bold mb-2">Tele-Health Consultation</h2>
            <p class="text-blue-100">Connect with your patients remotely through video conferencing</p>
        </div>
        
        <div class="p-12 text-center space-y-8">
            <div class="w-32 h-32 bg-blue-100 rounded-full flex items-center justify-center mx-auto text-blue-600">
                <i class="fas fa-video text-5xl"></i>
            </div>
            
            <div class="max-w-md mx-auto">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Coming Soon</h3>
                <p class="text-gray-600 mb-8">
                    We are currently integrating with Zoom and Twilio to provide high-quality video consultations. This feature will be available in the next update.
                </p>
                
                <div class="flex flex-col gap-4">
                    <button disabled class="w-full px-6 py-3 bg-gray-200 text-gray-400 rounded-xl font-bold flex items-center justify-center gap-3">
                        <i class="fas fa-plug text-sm"></i>
                        Connect Video SDK
                    </button>
                    <a href="{{ route('doctor.dashboard') }}" class="w-full px-6 py-3 bg-white border-2 border-indigo-600 text-indigo-600 rounded-xl font-bold hover:bg-indigo-50 transition-colors">
                        Return to Dashboard
                    </a>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-50 p-6 border-t border-gray-100 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center text-green-600">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="text-sm">
                    <p class="font-bold text-gray-900">Secure</p>
                    <p class="text-gray-500">End-to-end encrypted</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600">
                    <i class="fas fa-file-medical"></i>
                </div>
                <div class="text-sm">
                    <p class="font-bold text-gray-900">Integrated</p>
                    <p class="text-gray-500">Syncs with EMR records</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center text-orange-600">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="text-sm">
                    <p class="font-bold text-gray-900">Scheduled</p>
                    <p class="text-gray-500">Automatic appointments</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
