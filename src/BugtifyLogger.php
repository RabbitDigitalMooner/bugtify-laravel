<?php

namespace RabbitDigital\Bugtify;

use GuzzleHttp\Exception\GuzzleException;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class BugtifyLogger extends AbstractProcessingHandler
{
    private Bugtify $notifier;

    public function __construct(Bugtify $bugtify, $level = Logger::ERROR, bool $bubble = true)
    {
        $this->notifier = $bugtify;

        parent::__construct($level, $bubble);
    }

    /**
     * @throws GuzzleException
     */
    protected function write(array $record): void
    {
        if (isset($record['context']['exception'])
            && $record['context']['exception'] instanceof \Throwable
        ) {
            $this->notifier->handle(
                $record['context']['exception']
            );
        }
    }
}
