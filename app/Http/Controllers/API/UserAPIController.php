<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\HasResponseTrait;
use App\Traits\SortingTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserAPIController extends Controller
{
    use HasResponseTrait, SortingTrait;
    public function indexOfUsers(Request $request) : JsonResponse
    {

        $validSortColumns = ['name', 'email'];
        [$sortBy, $direction] = $this->applySortParams($request, $validSortColumns);

        $tasks = User::query()
            ->orderBy($sortBy, $direction)
            ->paginate(10);

        return $this->successResponseWithResource("User Lists",  UserResource::collection($tasks)->response()->getData());
    }
}
