<?php

namespace RabbitDigital\Bugtify\Tests;

use Mockery\Exception;
use RabbitDigital\Bugtify\Bugtify;
use Tests\TestCase;

class BugtifyTest extends TestCase
{
    protected $bugtify;

    public function setUp(): void
    {
        parent::setUp();

        $this->bugtify = new Bugtify([
            'discord' => [
                'webhook' => ''
            ]
        ]);
    }

    /** @test */
    public function it_will_skip_notification()
    {
        $this->markTestSkipped();

        $exception = new Exception('test');

        $this->bugtify->notifyException($exception);
    }
}
