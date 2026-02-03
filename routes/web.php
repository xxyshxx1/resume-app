<?php

use App\Models\ResumeSection;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/resume', function () {
    return ResumeSection::active()->ordered()->get();
});
