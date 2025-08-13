<?php

use Illuminate\Support\Facades\Route;


// Auth Routes

Route::get('/', function () { return view('auth.login'); })->name('login');

Route::get('/forgot-password', function () { return view('auth.forgot-password'); })->name('forgot.password');


// Main Routes

Route::get('/dashboard', function () { return view('pages.dashboard'); });

Route::get('/calendar', function () { return view('pages.calendar'); });

Route::get('/profile', function () { return view('pages.profile'); });

// Other Routes

// User Administration

Route::get('/view-users', function () { return view('pages.users.view'); });

// Meetings
Route::get('/view-meetings', function () { return view('pages.meetings.view'); });
Route::get('/schedule-meeting', function () { return view('pages.meetings.schedule-meeting'); });


