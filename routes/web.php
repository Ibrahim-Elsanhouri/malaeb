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
Route::get('/', function () {
    return view('welcome');
});
*/
/*
Route::get('/ibratest', function () {
    return view('ibratest');
});

Route::get('/map', function () {
    return view('map');
});

*/ 




// Route::get('/{slug}', [
//     'uses' => 'PageController@getPage' 
// ]);


/*
Route::get('/pop', function () {
    return view('bobup');
});
 */
Route::get('/playground','playgroundsController@web' );

//Route::resource('/palyground', 'Controller');

Route::get('/', 'HomeController@index')->name('home');

Route::resource('articles', 'articlesController');

// Route::post('contact-us', ['as'=>'contactus.store','uses'=>'ContactUSController@contactUSPost']);

Route::get('/playgrounds/{id}', 'playgroundsController@display');

Route::get('/contact-us', function () {
    return view('contact-us');
});


Route::resource('teams', 'teamsController');

Route::resource('users', 'UsersController');

Route::resource('pgNews', 'pg_newsController');

Route::resource('settings', 'settingsController');

Route::resource('playgrounds', 'playgroundsController');

Route::resource('pgtimes', 'pgtimesController');

Route::resource('homedatas', 'homedataController');

Route::resource('statistics', 'statisticsController');

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
//Auth::routes();

Route::get('/dashboard','PostController@index');
Route::get('/blog',['as' => 'blog', 'uses' => 'PostController@index']);


Route::get('/admin', function () {
    return view("");
});

Route::post('/reset-password','UsersController@passwordFrom');
Route::get('/reset-password','UsersController@passwordFrom');
Route::get('/confirm','UsersController@confirmUser');
Route::post('/confirm','UsersController@confirmUser');


Auth::routes();


Route::get('/blog', 'PostController@index')->name('blog');

// check for logged in user
Route::group(['middleware' =>  'auth'], function()
{
    // show new post form
    Route::get('new-post','PostController@create');
    // save new post
    Route::post('new-post','PostController@store');
    // edit post form
    Route::get('edit/{slug}','PostController@edit');
    // update post
    Route::post('update','PostController@update')->name('update');
    // delete post
    Route::get('delete/{id}','PostController@destroy');
    // display user's all posts
    Route::get('my-all-posts','PostController@user_posts_all');
    // display user's drafts
    Route::get('my-drafts','PostController@user_posts_draft');
    // add comment
    Route::post('comment/add','CommentController@store')->name('AddComment');
    // delete comment
    Route::post('comment/delete/{id}','CommentController@distroy');
    Route::get('profile/{id}', 'UsersController@profile');
    Route::get('reservation/delete/{id}', 'UsersController@DeleteResevation');
});

Route::resource('reservation', 'reservationsController');


//users profile
Route::get('user/{id}','PostController@profile')->where('id', '[0-9]+');
// display list of posts
Route::get('user/{id}/posts','PostController@user_posts')->where('id', '[0-9]+');
// display single post
// 


Route::get('offers','PageController@offers')->name('offers');
Route::get('terms','PageController@terms');
Route::get('agreement','PageController@agreement');
Route::get('privacy','PageController@privacy');
Route::get('about-us','PageController@about_us');



Route::get('blog/{slug}',['as' => 'post', 'uses' => 'PostController@show'])->where('slug', '[A-Za-z0-9-_]+');

Route::get('edit-profile/{id}','UsersController@edit_web');
Route::post('edit-profile/{id}','UsersController@edit_web');

Route::post('booknow','playgroundsController@bookTime');

Route::post('contactus','HomeController@contact_us')->name('contact-us');
// Route::get('/{slug}', 'PageController@getPage')->where('slug', '^('.CustomHelper::pagesSlug().')');