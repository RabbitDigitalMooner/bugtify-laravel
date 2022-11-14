<?php

namespace RabbitDigital\Bugtify;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Bugtify
{
    public const MAX_LENGTH_TITLE=100;

    private $webhookUrl;

    public function __construct(array $config)
    {
        $this->webhookUrl = $config['discord']['webhook'];
    }

    /**
     * @throws GuzzleException
     */
    public function handle(\Throwable $exception): void
    {
        $this->notifyException($exception);
    }

    /**
     * @throws GuzzleException
     */
    public function notifyException($exception)
    {
        $data = $this->getExceptionData($exception);

        if ($this->isSkipNotification($data)) {
            return;
        }

        try {
            $client = new Client();
            $client->request('POST', $this->webhookUrl, [
                'json' => [
                    'embeds' => [
                        [
                            'title'       => $this->getTitle($data['exception']),
                            'description' => $this->getDescription($data['error']),
                            'color'       => config('bugtify.discord.embed_color'),
                            'fields'      => [
                                [
                                    'name'   => 'Environment',
                                    'value'  => $data['environment'],
                                    'inline' => true,
                                ],
                                [
                                    'name'   => 'Method',
                                    'value'  => $data['method'],
                                    'inline' => true,
                                ],
                                [
                                    'name'   => 'Line',
                                    'value'  => $data['line'],
                                    'inline' => true,
                                ],
                                [
                                    'name'   => 'PHP Version',
                                    'value'  => $data['server']['php_version'],
                                    'inline' => true,
                                ],
                                [
                                    'name'  => 'URL',
                                    'value' => $data['fullUrl'],
                                ],
                                [
                                    'name'  => 'File',
                                    'value' => $data['file'],
                                ],
                            ],
                        ],
                    ],
                ],
            ]);
        } catch (\Exception $exception) {
            if (config('app.debug') === true) {
                Log::debug($exception->getMessage());
            }
        }
    }

    private function getTitle(string $title): string
    {
        return Str::limit($title, self::MAX_LENGTH_TITLE);
    }

    private function getDescription(string $description): string
    {
        return Str::limit($description, config('bugtify.lines_count'));
    }

    private function getExceptionData($exception): array
    {
        $request = app('request');

        return [
            'environment' => app()->environment(),
            'host'        => $request->getHost(),
            'method'      => $request->getMethod(),
            'fullUrl'     => $request->getUri(),
            'exception'   => $exception->getMessage() ?? '-',
            'error'       => $exception->getTraceAsString(),
            'line'        => $exception->getLine(),
            'file'        => $exception->getFile(),
            'server'      => array_filter([
                'user_agent'      => $_SERVER['HTTP_USER_AGENT'],
                'protocol'        => $request->server('SERVER_PROTOCOL'),
                'software'        => $request->server('SERVER_SOFTWARE'),
                'php_version'     => PHP_VERSION,
            ]),
        ];
    }

    private function isSkipNotification(array $exceptionData): bool
    {
        if (! in_array(app()->environment(), config('bugtify.environments'))) {
            return true;
        }

        if (! config('bugtify.enabled_limit_notification')) {
            return false;
        }

        $hashTitle = md5($exceptionData['exception']);
        if (Cache::get($hashTitle) > config('bugtify.max_limit_notification')) {
            return true;
        }

        Cache::increment($hashTitle);

        return false;
    }
}
