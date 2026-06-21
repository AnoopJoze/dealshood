<?php

namespace App\Services;

use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;

class AnalyticsService
{
    protected BetaAnalyticsDataClient $client;
    protected string $propertyId;

    public function __construct()
    {
        $this->client = new BetaAnalyticsDataClient([
            'credentials' => base_path(config('services.google_analytics.credentials_path')),
        ]);

        $this->propertyId = config('services.google_analytics.property_id');
    }

    public function getSummary(string $startDate = '7daysAgo', string $endDate = 'today'): array
    {
        $response = $this->client->runReport([
            'property' => $this->propertyId,
            'dateRanges' => [new DateRange(['start_date' => $startDate, 'end_date' => $endDate])],
            'dimensions' => [new Dimension(['name' => 'date'])],
            'metrics' => [
                new Metric(['name' => 'activeUsers']),
                new Metric(['name' => 'screenPageViews']),
                new Metric(['name' => 'sessions']),
            ],
            'orderBys' => [
                [
                    'dimension' => ['dimension_name' => 'date'],
                ],
            ],
        ]);

        $rows = [];
        foreach ($response->getRows() as $row) {
            $rows[] = [
                'date' => $row->getDimensionValues()[0]->getValue(),
                'users' => (int) $row->getMetricValues()[0]->getValue(),
                'pageviews' => (int) $row->getMetricValues()[1]->getValue(),
                'sessions' => (int) $row->getMetricValues()[2]->getValue(),
            ];
        }

        return $rows;
    }

    public function getTotals(string $startDate = '28daysAgo', string $endDate = 'today'): array
    {
        $response = $this->client->runReport([
            'property' => $this->propertyId,
            'dateRanges' => [new DateRange(['start_date' => $startDate, 'end_date' => $endDate])],
            'metrics' => [
                new Metric(['name' => 'activeUsers']),
                new Metric(['name' => 'screenPageViews']),
                new Metric(['name' => 'sessions']),
                new Metric(['name' => 'averageSessionDuration']),
            ],
        ]);

        $row = $response->getRows()[0] ?? null;

        if (!$row) {
            return ['users' => 0, 'pageviews' => 0, 'sessions' => 0, 'avgDuration' => 0];
        }

        return [
            'users' => (int) $row->getMetricValues()[0]->getValue(),
            'pageviews' => (int) $row->getMetricValues()[1]->getValue(),
            'sessions' => (int) $row->getMetricValues()[2]->getValue(),
            'avgDuration' => round((float) $row->getMetricValues()[3]->getValue()),
        ];
    }
}