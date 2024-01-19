<?php

namespace Itiden\FA\Http\Controllers;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Statamic\Facades\Entry;
use Statamic\Facades\Site;

class FAController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->input('sort') ?? 'pageviews';
        $order = $request->input('order') ?? 'desc';
        $sortOrder = $sort . ':' . $order;

        $filters = json_decode(base64_decode($request->input('filters')));

        $interval = match ($filters->interval) {
            'today' => Carbon::parse(Carbon::today())->format('Y-m-d h:i:s'),
            'week' => Carbon::parse(Carbon::today()->subDays(7))->format('Y-m-d h:i:s'),
            'month' => Carbon::parse(Carbon::today()->subMonth())->format('Y-m-d h:i:s'),
            default => null
        };

        /**
         * @var \Illuminate\Support\Collection $response
         */
        $response = Cache::remember('fathom' . $sortOrder . $interval, 60, fn () => Http::withToken(config('fa.api_token'))
            ->get('https://api.usefathom.com/v1/aggregations', [
                'entity' => 'pageview',
                'entity_id' => config('fa.site_id'),
                'aggregates' => 'visits, uniques, pageviews, avg_duration, bounce_rate',
                'field_grouping' => 'hostname,pathname',
                'limit' => 100,
                'sort_by' => $sortOrder,
                'date_from' => $interval,
                'filters' => json_encode(collect(config('fa.hostnames'))->filter()->map(
                    fn ($hostname) =>
                    [
                        'property' => 'hostname',
                        'operator' => 'is',
                        'value' => $hostname,
                    ],
                )),
            ])
            ->collect());

        if ($response->has('errors')) {
            throw new Exception('Something went wrong when getting analytics data');
        }

        $editedResponse = $response->map(function ($item) {
            if ($item['pathname'] != '/' && str_ends_with($item['pathname'], '/')) {
                $item['pathname'] = rtrim($item['pathname'], '/');
            }
            $entry = Entry::findByUri($item['pathname'], Site::selected());

            return [
                ...$item,
                'avg_duration' => CarbonInterval::seconds($item['avg_duration'])->cascade()->forHumans(['short' => true]),
                'edit_url' => $entry ? $entry->editUrl : null,
            ];
        });

        $perPage = $request->input('perPage', 10);

        $results = new LengthAwarePaginator(
            $editedResponse->slice(($request->input('page', 1) - 1) * $perPage, $perPage),
            $editedResponse->count(),
            $perPage,
        );

        return [
            ...$results->toArray(),
            'meta' => [
                'last_page' => $results->lastPage(),
                'from' => $results->firstItem(),
                'to' => $results->lastItem(),
                'total' => $results->total(),
                'current_page' => $results->currentPage(),
                'columns' => [
                    [
                        'label' => 'Pathname',
                        'field' => 'pathname',
                        'visible' => true,
                        'sortable' => true,
                    ],
                    [
                        'label' => 'Pageviews',
                        'field' => 'pageviews',
                        'visible' => true,
                        'sortable' => true,
                    ],
                    [
                        'label' => 'Uniques',
                        'field' => 'uniques',
                        'visible' => true,
                        'sortable' => true,
                    ],
                    [
                        'label' => 'Duration',
                        'field' => 'avg_duration',
                        'visible' => true,
                        'sortable' => true,
                    ],
                    [
                        'label' => 'Edit',
                        'field' => 'edit_url',
                        'visible' => false,
                        'sortable' => false,
                    ],
                ],
            ],
        ];
    }
}
