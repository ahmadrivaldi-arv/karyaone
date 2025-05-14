<?php

use App\Livewire\App\Attendance;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('filament.admin.auth.login');
});

Route::get('attendance', Attendance::class)->name('attendance');
