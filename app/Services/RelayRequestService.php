<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class RelayRequestService
{
    public function __construct(protected Client $client, protected ResponseFactory $responseFactory)
    {
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function relay(Request $request): Response
    {
        $mappings = new Collection(config('mappings'));

        $filteredMappings = $mappings->filter(function ($value, $key) use ($request) {
            return str_starts_with('/'.ltrim($request->path(), '/'), '/'.ltrim($key, '/'));
        });

        if ($filteredMappings->count() > 1) {
            throw new Exception('Ambiguous mappings detected, don\'t use the same route more than once.');
        }

        if ($filteredMappings->isEmpty()) {
            throw new Exception('No routing found for current path.');
        }

        $mappedPath = $filteredMappings->keys()->first();

        $baseUri = $filteredMappings->first();

        $relayPath = ltrim(
            str_replace(
                ltrim($mappedPath, '/'),
                '',
                ltrim($request->path(), '/')
            ),
            '/'
        );

        $options = [
            'verify' => false,
            'http_errors' => false,
        ];

        if ($request->hasHeader('Accept')) {
            $options['headers']['Accept'] = $request->header('Accept');
        }

        if ($request->hasHeader('Content-Type')) {
            $options['headers']['Content-Type'] = $request->header('Content-Type');
        }

        $query = $request->getQueryString();

        if ($query) {
            $options['query'] = $query;
        }

        $content = $request->getContent();

        if ($content) {
            $options['body'] = $content;
        }

        $response = $this->client->request(
            $request->method(),
            $baseUri.'/'.$relayPath,
            $options
        );

        return $this->responseFactory->make(
            content: $response->getBody()->getContents(),
            status: $response->getStatusCode(),
            headers: ['Content-Type' => $response->getHeader('Content-Type')]
        );
    }
}
