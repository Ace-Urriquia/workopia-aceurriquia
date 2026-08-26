<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\ApplicantController;


use App\Http\Middleware\LogRequest;
use GuzzleHttp\Middleware;
use SebastianBergmann\CodeUnit\FunctionUnit;
use Illuminate\Http\Request;
use illuminate\Http\Response; 



Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/jobs/search',[JobController::class,'search'])->name('jobs.search');

//Route::resource('jobs',JobController::class);
Route::resource('jobs',JobController::class)->Middleware('auth')->only(['create','edit','update','destroy']);
Route::resource('jobs',JobController::class)->except(['create','edit','update','destroy']);


Route::middleware('guest')->group(function(){

  Route::get('/register',[RegisterController::class, 'register'])->name('register');
  Route::post('/register',[RegisterController::class,'store'])->name('register.store');

  Route::get('/login',[LoginController::class,'login'])->name('login');
  Route::post('/login',[LoginController::class,'authenticate'])->name('login.authenticate');

});




Route::post('/logout',[LoginController::class,'logout'])->name('logout');


  Route::get('/dashboard',[DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

  Route::put('/profile',[ProfileController::class, 'update'])->name('profile.update')->middleware('auth');

Route::middleware('auth')->group(function(){
  Route::get('/bookmarks',[BookmarkController::class, 'index'])->name
  ('bookmarks.index');

  Route::post('/bookmarks/{job}',[BookmarkController::class, 'store'])->name
  ('bookmarks.store');

   Route::delete('/bookmarks/{job}',[BookmarkController::class, 'destroy'])->name
  ('bookmarks.destroy');

});

  Route::post('/jobs/{job}/apply',[ApplicantController::class,'store'])->name
  ('applicant.store')->middleware('auth');

  
  Route::delete('/applicants/{applicant}',[ApplicantController::class,'destroy'])->name
  ('applicant.destroy')->middleware('auth');




/*
Route::get('/jobs/create', function(){
    return view('jobs.create');
})->name('jobs.create');

*/


/*
Route::get('/jobs', function(){
  $title = 'Available Jobs using compact';
  $jobs = [
    'Web Developer',
    'Database Admin',
    'Software Engineer',
    'System Analyst'
  ];
    return view('jobs.index',compact('title','jobs'));
})->name('jobs');  

*/



/*
Route::get('/jobs', function(){
  $title = 'Available Jobs using string';
    return view('jobs.index')->with('title',$title);
})->name('jobs');
*/


/*
Route::get('/jobs', function(){
  $title = 'Available Jobs using string';
    return view('jobs.index')->with('title',$title);
})->name('jobs');
*/

/*
Route::get('/jobs', function(){
    return view('jobs.index')->with('title','Available Jobs using with');
})->name('jobs');

*/

/*

Route::get('/jobs', function(){
    return view('jobs.index',['title' => 'Available Jobs passing data from route to views']);
})->name('jobs');

*/








/*
Route::get('/notfound',function(){

return response('Page not Found',404);

});

Route::get('/test',function(){
return response()->json(['name' => 'John Doe'])->cookie('name','ace');
});


Route::get('/download',function (){

return response()->download(public_path('favicon.ico'));

});

Route::get('/read-cookie',function(Request $request){
  $cookieValue = $request->cookie('name');
  return response()->json(['cookie' => $cookieValue]);
});

*/

//Route::post('/submit',function(){

 //   return 'submitted';

//});

//Route::any('/submit',function(){
 //   return 'Submitted';
//});

//Route::get('/test',function(){
 //   $url = route('jobs');
  //  return "<a href='$url'> Click here </a>";
//});

//Route::get('api/user',function(){
  //  return [
   //     'name' => 'jonh doe',
   //     'email' => 'johndoe@gmail.com'
  //  ];

//});

//Route::get('/posts/{id}',function(string $id){
  // return 'Post' . $id;
//})->where('id','[0-9]+');

//Route::get('/posts/{id}/comments/{commentId}',function(string $id,string $commentId){
   // return 'Post'. $id . 'comment' .$commentId;
//});

//Route::get('/test1',function(Request $request){
 //   return[
   //     'method' => $request->method(),
    //    'url' => $request->url(),
    //    'path' => $request->path(),
    //    'fullUrl' => $request->fullUrl(),
     //   'ip' => $request->ip(),
     //   'userAgent' => $request->userAgent(),
     //   'Header' => $request->Header(),

  //  ];
//});
//Route::get('/users',function(Request $request){

 //   return $request->except(['name']);

//});
