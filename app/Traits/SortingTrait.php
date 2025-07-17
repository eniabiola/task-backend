<?php


namespace App\Traits;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

trait SortingTrait
{

    public function applySortParams(Request $request, array $sortColumns = []): array
    {
        $sortBy = in_array($request->query('sort_by'), $sortColumns) ? $request->query('sort_by') : 'created_at';
        $direction = in_array(strtolower($request->query('order')), ['asc', 'desc']) ? $request->query('order') : 'desc';

        return [$sortBy, $direction];
    }
}
