<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BookController;

Route::get('/', function () {
    return view('login');
})->name('login-page');

Route::post('login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {

    //User Routes
    Route::get('/books', [BookController::class , 'index'])->name('user-dashboard');
    Route::post('/searchbooks', [BookController::class , 'search'])->name('search-books');

    //Admin Routes
    Route::get('/admin', [BookController::class , 'bookList'])->name('admin-dashboard');
    Route::get('/admin/create-book-page',  [BookController::class , 'createBookPage'])->name('create-page');
    Route::post('/admin/create-book', [BookController::class , 'create'])->name('create-book');
    Route::get('/admin/edit-book/{id}', [BookController::class , 'edit'])->name('edit-book');
    Route::post('/admin/update-book/{id}', [BookController::class , 'update'])->name('update-book');
});
