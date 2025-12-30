@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="flex items-center text-gray-500 dark:text-gray-400">
        <a href="{{ route('dashboard') }}" class="hover:text-brand-500">Home</a>
        <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </li>
    <li class="text-brand-500">Dashboard</li>
@endsection

@section('content')
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
        <!-- Stats Cards -->
        <x-ui.card>
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-brand-50 dark:bg-brand-500/15">
                    <svg class="w-6 h-6 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Users</p>
                    <h4 class="text-2xl font-bold text-gray-800 dark:text-white">1,234</h4>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card>
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-success-50 dark:bg-success-500/15">
                    <svg class="w-6 h-6 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Active Sessions</p>
                    <h4 class="text-2xl font-bold text-gray-800 dark:text-white">567</h4>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card>
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-warning-50 dark:bg-warning-500/15">
                    <svg class="w-6 h-6 text-warning-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pending Tasks</p>
                    <h4 class="text-2xl font-bold text-gray-800 dark:text-white">89</h4>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card>
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-error-50 dark:bg-error-500/15">
                    <svg class="w-6 h-6 text-error-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Issues</p>
                    <h4 class="text-2xl font-bold text-gray-800 dark:text-white">12</h4>
                </div>
            </div>
        </x-ui.card>
    </div>

    <!-- Welcome Message -->
    <div class="mt-6">
        <x-ui.card title="Welcome to BMS Indonesia">
            <p class="text-gray-600 dark:text-gray-400">
                This dashboard has been migrated to TailAdmin. All features are now using Tailwind CSS and Alpine.js for a modern, responsive experience.
            </p>
        </x-ui.card>
    </div>
@endsection
