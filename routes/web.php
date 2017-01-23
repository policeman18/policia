<?php

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

Route::get('/', function () {
    return view('welcome');
})->middleware('guest');;

// Desactivadas para activar la ruta de Steam
// Auth::routes();

// TODO limitar acceso
Route::get('/login', 'AuthController@login')->name('login');
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');

// TODO limitar acceso
Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'frecuencias'], function() {
	Route::get('/', 'FrequencyController@index')->name('frequencies');
	Route::post('/regenerar', 'FrequencyController@new')->name('frequencies_new');
});

// TODO limitar acceso
Route::group(['prefix' => 'post'], function() {
	Route::get('/new', 'PostController@newForm');
	Route::post('/new', 'PostController@new');
});

// TODO limitar acceso
Route::get('/lista', 'UserController@users')->name('users');

Route::group(['prefix' => 'usuario'], function() {
	Route::get('/{id}', 'UserController@profile')->name('user_profile');
});

// TODO limitar acceso
Route::get('/about', 'HomeController@about')->name('about');


// TODO limitar acceso
Route::group(['prefix' => 'admin', 'middleware' => ['admin_only', 'auth']], function() {
	CRUD::resource('user', 'Admin\UserCrudController');
	CRUD::resource('specialty', 'Admin\SpecialtyCrudController');
});

// TODO limitar acceso
Route::group(['prefix' => 'especializacion'], function() {
	Route::get('/', 'SpecialtyController@list')->name('specialties');
	Route::get('/{id}', 'SpecialtyController@view')->name('specialty-view');
});

// TODO limitar acceso
Route::get('/freq/new', 'FrequencyController@generate')->name('freq-new');

// DEBUG
Route::get('/debug', function() {
	abort('403')
	$geo = new \App\Specialty;
	$geo->name = "Grupo Especial de Operaciones";
	$geo->acronym = "GEO";
	$geo->secret = true;
	$geo->save();

	$sap = new \App\Specialty;
	$sap->name = "Servicio Aéreo Policial";
	$sap->acronym = "SAP";
	$sap->secret = false;
	$sap->save();

	$atgc = new \App\Specialty;
	$atgc->name = "Agrupación de Tráfico de la Guardia Civil";
	$atgc->acronym = "ATGC";
	$atgc->secret = false;
	$atgc->save();

	$udev = new \App\Specialty;
	$udev->name = "Unidad de Delincuencia Especializada y Violenta";
	$udev->acronym = "UDEV";
	$udev->secret = true;
	$udev->save();

	$instr = new \App\Specialty;
	$instr->name = "Escuela Nacional de Policía";
	$instr->acronym = "Instrucción";
	$instr->secret = false;
	$instr->save();

	$ai = new \App\Specialty;
	$ai->name = "Unidad de Asuntos Internos";
	$ai->acronym = "UAI";
	$ai->secret = false;
	$ai->save();

	$freq = new \App\Frequency;
	$freq->content = [];
	$freq->save();

	return redirect('/home');
});


/** CATCH-ALL ROUTE for Backpack/PageManager - needs to be at the end of your routes.php file  **/
Route::get('{page}/{subs?}', ['uses' => 'PageController@index'])
    ->where(['page' => '^((?!admin).)*$', 'subs' => '.*']);