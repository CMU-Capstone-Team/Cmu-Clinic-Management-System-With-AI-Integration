@extends('layouts.student-layout')

@section('title', 'Student Dashboard | CMU Alaga')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        
        {{-- Welcome Header --}}
        <div class="bg-white shadow rounded-lg p-6 mb-6 border-l-4 border-blue-600">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                Welcome, {{ auth()->user()->name }}! 👋
            </h1>
            <p class="text-gray-600">
                You are now logged in to your student portal. 
                Your account status is 
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Active
                </span>
            </p>
            <p class="text-sm text-gray-500 mt-2">
                Student Number: <strong>{{ auth()->user()->student_number }}</strong>
            </p>
        </div>

        {{-- Quick Actions Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            {{-- My Profile Card --}}
            <a href="{{ route('student.profile') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition border border-gray-100 group">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-50 p-3 rounded-full group-hover:bg-blue-100 transition">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">My Profile</h3>
                        <p class="text-sm text-gray-500">View your information</p>
                    </div>
                </div>
            </a>

            {{-- Clinic Visits Card --}}
            <a href="{{ route('student.visits') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition border border-gray-100 group">
                <div class="flex items-center gap-4">
                    <div class="bg-emerald-50 p-3 rounded-full group-hover:bg-emerald-100 transition">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Clinic Visits</h3>
                        <p class="text-sm text-gray-500">Check visit history</p>
                    </div>
                </div>
            </a>

            {{-- Medical Records Card --}}
            <a href="{{ route('student.records') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition border border-gray-100 group">
                <div class="flex items-center gap-4">
                    <div class="bg-purple-50 p-3 rounded-full group-hover:bg-purple-100 transition">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Medical Records</h3>
                        <p class="text-sm text-gray-500">Access health records</p>
                    </div>
                </div>
            </a>

        </div>

        {{-- Important Notice --}}
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="font-semibold text-yellow-800">Important Reminder</h4>
                    <p class="text-sm text-yellow-700 mt-1">
                        Please update your medical profile if there are any changes in your health condition or allergies.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection