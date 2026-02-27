<?php

namespace App\Tests\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Service\GitHubService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use App\Enum\HealthStatus;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

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
                'title' => 'Maverick',
                'labels' => [['name' => 'Status: Healthy']]
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

    // PROVIDER
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

    public function testExceptionThrownForUnknownLabel(): void
    {

        $mockResponse = new MockResponse(json_encode([
            [
                'title' => 'Maverick',
                'labels' => [['name' => 'Status: Drowsy']]
            ]
        ]));
        $mockHttpClient = new MockHttpClient($mockResponse);
        // aqui estas creando dos mocks, es el sistema de php, pero symfony tiene un sistema que lo facilita
        // $mockLogger = $this->createStub(LoggerInterface::class); // dummy
        // $mockHttpClient = $this->createMock(HttpClientInterface::class); // mock
        // $mockResponse = $this->createStub(ResponseInterface::class); ///stub
        // $mockResponse
        //     ->method('toArray')
        //     ->willReturn([
        //         'title' => 'Maverick',
        //         'labels' => [['name' => 'Status: Drowsy']]

        //     ]); // stub
        // $mockHttpClient
        //     ->expects(self::once()) // mock
        //     ->method('request')
        //     ->with('GET', 'https://api.github.com/repos/SymfonyCasts/dino-park/issues') // mock
        //     ->willReturn($mockResponse)
        // ;

        $this->expectException(\RuntimeException::class); //assert camuflado
        $this->expectExceptionMessage(' Drowsy is an unknown status label!');
        $service = new GitHubService($mockHttpClient, $this->createStub(LoggerInterface::class));
        $service->getHealthReport('Maverick');
    }
}
