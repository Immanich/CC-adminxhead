<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first(); // Assuming at least one user exists
        $events = [
            [
                'image' => "assets/images/event-pic-1.png",
                'title' => "Buy your tickets for \nMiss Bohol 2024!",
                'description' => "Buy your tickets for Miss Bohol 2024! BUY YOUR TICKETS FOR MISS BOHOL 2024",
                'status' => "approved",
                'user_id' => $user->id, // Add user ID here
            ],
            [
                'image' => "assets/images/event-pic-2.png",
                'title' => "Tubigon's representative for the Mister Bohol 2024 competition.",
                'description' => "description 22",
                'status' => "approved",
                'user_id' => $user->id
            ],
            [
                'image' => "assets/images/event-pic-3.png",
                'title' => "TITLE 3",
                'description' => "description 3",
                'status' => "approved",
                'user_id' => $user->id,
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}
