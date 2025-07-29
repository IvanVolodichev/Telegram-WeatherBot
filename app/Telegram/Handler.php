<?php

namespace App\Telegram;

use DefStudio\Telegraph\Contracts\Storable;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use App\Services\Weather\MessageBuilder;
use App\Services\Weather\TimelineWeatherService;
use App\Services\Weather\SubscribeService;
use Illuminate\Support\Facades\Log;

class Handler extends WebhookHandler implements Storable
{
    use \DefStudio\Telegraph\Concerns\HasStorage;

    private const STATE_USER_ACTION_KEY = 'action'; 
    private const ACTION_WEATHER = "weather";
    private const ACTION_SUBSCRIBE = "subscribe";

    private SubscribeService $subscribeService; // Cервис подписок
    private TimelineWeatherService $weatherService; // Сервис получения прогноза погоды

    public function __construct(SubscribeService $subscribeService, TimelineWeatherService $weatherService)
    {
        $this->subscribeService = $subscribeService;
        $this->weatherService = $weatherService;
    }

    public function storageKey(): string|int
    {
        return $this->chat->chat_id; // Используем ID чата как ID хранилища состояния
    }

    public function start() 
    {   
        $this->sendWithKeyboard(
            MessageBuilder::startMessage()
        );
    }

    public function weather()
    {
        $this->chat->storage()->forget(self::STATE_USER_ACTION_KEY);
        $this->chat->storage()->set(self::STATE_USER_ACTION_KEY, self::ACTION_WEATHER);
        $this->chat->message(MessageBuilder::askLocation())->send();
    }

    public function subscribe()
    {
        $this->chat->storage()->set(self::STATE_USER_ACTION_KEY, self::ACTION_SUBSCRIBE);

        $subscribe = $this->subscribeService->getSubscribe($this->chat->chat_id); // Получение подписки по id чата

        if($subscribe)
        {
            $this->sendWithKeyboard(
                MessageBuilder::subscribeAlreadyExists($subscribe->location)
            );
            
            return;
        } 

        $this->chat
            ->message(MessageBuilder::askLocation())
            ->send();
    }

    public function handleChatMessage(\Illuminate\Support\Stringable $location):void
    {   
        $action = $this->chat->storage()->get(self::STATE_USER_ACTION_KEY);

        switch ($action) {
            case self::ACTION_WEATHER :
                try {
                    $this->sendWithKeyboard(
                        $this->getWeatherForecast($location)
                    );
                } catch (\Throwable $th) {
                    $this->sendWithKeyboard(
                        MessageBuilder::apiErrorMessage()
                    );
                }
                
                break;

            case self::ACTION_SUBSCRIBE :

                    $subscribe = $this->subscribeService->getSubscribe($this->chat->chat_id);

                    if(!$subscribe)
                    {
                        try {
                            $weatherData = $this->getWeatherForecast($location);

                            $subscribe = $this->subscribeService->create($this->chat->chat_id, $location);

                            $this->sendWithKeyboard(
                                MessageBuilder::successSubscribe($subscribe->location)
                            );
                        
                            $this->chat->storage()->forget(self::STATE_USER_ACTION_KEY);
                        } catch (\Throwable $e) {
                            Log::error('Ошибка при получении погоды', [
                                'location' => $location,
                                'error' => $e->getMessage(),
                            ]);
                            $this->sendWithKeyboard(MessageBuilder::apiErrorMessage());
                        }
                        
                    }
                    
                break;
            
            default:

                $this->sendWithKeyboard('Команда не распознана');
                $this->chat->storage()->forget(self::STATE_USER_ACTION_KEY);
                break;
        }
    }

    public function unsubscribe()
    {
        $subscribe = $this->subscribeService->getSubscribe($this->chat->chat_id);

        if($subscribe)
        {
            if($this->subscribeService->delete($subscribe))
            {
                $this->sendWithKeyboard(
                    MessageBuilder::declineSubscribe()
                );

                return;
            }

            return;
        }

        $this->sendWithKeyboard(MessageBuilder::subscribeNotExists());
    }

    public function status()
    {
        $subscribe = $this->subscribeService->getSubscribe($this->chat->chat_id);

        if($subscribe)
        {
            $this->sendWithKeyboard(
                MessageBuilder::subscribeAlreadyExists($subscribe->location)
            );

            return;
        }

        $this->sendWithKeyboard(
            MessageBuilder::subscribeNotExists()
        );
    }

    public function handleUnknownCommand(\Illuminate\Support\Stringable $text): void
    {
        $this->sendWithKeyboard(
            MessageBuilder::unknownCommand()
        );
    }
        
    private function getWeatherForecast(string $location): string
    {
        try {
            $data = $this->weatherService->getWeatherData($location);
        } catch (\Throwable $e) {
            Log::error('Ошибка при получении погоды', [
                'location' => $location,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
        
        return MessageBuilder::weatherMessage($data);
    }

    private function sendWithKeyboard(string $text)
    {
        $this->chat
            ->message($text)
            ->keyboard(MessageBuilder::keyboard())
            ->send();
    }
}