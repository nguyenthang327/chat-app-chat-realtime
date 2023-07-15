<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        DB::beginTransaction();
        try{
            $users = [];
            for($i = 0; $i<3 ;++$i){
                $users[] = [
                    "name" => "Thang ND handsome $i",
                    "email" => "user$i@example.com",
                    "password" => Hash::make('123456'),
                    // "created_at" => Carbon::now(),
                    // "updated_at" => Carbon::now(),
                ];
            }

            User::insert($users);

            $conversation = Conversation::create();

            $userFirst = User::first();
            $userLast = User::orderBy('id', 'desc')->first();

            $conversationParticipants = [
                [
                    'user_id' => $userFirst->id,
                    'conversation_id' => $conversation->id,
                ],
                [
                    'user_id' => $userLast->id,
                    'conversation_id' => $conversation->id,
                ],
            ];
            ConversationParticipant::insert($conversationParticipants);
            DB::commit();
            Log::info('[DatabaseSeeder] seeder success');
        }catch(Exception $e){
            DB::rollBack();
            Log::error('[DatabaseSeeder] seeder failed. Message: ' . $e->getMessage());
        }
    }
}
