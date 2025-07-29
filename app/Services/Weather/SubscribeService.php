<?php

namespace App\Services\Weather;

use App\Models\Subscribe;
use Illuminate\Support\Facades\Log;

class SubscribeService
{
    public function getSubscribe(string $chat_id): ?Subscribe
    {
        return Subscribe::where('chat_id', $chat_id)->first();
    }

    public function create(string $chat_id, string $location): Subscribe
    {
        try {
            return Subscribe::create([
                'chat_id' => $chat_id,
                'location' => $location,
            ]);
        } catch (\Exception $e) {
            Log::error("Subscription creation failed for {$chat_id}: " . $e->getMessage());
            throw $e;
        }
    }

    public function delete(Subscribe $subscribe): bool
    {
        try {
            return $subscribe->delete();
        } catch (\Exception $e) {
            Log::error("Subscription deletion failed for {$subscribe->chat_id}: " . $e->getMessage());
            throw $e;
        }
    }
}