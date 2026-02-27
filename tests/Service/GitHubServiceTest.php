<?php

namespace App\Tests\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Service\GitHubService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use App\Enum\HealthStatus;

class GitHubServiceTest extends TestCase
{
    #[DataProvider('dinoNameProvider')]
    public function testGetHealthReturnsCorrectHealthStatusForDino(HealthStatus $expectedStatus, string $dinoName): void
    {

        $mockLogger = $this->createStub(LoggerInterface::class); // dummy
        $mockHttpClient = $this->createMock(HttpClientInterface::class); // mock
        $mockResponse = $this->createStub(ResponseInterface::class); ///stub

        $mockResponse
            ->method('toArray')
            ->willReturn([
                [
                    'title' => 'Daisy',
                    'labels' => [['name' => 'Status: Sick']]
                ],

                [
                    'title' => 'Maverick',
                    'labels' => [['name' => 'Status: Healthy']]
                ]
            ])
        ; // stub

        $mockHttpClient
            ->expects(self::once()) // mock
            ->method('request')
            ->with('GET', 'https://api.github.com/repos/SymfonyCasts/dino-park/issues') // mock
            ->willReturn($mockResponse)
        ; //
        $service = new GitHubService($mockHttpClient, $mockLogger);
        self::assertEquals($expectedStatus, $service->getHealthReport($dinoName));
    }

    public static function dinoNameProvider(): \Generator
    {
        yield 'Sick Dino' => [
            HealthStatus::SICK,
            'Daisy'
        ];

        yield 'Healthy Dino' => [
            HealthStatus::HEALTHY,
            'Maverick'
        ];
    }
}
