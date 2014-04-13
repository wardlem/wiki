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

Route::get('/', array('as' => 'home', 'uses' => 'PageController@homePage'))->before('auth');

Route::get('page/{slug}', array('as' => 'page', 'uses' => 'PageController@page'))->before('auth');

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


Route::get('foundation', function(){
    return View::make('foundationhtml');
});