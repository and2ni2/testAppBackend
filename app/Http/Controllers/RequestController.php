<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRequestItemRequest;
use App\Models\RequestItem;
use App\Services\RequestService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly RequestService $requestService
    ) {}


    public function create(CreateRequestItemRequest $request): JsonResponse
    {
        return $this->requestService->create($request->validated());
    }


    public function list(Request $request): JsonResponse
    {
        return $this->requestService->list();
    }


    public function show(int $id): JsonResponse
    {
       $model = $this->checkModel($id);
       return $this->requestService->show($model);
    }

    public function close(int $id): JsonResponse
    {
        $model = $this->checkModel($id);
        return $this->requestService->close($model);
    }


    private function checkModel(int $id): JsonResponse | RequestItem
    {
        /** @var RequestItem $model */
        $model = RequestItem::query()->where('id', $id)->first();

        if (! $model) {
            return $this->error(['Заявка не найдена'], 404);
        }

        return $model;
    }
}
