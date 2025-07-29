# 🌤 Telegram WeatherBot

Telegram-бот для получения прогноза погоды и подписки на ежедневные уведомления по выбранной локации.  
Разработан с использованием Laravel и DefStudio Telegraph.

---

## ⚙️ Технологии

- PHP 8.2
- Laravel 12
- [DefStudio/Telegraph](https://github.com/defstudio/telegraph) — Telegram Bot SDK
- [Weather API](https://www.visualcrossing.com/) — прогноз погоды
- MySQL — база данных подписок
- Docker

---

## 🚀 Возможности

- 🔍 Получение прогноза по городу
- ✅ Подписка на ежедневную рассылку
- ❌ Отписка от рассылки
- ℹ️ Проверка подписки
- 🎛 Инлайн-клавиатура для удобства

---

## 🧱 Архитектура

```text
app/
├── Telegram/
│   └── Handler.php              # Обработчик Telegram-команд
├── Services/
│   └── Weather/
│       ├── TimelineWeatherService.php  # Получение прогноза
│       ├── SubscribeService.php        # Подписки
│       └── MessageBuilder.php          # Шаблоны сообщений
```
---
## Ссылка

[text](https://t.me/Cognitive2005_bot)
