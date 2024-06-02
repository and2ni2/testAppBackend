<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMessageRequest;
use App\Services\RequestService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RequestMessagesController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly RequestService $requestService
    ) {}


    public function store(CreateMessageRequest $request): JsonResponse
    {
        return $this->requestService->sendMessage($request->validated());
    }
}
