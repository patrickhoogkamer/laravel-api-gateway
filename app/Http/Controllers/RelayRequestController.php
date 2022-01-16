<?php

namespace App\Http\Controllers;

use App\Services\RelayRequestService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RelayRequestController extends Controller
{
    /**
     * @param Request $request
     * @param RelayRequestService $relayRequestService
     * @return Response
     * @throws Exception
     */
    public function __invoke(Request $request, RelayRequestService $relayRequestService): Response
    {
        return $relayRequestService->relay($request);
    }
}
