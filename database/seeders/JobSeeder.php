<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;


class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Load job listings from file
        $jobListings = include database_path('seeders/data/job_listings.php');

        //Get test user id
      
       $testUserId = User::where('email', 'test@test.com')->value('id');
        
        // Get all other user ids from user model
        $userIds = User::where('email','!=','test@test.com')->pluck('id')->toArray();

        forEach($jobListings as $index => &$listing){
        //Assing user id to listing

        if($index < 2){
            // assign the first two listings to the test user
            $listing['user_id'] = $testUserId;

        }
        else{
            $listing['user_id'] = $userIds[array_rand($userIds)];
        }
        
       

       $listing['created_at'] = now();
        $listing['updated_at'] = now();
     

        }
        //Insert job Listing
        DB::table('job_listings')->insert($jobListings);
         echo 'Jobs created successfully';
    }
}
