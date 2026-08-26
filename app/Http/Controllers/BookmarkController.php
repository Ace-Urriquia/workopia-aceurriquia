<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use App\Models\Job;

class BookmarkController extends Controller
{
    //Get all users bookmarks
    // route GET request bookmarks

    public function index(): View {
    $user = Auth::user();

    $bookmarks = $user->bookmarkedJobs()->orderBy('job_user_bookmarks.created_at','desc')->paginate(9);
    return view ('jobs.bookmarked')->with('bookmarks',$bookmarks);
    }

    //Get create new bookmarked job
    // route POST/ bookmarks/{job}

    public function store(Job $job): RedirectResponse {
       
    $user = Auth::user();

   //Check if the job is already bookmarked

   if($user->bookmarkedJobs()->where('job_id',$job->id)->exists()){
    return back()->with('error','Job is already bookmarked');
   }

   //Create new bookmark
   $user->bookmarkedJobs()->attach($job->id);

   return back()->with('success','Job bookmarked successfully');

    }


    //Get all Remove bookmarks jobs
    // DELETE bookmarks/{jobs}



    public function destroy(Job $job): RedirectResponse {
    $user = Auth::user();

   //Check if the job is  not  bookmarked

   if(!$user->bookmarkedJobs()->where('job_id',$job->id)->exists()){
    return back()->with('error','Job is not bookmarked');
   }

   //remove  bookmark
   // detach is used to delete unlike the attack used attach
   $user->bookmarkedJobs()->detach($job->id);

   return back()->with('success','bookmark removed successfully');

    }

    
}
