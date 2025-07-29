<?php

namespace App\Console\Commands;

use App\Models\Subscribe;
use App\Services\Weather\MessageBuilder;
use App\Services\Weather\TimelineWeatherService;
use Illuminate\Console\Command;
use Log;

class SendMorningForecast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-morning-forecast';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(TimelineWeatherService $weatherService)
    {
        $subscribes = Subscribe::with('chat')->get();

        foreach ($subscribes as $subscribe) {
            $chat = $subscribe->chat;

            if (!$chat) continue;

            try {
                $weatherData = $weatherService->getWeatherData($subscribe->location);
                
                $chat->message(MessageBuilder::morningMessage($weatherData))
                    ->keyboard(MessageBuilder::keyboard())
                    ->send();

                Log::info("Утренний прогноз: для пользователя {$subscribe->chat->name} доставлен");
                    
            } catch (\Exception $e) {
                $this->error("Ошибка для чата {$subscribe->chat_id}: " . $e->getMessage());
                Log::error("Утренний прогноз: {$subscribe->chat_id} - " . $e->getMessage());
            }
        }
    }
    
}
