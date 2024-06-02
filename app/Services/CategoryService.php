<?php
namespace App\Services;

use App\Models\RequestCategory;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Exception;

class CategoryService
{
    use ApiResponse;

    public function __construct() {}


    /**
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        $categories = RequestCategory::whereNull('parent_id')->with('children')->get();

        return $this->response($categories);
    }

}
