<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RequestCategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $categoryService
    ) {}


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $inline = false;
        if ($request->input('inline')) {
            $inline = true;
        }

        return $this->categoryService->list($inline);
    }

}
