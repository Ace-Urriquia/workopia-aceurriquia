<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Applicant;
use App\Models\Job;
use App\Mail\JobApplied;
use Illuminate\Support\Facades\Mail;


class ApplicantController extends Controller
{
    // @description Store new job application
    // @route POST / jobs/{job}/apply

    public function store(Request $request, Job $job): RedirectResponse {

    // check if user has already applied
    $existingApplication = Applicant::where('job_id', $job->id)
    ->where('user_id',auth()->id())->exists();

    if($existingApplication){
        return redirect()->back()->with('error','You have already applied to this job');
        
    }

    //Validate incoming data

    $validatedData = $request->validate([
        'full_name' => 'required|string',
        'contact_phone' => 'string',
        'contact_email' => 'required|string|email',
        'message' => 'string',
        'location' => 'string',
        'resume' => 'required|file|mimes:pdf|max:2048',
        
        


    ]);

    //Handle resume uploud
    if($request->hasFile('resume')){
        $path = $request->file('resume')->store('resumes','public');
        $validatedData['resume_path'] = $path;
    }
    //Store the application
    $application = new Applicant($validatedData);
    $application->job_id = $job->id;
    $application->user_id = auth()->id();

    $application->save();

    // Send Email to owner
    Mail::to($job->user->email)->send(new JobApplied($application, $job));

    return redirect()->back()->with('success','Your application has been submitted');
    }

    //description Delete job applicant
    // route delete request/applicants/{applicant}

    public function destroy($id): RedirectResponse
    {
        $applicant = Applicant::findOrFail($id);

        $applicant->delete();

        return redirect()->route('dashboard')->with('success','Applicant Deleted successfully');
    }

}
