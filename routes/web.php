<?php

use App\Http\Controllers\Config\StudentImageController;
use App\Http\Controllers\CorsightController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\MenuMiddleware;
use App\Http\Middleware\EventsMiddleware;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UsersRoleController;

Route::middleware(['auth', 'verified', MenuMiddleware::class, EventsMiddleware::class])->group(function () {
    // Dashboard
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('perfil/' . Auth::user(), [ProfileController::class, 'show'])->name('profile.show');
    Route::put('perfil/' . Auth::user(), [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/perfil/validate-request', [ProfileController::class, 'validateProfileRequest'])->name('profile.validateRequest');
    Route::post('/logout', [ProfileController::class, 'logout'])->name('profile.logout');

    // User
    Route::get('/usuarios', [UserController::class, 'index'])->name('user.index');
    Route::get('/usuarios/inserir', [UserController::class, 'create'])->name('user.create');
    Route::post('/usuarios/inserir', [UserController::class, 'store'])->name('user.store');
    Route::get('/usuarios/visualizar/{id}', [UserController::class, 'show'])->name('user.show');
    Route::get('/usuarios/editar/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/usuarios/editar/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/usuarios/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    Route::post('/usuarios/validate-request', [UserController::class, 'validateUserRequest'])->name('user.validateRequest');

    // Client
    Route::get('/clientes', [ClientController::class, 'index'])->name('client.index');
    Route::get('/clientes/inserir', [ClientController::class, 'create'])->name('client.create');
    Route::post('/clientes/inserir', [ClientController::class, 'store'])->name('client.store');
    Route::get('/clientes/visualizar/{id}', [ClientController::class, 'show'])->name('client.show');
    Route::get('/clientes/editar/{id}', [ClientController::class, 'edit'])->name('client.edit');
    Route::put('/clientes/editar/{id}', [ClientController::class, 'update'])->name('client.update');
    Route::delete('/clientes/{id}', [ClientController::class, 'destroy'])->name('client.destroy');
    Route::post('/clientes/validate-request', [ClientController::class, 'validateClientRequest'])->name('client.validateRequest');

    // School
    Route::get('/escolas', [SchoolController::class, 'index'])->name('school.index');
    Route::get('/escolas/inserir', [SchoolController::class, 'create'])->name('school.create');
    Route::post('/escolas/inserir', [SchoolController::class, 'store'])->name('school.store');
    Route::get('/escolas/visualizar/{id}', [SchoolController::class, 'show'])->name('school.show');
    Route::get('/escolas/editar/{id}', [SchoolController::class, 'edit'])->name('school.edit');
    Route::put('/escolas/editar/{id}', [SchoolController::class, 'update'])->name('school.update');
    Route::delete('/escolas/{id}', [SchoolController::class, 'destroy'])->name('school.destroy');
    Route::post('/escolas/validate-request', [SchoolController::class, 'validateSchoolRequest'])->name('school.validateRequest');

    // Student
    Route::get('/alunos', [StudentController::class, 'index'])->name('student.index');
    Route::get('/alunos/inserir', [StudentController::class, 'create'])->name('student.create');
    Route::post('/alunos/inserir', [StudentController::class, 'store'])->name('student.store');
    Route::get('/alunos/visualizar/{id}', [StudentController::class, 'show'])->name('student.show');
    Route::get('/alunos/editar/{id}', [StudentController::class, 'edit'])->name('student.edit');
    Route::put('/alunos/editar/{id}', [StudentController::class, 'update'])->name('student.update');
    Route::delete('/alunos/{id}', [StudentController::class, 'destroy'])->name('student.destroy');
    Route::post('/alunos/validate-request', [StudentController::class, 'validateStudentRequest'])->name('student.validateRequest');
    Route::post('/alunos/{student}/upload-photo', [StudentController::class, 'uploadPhoto'])->name('student.uploadPhoto');
    Route::get('/alunos/teste', [StudentController::class, 'listStudent'])->name('student.listStudent');

    // StudentImages
    Route::get('/upload-image/inserir', [StudentImageController::class, 'create'])->name('studentImage.create');
    Route::post('/upload-image/inserir', [StudentImageController::class, 'store'])->name('studentImage.store');
    Route::post('/upload-image/validate-request', [StudentImageController::class, 'validateStudentImageRequest'])->name('studentImage.validateRequest');

    // Roles
    Route::get('/perfis', [UsersRoleController::class, 'index'])->name('roles.index');
    Route::get('/perfis/inserir', [UsersRoleController::class, 'create'])->name('roles.create');
    Route::post('/perfis/inserir', [UsersRoleController::class, 'store'])->name('roles.store');
    Route::get('/perfis/visualizar/{usersRole}', [UsersRoleController::class, 'show'])->name('roles.show');
    Route::get('/perfis/editar/{usersRole}', [UsersRoleController::class, 'edit'])->name('roles.edit');
    Route::put('/perfis/editar/{usersRole}', [UsersRoleController::class, 'update'])->name('roles.update');
    Route::delete('/perfis/{usersRole}', [UsersRoleController::class, 'destroy'])->name('roles.destroy');

    // Menus
    Route::get('/config/menus', [MenuController::class, 'index'])->name('menus.index');
    Route::get('/config/menus/inserir', [MenuController::class, 'create'])->name('menus.create');
    Route::post('/config/menus/inserir', [MenuController::class, 'store'])->name('menus.store');
    Route::get('/config/menus/visualizar/{menu1}', [MenuController::class, 'show'])->name('menus.show');
    Route::get('/config/menus/editar/{menu1}', [MenuController::class, 'edit'])->name('menus.edit');
    Route::put('/config/menus/editar/{menu1}', [MenuController::class, 'update'])->name('menus.update');
    Route::delete('/config/menus/{menu1}', [MenuController::class, 'destroy'])->name('menus.destroy');

    // Submenus (Menus2)
    Route::get('/config/submenus/visualizar/{menu2}', [MenuController::class, 'showSubmenu'])->name('submenus.show');
    Route::get('/config/submenus/editar/{menu2}', [MenuController::class, 'editSubmenu'])->name('submenus.edit');
    Route::put('/config/submenus/editar/{menu2}', [MenuController::class, 'updateSubmenu'])->name('submenus.update');
    Route::delete('/config/submenus/{menu2}', [MenuController::class, 'destroySubmenu'])->name('submenus.destroy');

    // Sub-submenus (Menus3)
    Route::get('/config/subsubmenus/visualizar/{menu3}', [MenuController::class, 'showSubsubmenu'])->name('subsubmenus.show');
    Route::get('/config/subsubmenus/editar/{menu3}', [MenuController::class, 'editSubsubmenu'])->name('subsubmenus.edit');
    Route::put('/config/subsubmenus/editar/{menu3}', [MenuController::class, 'updateSubsubmenu'])->name('subsubmenus.update');
    Route::delete('/config/subsubmenus/{menu3}', [MenuController::class, 'destroySubsubmenu'])->name('subsubmenus.destroy');

    // Corsight
    Route::get('/corsight/watchlists', [CorsightController::class, 'listWatchlist'])->name('corsight.watchlist');
    Route::get('/corsight/pessoas', [CorsightController::class, 'listFaces'])->name('corsight.faces');
    Route::get('/corsight/faces-data', [CorsightController::class, 'getFacesData']);
});

// Corsight Events
Route::get('/corsight/powerbi-data', [CorsightController::class, 'getPowerBIData'])->name('corsight.powerbiData');

require __DIR__ . '/auth.php';
