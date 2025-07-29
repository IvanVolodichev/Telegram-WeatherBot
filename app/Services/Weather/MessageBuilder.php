<?php

namespace App\Services\Weather;

use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

class MessageBuilder
{
    public static function startMessage(): string
    {
        return "Привет! Я бот <b>Cognitive</b>.\n\n" .
            "Я помогу тебе узнать погоду в любом городе и отправлять прогноз каждое утро.\n\n" .
            "Вот что я умею:\n" .
            "/weather — показать погоду на день\n" .
            "/subscribe — подписка на ежедневный прогноз\n" .
            "/unsubscribe — отменить подписку\n" .
            "/status — показать текущие подписки";
    }

    public static function weatherMessage(array $data): string
    {
        $rainfallText = $data['rainfall'] > 0 
            ? "🌧 Осадки: {$data['rainfall']} мм" 
            : "☔ Без осадков";
        
        return "🌤 <b>Погода в {$data['address']}</b>\n\n"
            . "🌡 Температура:\n"
            . "  • Средняя: <b>{$data['avg_temperature']}°C</b>\n"
            . "  • Максимальная: <b>{$data['max_temperature']}°C</b>\n\n"
            . "💨 Ветер: <b>{$data['windspeed']} км/ч</b>\n"
            . "☁️ Облачность: <b>{$data['cloudcover']}%</b>\n"
            . $rainfallText;
    }

    public static function morningMessage(array $data): string
    {
        $rainfallText = $data['rainfall'] > 0 
            ? "🌧 Осадки: {$data['rainfall']} мм" 
            : "☔ Без осадков";
        
        return "Доброе утро!\n\n"
            . "🌤 <b>Погода в {$data['address']} сегодня:</b>\n\n"
            . "🌡 Температура:\n"
            . "  • Средняя: <b>{$data['avg_temperature']}°C</b>\n"
            . "  • Максимальная: <b>{$data['max_temperature']}°C</b>\n\n"
            . "💨 Ветер: <b>{$data['windspeed']} км/ч</b>\n"
            . "☁️ Облачность: <b>{$data['cloudcover']}%</b>\n"
            . $rainfallText
            . "\n\nУдачного дня!";
    }

    public static function askLocation(): string
    {
        return "Укажите населённый пункт\n\nНапример: <i>Тамань, Темрюкский район</i>";
    }

    public static function successSubscribe(string $location): string
    {
        return "✅ Подписка на утреннюю рассылку по адресу <i>{$location}</i> оформлена!\n\nДля отмены используйте команду /unsubscribe";
    }

    public static function subscribeAlreadyExists(string $location): string
    {
        return "ℹ️ У вас уже есть подписка по адресу <i>{$location}</i>\n\nДля отмены используйте команду /unsubscribe";
    }

    public static function subscribeNotExists(): string
    {
        return "ℹ️ У вас нет активных подписок";
    }

    public static function unknownCommand():string
    {
        return "ℹ️ Неизвестная команда";
    }

    public static function declineSubscribe(): string
    {
        return "✅ Подписка отменена";
    }

    public static function apiErrorMessage(): string
    {
        return "❌ Указанная локация не найдена или сервис погоды временно недоступен. Пожалуйста, проверьте название и попробуйте снова.";
    }

    public static function keyboard()
    {
        $keyboard = Keyboard::make()
        ->buttons([
            Button::make('✉️ Подписаться')->action('subscribe'),
            Button::make('🌤 Погода')->action('weather'),
            Button::make('❌ Отписаться')->action('unsubscribe'),
            Button::make('ℹ️ Статус')->action('status'),
            Button::make('🆘 Помощь')->action('start'),
        ])
        ->chunk(2)
        ->rightToLeft();

        return $keyboard;
    }
}