<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;

use App\Livewire\SuperAdmin\Dashboard\DashboardPage;
use App\Livewire\SuperAdmin\Staff\StaffManagement;
use App\Http\Controllers\SuperAdmin\BusinessController;
use App\Http\Controllers\SuperAdmin\RolesPermissionsController;
use App\Http\Controllers\SuperAdmin\SettingsController;

/*
|--------------------------------------------------------------------------
| Root redirect
|--------------------------------------------------------------------------
*/

Route::get("/", function () {
    return redirect()->route("super-admin.dashboard");
});

/*
|--------------------------------------------------------------------------
| Super Admin routes
|--------------------------------------------------------------------------
*/
Route::prefix("super-admin")
    ->name("super-admin.")
    ->group(function () {
        // ---- Guest-only --------------------------------------------------------
        Route::middleware("guest")->group(function () {
            Route::get("/login", Login::class)->name("login");
        });

        // ---- Authenticated Super Admin -----------------------------------------
        Route::middleware(["auth", "super_admin"])->group(function () {
            Route::get("/dashboard", DashboardPage::class)->name("dashboard");

            Route::get('/businesses', [BusinessController::class, 'index'])->name('businesses.index');
            Route::get('/businesses/create', [BusinessController::class, 'create'])->name('businesses.create');
            Route::post('/businesses', [BusinessController::class, 'store'])->name('businesses.store');
            Route::get('/businesses/{business}/edit', [BusinessController::class, 'edit'])->name('businesses.edit');
            Route::put('/businesses/{business}', [BusinessController::class, 'update'])->name('businesses.update');
            Route::post('/businesses/bulk-status', [BusinessController::class, 'bulkUpdateStatus'])->name('businesses.bulk-status');
            Route::delete('/businesses/delete', [BusinessController::class, 'destroy'])->name('businesses.destroy');
            Route::get("/staff", StaffManagement::class)->name("staff.index");

            // Roles
            Route::get("/roles", [RolesPermissionsController::class, 'index'])->name("roles.index");
            Route::post("/roles/toggle-permission", [RolesPermissionsController::class, 'togglePermission'])->name("roles.toggle-permission");

            
            Route::get('/settings',          [SettingsController::class, 'index'])->name('settings.index');
            Route::post('/settings/general',  [SettingsController::class, 'saveGeneral'])->name('settings.general');
            Route::post('/settings/features', [SettingsController::class, 'saveFeatures'])->name('settings.features');
            Route::post('/settings/billing',  [SettingsController::class, 'saveBilling'])->name('settings.billing');
        });

        // ---- Logout (auth only) ------------------------------------------------
        Route::post("/logout", function () {
            auth()->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect()->route("super-admin.login");
        })->name("logout")
            ->middleware("auth");
    });
