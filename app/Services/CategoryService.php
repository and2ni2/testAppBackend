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
     * @param bool $inline
     * @return JsonResponse
     */
    public function list(bool $inline): JsonResponse
    {
        $categories = RequestCategory::query();

        if ($inline) {
            $data = $categories->get();
        } else {
            $data = $categories->whereNull('parent_id')->with('children')->get();
        }

        return $this->response($data);
    }

}
