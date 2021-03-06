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
Route::group(['prefix' => 'user'], function() {
    Route::get('', 'User\UserController@getUserById');
    Route::get('/all', 'User\UserController@getAll');
    Route::post('/login', 'User\UserController@login');
    Route::delete('/logout', 'User\UserController@logout');
    Route::post('/password', 'User\UserController@changePassword');
    Route::post('/register', 'User\UserController@register');
    Route::put('/update', 'User\UserController@updateUser');
    Route::get('/question', 'Question\QuestionController@getAllQuestionByUserId');
});

Route::group(['prefix' => 'question'], function() {
    Route::get('/topic/class_id={id}', 'Question\QuestionController@getAllTopic');
    Route::get('/level', 'Question\QuestionController@getAllLevel');
    Route::get('/class', 'Question\QuestionController@getAllClass');
    Route::post('/add', 'Question\QuestionController@addQuestion');
    Route::get('/topic={id}', 'Question\QuestionController@getQuestionByTopic');
    Route::get('/level={id}', 'Question\QuestionController@getQuestionByLevel');
    Route::get('/class={id}', 'Question\QuestionController@getQuestionByClass');
    Route::put('/update', 'Question\QuestionController@updateQuestion');
    Route::get('/question={id}','Question\QuestionController@getQuestionByQuestionId');
});

Route::group(['prefix' => 'quizz'], function() {
    Route::get('/all', 'Quiz\QuizzController@getAllQuiz');
    Route::get('/', 'Quiz\QuizzController@getAllQuizByUserId');
    Route::post('/create', 'Quiz\QuizzController@createQuizz');
    Route::get('/start/{id}', 'Quiz\QuizzController@startQuizz');
    Route::get('/answer/{id}', 'Quiz\QuizzController@getAnswer');
    Route::post('/answer','Quiz\QuizzController@userAnswerQuestion');
    Route::get('/numberQuest/{id}','Quiz\QuizzController@getNumberQuestion');
    Route::get('/getHistoryScore/{id}','Quiz\QuizzController@getHistoryScore');
    Route::get('/getQuizzScore/{id}','Quiz\QuizzController@getQuizzScore');
    Route::get('/getHistory','Quiz\QuizzController@getHistory');
    Route::get('/getHistoryDetail/quizzId={id}','Quiz\QuizzController@getHistoryDetail');
    Route::get('/getHistoryAnswer/historyId={id}','Quiz\QuizzController@getHistoryAnswer');
    Route::get('/test/{id}','Quiz\QuizzController@test');
    Route::get('/getHistoryAnswerDetail/historyId={id}','Quiz\QuizzController@getHistoryAnswerDetail');   
});

Route::group(['prefix' => 'admin'],function(){
    Route::get('/public','Admin\AdminController@getQuestionIsPublic');
    Route::get('/nonPublic','Admin\AdminController@getQuestionNonPublic');
    Route::get('/public/{id}','Admin\AdminController@getQuestionIsPublicById');
    Route::get('/nonPublic/{id}','Admin\AdminController@getQuestionNonPublicById');
    Route::get('/userByRole/{id}','Admin\AdminController@getUserByRole');
});




