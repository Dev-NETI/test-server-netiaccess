<?php

namespace Database\Seeders;

use App\Models\ChatMessageStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChatMessageStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Status = [
            "Unread",
            "Read",
            "Archive",
            "Deleted"
        ];

        foreach ($Status as $data) {
            ChatMessageStatus::create([
                'status' => $data
            ]);
        }
    }
}
