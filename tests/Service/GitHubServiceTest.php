<?php

namespace App\Tests\Service;

use App\Service\GitHubService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use App\Enum\HealthStatus;

class GitHubServiceTest extends TestCase
{
    #[DataProvider('dinoNameProvider')]
    public function testGetHealthReturnsCorrectHealthStatusForDino($expectedStatus, $dinoName): void 
    {
        $service = new GitHubService();

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
