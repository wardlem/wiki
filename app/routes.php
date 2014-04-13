<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Model Bindings
|--------------------------------------------------------------------------
|
*/

Route::model('user', 'User');
Route::model('comment', 'Comment');
Route::bind('page', function($value, $route){
    if (is_numeric($value)){
        return Page::find($value);
    }
    return Page::where('slug', 'LIKE', $value)->first();
});

/*
|--------------------------------------------------------------------------
| Home Page Route
|--------------------------------------------------------------------------
|
*/

Route::get('/', array('as' => 'home', 'uses' => 'PageController@homePage'))
    ->before('auth');

/*
|--------------------------------------------------------------------------
| Pages Route
|--------------------------------------------------------------------------
|
*/

$pageTypes = PageType::all()->each(function($type){
    Route::get($type->url_prefix . '/{page}/{tab?}', array('as' => $type->url_prefix, 'uses' => 'PageController@page'))
        ->before('auth')
        ->where('tab', '(content|revisions|discussion)');
});



/*
|--------------------------------------------------------------------------
| Comment Routes
|--------------------------------------------------------------------------
|
*/

Route::group(array('prefix' => 'comment', 'before' => 'auth'), function(){
    Route::post('{page}', array('as' => 'post_comment', 'uses' => 'CommentController@postComment'))
        ->where('page', '[0-9]+');

    Route::post('{comment}/reply', array('as' => 'comment_reply', 'uses' => 'CommentController@replyToComment'));

    Route::put('{comment}', array('as' => 'update_comment', 'uses' => 'CommentController@updateComment'));

    Route::delete('{comment}', array('as' => 'delete_comment', 'uses' => 'CommentController@deleteComment'));
});



/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
*/

Route::get('login', array('as' => 'login', function(){
    return View::make('login');
}))->before('guest');

Route::post('login', array('as' => 'login_check', function(){

    if ( ! Input::has('email') || ! Input::has('password')){
        Session::flash('login_error', 'The server didn\'t receive the proper data.');
    } else if (Auth::attempt(array('email' => Input::get('email'), 'password' => Input::get('password')))){
        return Redirect::intended('dashboard');
    } else {
        Session::flash('login_error', 'Login failed yo.');
    }
    return View::make('login');

}))->before('csrf');

Route::get('logout', array('as' => 'logout', function(){
    Auth::logout();
    Session::flash('logout_success', 'You have successfully logged out.');
    return Redirect::guest('/');
}))->before('auth');

/*
|--------------------------------------------------------------------------
| Utility Routes
|--------------------------------------------------------------------------
|
*/
Route::get('foundation', function(){
    return View::make('foundationhtml');
});