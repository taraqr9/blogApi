<?php

use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register',[UserController::class,'register']);
Route::post('/login',[UserController::class,'login']);

// login with social credential
Route::get('/login/github',[UserController::class,'redirectSocialLogin']);
Route::get('/login/github-callback',[UserController::class,'callbackSocialLogin']);


Route::group(["middleware"=>["auth:sanctum"]], function(){
    // User
    Route::get('/profile',[UserController::class,'profile']);
    Route::get('/logout',[UserController::class,'logout']);

    // Blog
    Route::post('/blog/create',[BlogController::class,'create']);
    Route::get('/blogs',[BlogController::class,'blogs']);
    Route::get('/blog/{id}',[BlogController::class,'singleBlog']);
    Route::delete('/blog/{id}',[BlogController::class,'delete']);

    // Comment
    Route::post('/comment/create',[CommentController::class,'create']);
    Route::get('/comments',[CommentController::class,'comments']);
    Route::get('/comment/{id}',[CommentController::class,'singleComment']);
    Route::delete('/comment/{id}',[CommentController::class,'delete']);
});
