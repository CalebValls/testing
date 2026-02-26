<?php

namespace App\Service;

use App\Enum\HealthStatus;
use Symfony\Component\HttpClient\HttpClient;

class GitHubService
{

    public function getHealthReport(string $dinoName): HealthStatus
    {
        $health = HealthStatus::HEALTHY;
        $client = HttpClient::create();
        $response = $client->request(
            method: 'GET',
            url: 'https://api.github.com/repos/SymfonyCasts/dino-park/issues'
        );

        foreach ($response->toArray() as $issue) {
            if (str_contains($issue['title'], $dinoName)) {
                $health = $this->getDinoStatusFromLabels($issue['labels']);
            }
        }
        return $health;
    }


    public function getDinoStatusFromLabels(array $labels): HealthStatus
    {
        foreach ($labels as $label) {
            $label = $label['name'];

            if (!str_starts_with($label, 'Status:')) {
                continue;
            }

            $status = trim(substr($label, strlen('Status:')));
            $status = HealthStatus::tryFrom($status);
            if ($status !== null) {
                return $status;
            }
        }
        return HealthStatus::HEALTHY;
    }
}
