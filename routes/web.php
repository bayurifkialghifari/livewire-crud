<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false,
]);

Route::get('/', function () {
    return view('welcome');
});



// Admin Routes
Route::group([
    'middleware' => ['web', 'auth', 'role:admin'],
    'prefix' => 'admin',
    'as' => 'admin.',
], function() {

    Route::get('/', App\Http\Livewire\Admin\Dashboard::class);

    // BREAD
    Route::get('/bread', App\Http\Livewire\Admin\Bread::class);
    Route::get('/bread/create', App\Http\Livewire\Admin\BreadCreateUpdate::class);

    // Settings
    Route::get('/setting-role', App\Http\Livewire\Admin\Settings\Role::class);
    Route::get('/setting-role/menu-access/{id}', App\Http\Livewire\Admin\Settings\Role\MenuAccess::class);
    Route::get('/setting-menu', App\Http\Livewire\Admin\Settings\Menu::class);
    Route::get('/setting-menu/sub-menu/{id}', App\Http\Livewire\Admin\Settings\Menu\SubMenu::class);
    Route::get('/setting-user', App\Http\Livewire\Admin\Settings\User::class);

});

