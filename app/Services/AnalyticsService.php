<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;

class AnalyticsService
{
    protected string $propertyId;
    protected string $credentialsPath;

    public function __construct()
    {
        $this->propertyId = str_replace('properties/', '', config('services.google_analytics.property_id'));
        $this->credentialsPath = base_path(config('services.google_analytics.credentials_path'));
    }

    protected function getAccessToken(): string
    {
        $credentials = new ServiceAccountCredentials(
            'https://www.googleapis.com/auth/analytics.readonly',
            $this->credentialsPath
        );

        $token = $credentials->fetchAuthToken();

        return $token['access_token'];
    }

    public function getSummary(string $startDate = '7daysAgo', string $endDate = 'today'): array
    {
        $response = Http::withToken($this->getAccessToken())
            ->post("https://analyticsdata.googleapis.com/v1beta/properties/{$this->propertyId}:runReport", [
                'dateRanges' => [['startDate' => $startDate, 'endDate' => $endDate]],
                'dimensions' => [['name' => 'date']],
                'metrics' => [
                    ['name' => 'activeUsers'],
                    ['name' => 'screenPageViews'],
                    ['name' => 'sessions'],
                ],
                'orderBys' => [['dimension' => ['dimensionName' => 'date']]],
            ]);

        $rows = [];
        foreach ($response->json('rows', []) as $row) {
            $rows[] = [
                'date' => $row['dimensionValues'][0]['value'],
                'users' => (int) $row['metricValues'][0]['value'],
                'pageviews' => (int) $row['metricValues'][1]['value'],
                'sessions' => (int) $row['metricValues'][2]['value'],
            ];
        }

        return $rows;
    }

    public function getTotals(string $startDate = '28daysAgo', string $endDate = 'today'): array
    {
        $response = Http::withToken($this->getAccessToken())
            ->post("https://analyticsdata.googleapis.com/v1beta/properties/{$this->propertyId}:runReport", [
                'dateRanges' => [['startDate' => $startDate, 'endDate' => $endDate]],
                'metrics' => [
                    ['name' => 'activeUsers'],
                    ['name' => 'screenPageViews'],
                    ['name' => 'sessions'],
                    ['name' => 'averageSessionDuration'],
                ],
            ]);

        $row = $response->json('rows.0');

        if (!$row) {
            return ['users' => 0, 'pageviews' => 0, 'sessions' => 0, 'avgDuration' => 0];
        }

        return [
            'users' => (int) $row['metricValues'][0]['value'],
            'pageviews' => (int) $row['metricValues'][1]['value'],
            'sessions' => (int) $row['metricValues'][2]['value'],
            'avgDuration' => round((float) $row['metricValues'][3]['value']),
        ];
    }
}