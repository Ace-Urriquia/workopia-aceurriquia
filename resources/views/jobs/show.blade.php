<x-layout>

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
<section class="md:col-span-3">
                    <div class="rounded-lg shadow-md bg-white p-3">
                        <div class="flex justify-between items-center">
                            <a
                                class="block p-4 text-blue-700"
                                href="{{route('jobs.index')}}"
                            >
                                <i class="fa fa-arrow-alt-circle-left"></i>
                                Back To Listings
                            </a>
                           @can('update',$job)
                            <div class="flex space-x-3 ml-4">
                                <a
                                    href="{{route('jobs.edit',$job->id)}}"
                                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded"
                                    >Edit</a
                                >
                                <!-- Delete Form -->
                                <form method="POST" action="{{route('jobs.destroy', $job->id) }}" onsubmit="return confirm('Are you sure that you want to delete this job')">
                                  @csrf
                                  @method('DELETE')
                                  <button
                                        type="submit"
                                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded"
                                    >
                                        Delete
                                    </button>
                                </form>
                                <!-- End Delete Form -->
                            </div>
                            @endcan
                        </div>
                        <div class="p-4">
                            <h2 class="text-xl font-semibold">
                                {{$job->title}}
                            </h2>
                            <p class="text-gray-700 text-lg mt-2">
                                {{$job->description}}
                            </p>
                            <ul class="my-4 bg-gray-100 p-4">
                                <li class="mb-2">
                                    <strong>Job Type:</strong> {{$job->job_type}}
                                </li>
                                <li class="mb-2">
                                    <strong>Remote:</strong> {{$job->remote ? 'Yes' : 'No' }}
                                </li>
                                <li class="mb-2">
                                    <strong>Salary:</strong> ${{number_format($job->salary)}}
                                </li>
                                <li class="mb-2">
                                    <strong>Site Location:</strong> {{$job->city}}, {{$job->state}}
                                </li>
                                @if($job->tags)
                                <li class="mb-2">
                                    <strong>Tags:</strong>{{ucwords(str_replace(',',', ',
                                    $job->tags))}}
                                    
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <div class="container mx-auto p-4">
                        @if($job->requirements || $job->benefits)
                        <h2 class="text-xl font-semibold mb-4">Job Details</h2>
                        <div class="rounded-lg shadow-md bg-white p-4">
                            <h3
                                class="text-lg font-semibold mb-2 text-blue-500"
                            >
                                {{$job->requirements}}
                            </h3>
                            <p>
                                Bachelors degree in Computer Science or related
                                field, 3+ years of software development
                                experience
                            </p>
                            <h3
                                class="text-lg font-semibold mt-4 mb-2 text-blue-500"
                            >
                                {{$job->benefits}}
                            </h3>
                            <p>
                                Healthcare, 401(k) matching, flexible work hours
                            </p>
                        </div>
                        @endif

                        @auth

                        <p class="my-5">
                            Put "Job Application" as the subject of your email
                            and attach your resume.
                        </p>



                        <div x-data="{ open:false }" id="appicant-form">
                                <button @click="open = true" href="mailto:{{$job->contact_email}}"
                            class="block w-full text-center px-5 py-2.5 shadow-sm rounded border text-base font-medium cursor-pointer text-indigo-700 bg-indigo-100 hover:bg-indigo-200">

                            Apply Now
                                </button>

                               <div x-show="open" class="fixed inset-0 flex items-center
                               justify-center bg-gray-900 bg-opacity-50">
                                <div @click.away="open = false" class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
                                    <h3 class="text-lg font-semibold mb-4">
                                        Apply for {{$job->title}}</h3>   
                                        <form method="POST" action="{{route('applicant.store', $job->id)}}" enctype="multipart/form-data"">
                                        @csrf
                                        <x-inputs.text 
                                        id="full_name" 
                                        name="full_name" 
                                        label="Full name"
                                        :required="true"
                                        />

                                           <x-inputs.text 
                                        id="contact_phone" 
                                        name="contact_phone" 
                                        label="Contact Phone"
                                        
                                        />

                                        <x-inputs.text 
                                        id="contact_email" 
                                        name="contact_email" 
                                        label="Contact Email"
                                        :required="true"
                                        />

                                        <x-inputs.text-area 
                                        id="message" 
                                        name="message" 
                                        label="Message"
                                        
                                        />

                                          <x-inputs.text 
                                        id="location" 
                                        name="location" 
                                        label="Location"
                                       
                                        />

                                 

                                        <x-inputs.file 
                                        id="resume"
                                        name="resume" 
                                        label="Upload Your Resume (pdf)"
                                        :required="true" />


                                        <button type="submit" class="bg-blue-500 hover:bg-blue-600
                                        text-white px-4 py-2 rounded-md">
                                        Submit Application

                                        </button>

                                        <button @click="open = false" class="bg-gray-300 hover:bg-gray-400
                                        text-black px-4 py-2 rounded-md">
                                       Cancel

                                        </button>
                                        </form>
                                </div> 
                               </div> 
                        </div>

                            @else 
                        <p class="my-5 bg-red-200 rounded-xl p-3">
                             <i class="fas fa-info-circle mr-3"></i> You must be logged in to apply for this job

                        </p>
                            @endauth


                        
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md mt-6">
                        <div id="map"></div>
                    </div>
                </section>

                 <!-- Sidebar -->
                <aside class="bg-white rounded-lg shadow-md p-3">
                    <h3 class="text-xl text-center mb-4 font-bold">
                        Company Info
                    </h3>
                    @if($job->company_logo)
                    <img
                        src="/storage/{{$job->company_logo}}"
                        alt="Ad"
                        class="w-full rounded-lg mb-4 m-auto"
                    />
                    @endif
                    <h4 class="text-lg font-bold">{{$job->company_name}}</h4>
                    @if($job->company_description)
                    <p class="text-gray-700 text-lg my-3">
                        {{$job->company_description}}
                    </p>
                    @endif
                    @if($job->company_website)
                    <a
                        href="{{$job->company_website}}"
                        target="_blank"
                        class="text-blue-500"
                        >Visit Website</a
                    >
                    @endif

                   {{-- Bookmark button --}}
                   @guest 
                    <p class="mt-10 bg-gray-200 text-gray-700 font-bold w-full py-2 px-4 rounded-full text-center">

                        <i class="fas fa-info-circle mr-3"></i> You must be logged in to bookmark a job

                    </p>

                   @else 
                    <form method="POST" action="{{auth()->user()->bookmarkedJobs()->where('job_id',$job->id)
                    ->exists() ? route('bookmarks.destroy', $job->id) : route('bookmarks.store', $job->id)}}" 
                        class="mt-10">

                        @csrf 
                        @if(auth()->user()->bookmarkedJobs()->where('job_id',$job->id)
                    ->exists())
                    @method('DELETE')
                            <button class="bg-red-500 hover:bg-blue-600 text-white font-bold w-full py-2 px-4 rounded-full flex items-center justify-center">
                                <i class="fas fa-bookmark mr-3"></i> Remove Bookmark
                        </button>
                    @else
                        <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold w-full py-2 px-4 rounded-full flex items-center justify-center">
                                <i class="fas fa-bookmark mr-3"></i> Bookmark Listings
                        </button>
                        @endif
                    </form>

                   @endguest
                </aside>


</div>

  {{-- MAP API  --}}

  <style>
    #map {
        width: 100%;
        height: 400px;
        z-index: 1;
    }

    #appicant-form{
    position: relative;
    z-index: 2;
}
</style>
         <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // Initialize the map
        const map = L.map('map').setView([14.5995, 120.9842], 9);

        // OpenStreetMap
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Get address from Laravel
        const city = '{{ $job->city }}';
        const state = '{{ $job->state }}';
        const address = city + ', ' + state;

        // Convert address into coordinates
        fetch(
            `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`
        )
        .then(response => response.json())
        .then(data => {

            if (data.length > 0) {

                const latitude = parseFloat(data[0].lat);
                const longitude = parseFloat(data[0].lon);

                // Move map to location
                map.setView([latitude, longitude], 14);

                // Add marker
                L.marker([latitude, longitude])
                    .addTo(map);

            } else {

                console.error('No results found for the address.');

            }

        })
        .catch(error => {
            console.error('Error geocoding address:', error);
        });

    });
</script>

</x-layout>
          