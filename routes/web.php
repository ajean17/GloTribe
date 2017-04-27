<?php
//SPLASH PAGE
Route::get('/', function ()
{
    return view('welcome');
});
Route::get('/about', function ()
{
    return view('about');
});
Route::get('/freeSmoke', function ()
{
    return view('welcome');
});

//AUTHORIZATION ROUTES
Route::get('/register','RegistrationController@create');
Route::get('/activation','RegistrationController@activation');
Route::post('/register','RegistrationController@store')->name('register');

Route::get('/login','SessionsController@create');
Route::post('/login','SessionsController@store');
Route::get('/logout','SessionsController@destroy');
Route::get('/forgotPassword','SessionsController@reset');
Route::get('/reclaim','SessionsController@reclaim');
//HOME DASHBOARD ROUTES
Route::get('/home', 'HomeController@index');
Route::get('/account/{User}', 'HomeController@account');
Route::get('/notifications/{User}', 'HomeController@notifications');

Route::get('/profile/{profileOwner}', 'ProfileController@show');
Route::get('/post/{id}', 'ProfileController@helpShow');
Route::get('/postings/{profileOwner}', 'ProfileController@postings');
Route::get('/network/{profileOwner}', 'ProfileController@network');
Route::get('/reviews/{profileOwner}', 'ProfileController@reviews');
Route::get('/search', 'ProfileController@search');

//MESSENGER ROUTES
Route::get('/inbox/{inboxOwner}','ConversationController@index');

//PHP PARSE ROUTES
Route::post('/friendSystem','ParseController@friend')->name('friend');;
Route::post('/blockSystem','ParseController@block')->name('block');
Route::post('/searchSystem','ParseController@search')->name('search');
Route::post('/messageSystem','ParseController@conversation')->name('message');
Route::post('/postSystem','ParseController@post')->name('post');
Route::post('/reviewSystem','ParseController@review')->name('review');
Route::post('/eventSystem','ParseController@events')->name('events');
Route::post('/profileSystem','ParseController@profile')->name('profile');
Route::get('/passwordSystem','ParseController@password');
Route::post('/photoSystem/{User}','ParseController@photoHandle');
Route::post('/imageSystem','ParseController@images');

//IMAGE PULLING
Route::get('images/{filename}', function ($filename)
{
    //$path = storage_path('app/public/images').'/'.$filename;
    $path = storage_path().'/app/public/images'.'/'.$filename;

    if(!File::exists($path)) abort(404);

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

Route::get('uploads/user/{user}/{type}/{filename}', function ($user,$type,$filename)
{
    //$path = storage_path('app/public/uploads/user').'/'.$user.'/'.$type.'/'.$filename;
    $path = storage_path().'/app/public/uploads/user'.'/'.$user.'/'.$type.'/'.$filename;

    if(!File::exists($path)) abort(404);

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

//Error Display Routes
Route::get('message', function ()
{
    return view('message');
});
