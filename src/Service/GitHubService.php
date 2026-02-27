<?php

namespace App\Service;

use App\Enum\HealthStatus;
use PHPUnit\TextUI\Help;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GitHubService
{
    public function __construct(private HttpClientInterface $httpClient, private LoggerInterface $logger) {}

    public function getHealthReport(string $dinoName): HealthStatus
    {
        $health = HealthStatus::HEALTHY;

        $response = $this->httpClient->request(
            method: 'GET',
            url: 'https://api.github.com/repos/SymfonyCasts/dino-park/issues'
        );

        $this->logger->info('Request Dino Issues', [
            'dino' => $dinoName,
            'responseStatus' => $response->getStatusCode(),
        ]);

        foreach ($response->toArray() as $issue) {
            if (str_contains($issue['title'], $dinoName)) {
                $health = $this->getDinoStatusFromLabels($issue['labels']);
            }
        }
        return $health;
    }

    public function getDinoStatusFromLabels(array $labels): HealthStatus
    {
        $health = HealthStatus::HEALTHY;

        foreach ($labels as $labelData) {
            $label = $labelData['name'];

            if (!str_starts_with($label, 'Status:')) {
                continue;
            }

            $status = trim(substr($label, strlen('Status:')));

            $health = HealthStatus::tryFrom($status);

            if (null === $health) {
                throw new \RuntimeException(sprintf('%s is an unknown status label!', $label));
            }
            // $status = HealthStatus::tryFrom($status);
            // if ($status !== null) {
            //     return $status;
            // }
        }
        return $health;
    }
}
