<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('auth.login');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/dashboard/events', [App\Http\Controllers\HomeController::class, 'events'])->name('dashboard.events')->middleware('auth');

Route::fallback(function () {
    return view('404');
});

Route::middleware('isSupervisor')->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    // Route::delete('users/{id}/destroy', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('users/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
});

Route::post('users/ganti-password', [UserController::class, 'gantiPassword'])->name('users.ganti-password');
Route::resource('users', UserController::class)->middleware('isSupervisor');
Route::post('user-update-role', [UserController::class, 'updateRole'])->name('users.update-role');

// Public/front map (full-screen map with markers)
Route::get('/front_rencana_kegiatan', [App\Http\Controllers\RencanaKegiatanController::class, 'frontIndex'])->name('rencana_kegiatan.front');

Route::middleware('auth')->group(function () {
    Route::get('/rencana_kegiatan', [App\Http\Controllers\RencanaKegiatanController::class, 'index'])->name('rencana_kegiatan.index');
    Route::get('/rencana_kegiatan/create', [App\Http\Controllers\RencanaKegiatanController::class, 'create'])
        ->middleware('isAdmin')
        ->name('rencana_kegiatan.create');
    Route::post('/rencana_kegiatan', [App\Http\Controllers\RencanaKegiatanController::class, 'store'])
        ->middleware('isAdmin')
        ->name('rencana_kegiatan.store');

    // Routes that need authorization checks (show, update, destroy)
    Route::get('/rencana_kegiatan/{rencana_kegiatan}', [App\Http\Controllers\RencanaKegiatanController::class, 'show'])->name('rencana_kegiatan.show');
    Route::get('/rencana_kegiatan/{rencana_kegiatan}/edit', [App\Http\Controllers\RencanaKegiatanController::class, 'edit'])->name('rencana_kegiatan.edit');
    Route::put('/rencana_kegiatan/{rencana_kegiatan}', [App\Http\Controllers\RencanaKegiatanController::class, 'update'])->name('rencana_kegiatan.update');
    Route::post('/rencana_kegiatan/{rencana_kegiatan}/update-status', [App\Http\Controllers\RencanaKegiatanController::class, 'updateStatus'])->name('rencana_kegiatan.updateStatus');

    // Admin Action Routes for Rencana Kegiatan
    Route::put('/rencana_kegiatan/{id}/setujui', [App\Http\Controllers\RencanaKegiatanController::class, 'setujuiRencana'])->name('rencana_kegiatan.setujui');
    Route::put('/rencana_kegiatan/{id}/revisi', [App\Http\Controllers\RencanaKegiatanController::class, 'revisiRencana'])->name('rencana_kegiatan.revisi');
    Route::put('/rencana_kegiatan/{id}/tolak', [App\Http\Controllers\RencanaKegiatanController::class, 'tolakRencana'])->name('rencana_kegiatan.tolak');
    
    // Member Action Routes for Rencana Kegiatan
    Route::put('/rencana_kegiatan/{id}/ajukan', [App\Http\Controllers\RencanaKegiatanController::class, 'ajukanRencana'])->name('rencana_kegiatan.ajukan');

    Route::delete('/rencana_kegiatan/{rencana_kegiatan}', [App\Http\Controllers\RencanaKegiatanController::class, 'destroy'])->name('rencana_kegiatan.destroy');

    // Export Excel & PDF routes (supervisor only)
    Route::get('/rencana_kegiatan/export/excel', [App\Http\Controllers\RencanaKegiatanController::class, 'exportExcel'])
        ->middleware('isSupervisor')
        ->name('rencana_kegiatan.export.excel');
        
    Route::get('/rencana_kegiatan/{rencana_kegiatan}/export/pdf', [App\Http\Controllers\RencanaKegiatanController::class, 'exportPdf'])
        ->name('rencana_kegiatan.export.pdf');
});

// Laporan Kegiatan Routes
Route::middleware('auth')->group(function () {
    // Create route (admin only but needs to be outside to accept query parameter)
    Route::get('/laporan_kegiatan/create', [App\Http\Controllers\LaporanKegiatanController::class, 'create'])
        ->middleware('isAdmin')
        ->name('laporan_kegiatan.create');

    // Admin only routes (store)
    Route::middleware('isAdmin')->group(function () {
        Route::post('/laporan_kegiatan', [App\Http\Controllers\LaporanKegiatanController::class, 'store'])->name('laporan_kegiatan.store');
    });

    // Route untuk aksi verifikasi laporan oleh supervisor
    Route::put('/laporan_kegiatan/{id}/terima', [App\Http\Controllers\LaporanKegiatanController::class, 'terimaLaporan'])->name('laporan_kegiatan.terima');
    Route::put('/laporan_kegiatan/{id}/revisi', [App\Http\Controllers\LaporanKegiatanController::class, 'revisiLaporan'])->name('laporan_kegiatan.revisi');

    // Resource routes for show, edit, update, destroy (with UUID)
    Route::resource('laporan_kegiatan', App\Http\Controllers\LaporanKegiatanController::class)->except(['index', 'create', 'store']);

    // Both admin and supervisor can view index
    Route::get('/laporan_kegiatan', [App\Http\Controllers\LaporanKegiatanController::class, 'index'])->name('laporan_kegiatan.index');

    // Print route (both admin and supervisor)
    Route::get('/laporan_kegiatan/{laporanKegiatan}/print', [App\Http\Controllers\LaporanKegiatanController::class, 'print'])->name('laporan_kegiatan.print');
});

// Notification Routes
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/read/{id}', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read/{id}', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read.post');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::delete('/notifications/delete-all', [App\Http\Controllers\NotificationController::class, 'deleteAll'])->name('notifications.deleteAll');
    Route::get('/api/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
});
