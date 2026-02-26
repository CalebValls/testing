<?php

namespace App\Tests\Service;

use Psr\Log\LoggerInterface;
use App\Service\GitHubService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use App\Enum\HealthStatus;

class GitHubServiceTest extends TestCase
{    
    #[DataProvider('dinoNameProvider')]
    public function testGetHealthReturnsCorrectHealthStatusForDino($expectedStatus, $dinoName): void
    {

        $mockLogger = $this->createMock(LoggerInterface::class);

        $service = new GitHubService($mockLogger);

        self::assertSame($expectedStatus, $service->getHealthReport($dinoName));
    }

    public static function dinoNameProvider(): \Generator
    {
        yield 'Sick Dino' => [
            HealthStatus::SICK,
            'Daisy'
        ];

        yield 'Healthy Dino' => [
            HealthStatus::HEALTHY,
            'Igor'
        ];
    }
}
