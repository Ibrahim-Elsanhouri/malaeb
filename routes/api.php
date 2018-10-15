<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*
$api = app('Dingo\Api\Routing\Router');
        $api->version('v1', function ($api) {
            $api->get('/', function() {
                return ['Fruits' => 'Delicious and healthy!'];
            });
        }); 
       */




Route::group(['middleware' => 'auth:api'], function()
{
	Route::resource('articles', 'articlesAPIController');
	Route::resource('teams', 'teamsAPIController');
	// Route::resource('users', 'usersAPIController');
	Route::get('users/{user_id}', 'usersAPIController@show');
	Route::post('users/login', 'usersAPIController@login');
	Route::post('users', 'usersAPIController@store');
	Route::post('users/confirm', 'usersAPIController@confirm');
	Route::post('users/logout', 'usersAPIController@logout');
	Route::post('users/resend-code', 'usersAPIController@resendConfirmation');
	Route::post('users/forget-password', 'usersAPIController@forgetPassword');
	Route::post('users/fb-login', 'usersAPIController@fbLogin');
	Route::post('users/profile', 'usersAPIController@userProfile');
	Route::post('users/logout', 'usersAPIController@logout');
	Route::post('users/change-password', 'usersAPIController@changePassword');
	Route::get('users/{user_id}/playgrounds', 'usersAPIController@userPlaygrounds');
	Route::post('users/points', 'usersAPIController@userPoints');

	Route::get('owners/playgrounds', 'ownersAPIController@listPlaygrounds');
	Route::get('owners/pgtimes', 'ownersAPIController@showPlayground');
	Route::get('owners/report', 'ownersAPIController@pgReports');

	Route::get('owners', 'ownersAPIController@index');

	Route::get('prizes', 'prizesAPIController@index');
	Route::resource('playgrounds', 'playgroundsAPIController');
	// Route::get('playgrounds/times/{id}', 'playgroundsAPIController@getTimes');
	Route::get('playgrounds/nearby/{lat}/{lng}', 'playgroundsAPIController@getNearby');
	Route::post('playgrounds/rating', 'playgroundsAPIController@makeRating');

	Route::get('reservations_by_player/{id}', 'reservationsAPIController@byplayer');
	Route::post('reservations/confirm', 'reservationsAPIController@setconfirm');
	Route::post('reservations/attendance', 'reservationsAPIController@setAttendance');
	Route::post('reservations/cancel', 'reservationsAPIController@cancelReservation');
	Route::resource('reservations', 'reservationsAPIController');

	Route::post('pgtimes/available', 'pgtimesAPIController@available');
	Route::resource('pgtimes', 'pgtimesAPIController');
	Route::resource('pgimages', 'pgimagesAPIController');

	Route::resource('cities', 'citiesAPIController');
	Route::get('cities/{id}', 'citiesAPIController@getCity');

	Route::post('search', 'searchAPIController@index');
	
	Route::resource('homedatas', 'homedataAPIController');

	Route::resource('pg_news', 'pg_newsAPIController');

	Route::post('fcm', 'fcmAPIserviceAPIController@store');

	Route::resource('settings', 'settingsAPIController');
	
	Route::resource('statistics', 'statisticsAPIController');

	Route::post('fcm-test', 'fcmAPIserviceAPIController@show');



});





