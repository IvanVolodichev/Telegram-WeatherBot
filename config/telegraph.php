<?php

use App\Telegram\Handler;
use DefStudio\Telegraph\Telegraph;

return [
    /*
     * Telegram api base url, it can be overridden
     * for self-hosted servers
     */
    'telegram_api_url' => 'https://api.telegram.org/',

    /*
     * Sets Telegraph messages default parse mode
     * allowed values: html|markdown|MarkdownV2
     */
    'default_parse_mode' => Telegraph::PARSE_HTML,

    'webhook' => [
        /*
         * Sets the webhook URL that will be exposed by the server,
         * this can be customized or entirely disabled (by setting it to NULL)
         */
        'url' => '/telegraph/{token}/webhook',

        /*
         * Sets the handler to be used when Telegraph
         * receives a new webhook call.
         *
         * For reference, see https://docs.defstudio.it/telegraph/webhooks/overview
         */
        'handler' => Handler::class,

        /*
         * Middleware to be applied to the webhook route
         */
        'middleware' => [],

        /*
         * Sets a custom domain when registering a webhook. This will allow a local telegram bot api server
         * to reach the webhook. Disabled by default
         *
         * For reference, see https://core.telegram.org/bots/api#using-a-local-bot-api-server
         */
        // 'domain' => 'http://my.custom.domain',

        /*
         * If enabled, unknown webhook commands are
         * reported as exception in application logs
         */
        'report_unknown_commands' => env('TELEGRAPH_REPORT_UNKNOWN_COMMANDS', true),

        /**
         * secret token to be sent in a X-Telegram-Bot-Api-Secret-Token header
         * to verify the authenticity of the webhook
         */
        'secret' => env('TELEGRAPH_WEBHOOK_SECRET'),

        /**
         * maximum allowed simultaneous connections to the webhook (defaults to 40)
         */
        'max_connections' => env('TELEGRAPH_WEBHOOK_MAX_CONNECTIONS', 40),

        /**
         * List of event types for which the webhook should fire.
         *
         * Specify a null to receive all update types except `chat_member`, `message_reaction`,
         * and `message_reaction_count` (by default).
         *
         * @see https://core.telegram.org/bots/api#setwebhook
         */
        'allowed_updates' => null,

        /*
         * If enabled, Telegraph dumps received
         * webhook messages to logs
         */
        'debug' => env('TELEGRAPH_WEBHOOK_DEBUG', false),
    ],

    /*
     * Sets HTTP request timeout when interacting with Telegram servers
     */
    'http_timeout' => env('TELEGRAPH_HTTP_TIMEOUT', 30),

    /*
     * Sets HTTP connection request timeout when interacting with Telegram servers
     */
    'http_connection_timeout' => env('TELEGRAPH_HTTP_CONNECTION_TIMEOUT', 10),

    'security' => [
        /*
         * if enabled, allows callback queries from unregistered chats
         */
        'allow_callback_queries_from_unknown_chats' => true,

        /*
         * if enabled, allows messages and commands from unregistered chats
         */
        'allow_messages_from_unknown_chats' => true,

        /*
         * if enabled, store unknown chats as new TelegraphChat models
         */
        'store_unknown_chats_in_db' => true,
    ],

    /*
     * Set model class for both TelegraphBot and TelegraphChat,
     * to allow more customization.
     *
     * Bot model must be or extend `DefStudio\Telegraph\Models\TelegraphBot::class`
     * Chat model must be or extend `DefStudio\Telegraph\Models\TelegraphChat::class`
     */
    'models' => [
        'bot' => DefStudio\Telegraph\Models\TelegraphBot::class,
        'chat' => DefStudio\Telegraph\Models\TelegraphChat::class,
    ],

    'storage' => [
        /**
         * Default storage driver to be used for Telegraph data
         */
        'default' => 'file',

        'stores' => [
            'file' => [
                /**
                 * Telegraph cache driver to be used, must implement
                 * DefStudio\Telegraph\Contracts\StorageDriver contract
                 */
                'driver' => \DefStudio\Telegraph\Storage\FileStorageDriver::class,

                /*
                 * Laravel Storage disk to use. See /config/filesystems/disks for available disks
                 * If 'null', Laravel default store will be used,
                 */
                'disk' => 'local',

                /**
                 * Folder inside filesystem to be used as root for Telegraph storage
                 */
                'root' => 'telegraph',
            ],
            'cache' => [
                /**
                 * Telegraph cache driver to be used, must implement
                 * DefStudio\Telegraph\Contracts\StorageDriver contract
                 */
                'driver' => \DefStudio\Telegraph\Storage\CacheStorageDriver::class,

                /*
                 * Laravel Cache store to use. See /config/cache/stores for available stores
                 * If 'null', Laravel default store will be used,
                 */
                'store' => null,

                /*
                 * Prefix to be prepended to cache keys
                 */
                'key_prefix' => 'tgph',
            ],
        ],
    ],

    /**
     * Attachment validation rules, Telegram bot API defaults are set
     * can be changed to match higher limits when using a local bot
     * API server (ref. https://core.telegram.org/bots/api#using-a-local-bot-api-server)
     */
    'attachments' => [
        'thumbnail' => [
            'max_size_kb' => 200,
            'max_height_px' => 320,
            'max_width_px' => 320,
            'allowed_ext' => ['jpg'],
        ],
        'photo' => [
            'max_size_mb' => 10,
            'max_ratio' => 20,
            'height_width_sum_px' => 10000,
        ],
        'animation' => [
            'max_size_mb' => 50,
        ],
        'video' => [
            'max_size_mb' => 50,
        ],
        'audio' => [
            'max_size_mb' => 50,
        ],
        'document' => [
            'max_size_mb' => 50,
        ],
        'sticker' => [
            'max_size_mb' => 50,
        ],
    ],

    /*
     * Sets preferences for commands
     */
    'commands' => [
        /*
         * Defines a list of characters that are the identifier of a command sent to the chat.
         *
         * Default is `/`
         */
        'start_with' => ['/'],
    ],

    'payments' => [
        'provider_token' => env('TELEGRAPH_PAYMENT_PROVIDER_TOKEN', ''),
    ],
];
