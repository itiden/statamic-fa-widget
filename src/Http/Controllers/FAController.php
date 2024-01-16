<?php

namespace Itiden\FA\Http\Controllers;

use Carbon\Carbon;
use Carbon\CarbonInterval;
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

        $response = Cache::remember('fathom' . $sortOrder . $interval, 60, fn () => Http::withToken(config('fa.fa_api_token'))
            ->get('https://api.usefathom.com/v1/aggregations', [
                'entity' => 'pageview',
                'entity_id' => config('fa.fa_site_id'),
                'aggregates' => 'visits, uniques, pageviews, avg_duration, bounce_rate',
                'field_grouping' => 'hostname,pathname',
                'limit' => 100,
                'sort_by' => $sortOrder,
                'date_from' => $interval,
                'filters' => config('fa.fa_hostname')
                    ? '[{"property":"hostname","operator":"is","value":"' . config('fa.fa_hostname') . '"}]'
                    : '',
            ])
            ->collect());

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

        $perPage = $request->input('perPage') ?? 10;
        $editedResponse = $editedResponse->all();
        $items = array_slice($editedResponse, (request('page', 1) - 1) * $perPage, $perPage);

        $results = new LengthAwarePaginator($items, count($editedResponse), $perPage, request('page', 1));

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
