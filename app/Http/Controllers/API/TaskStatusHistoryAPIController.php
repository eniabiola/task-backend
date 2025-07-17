<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskStatusHistoryResource;
use App\Models\TaskStatusHistory;
use App\Traits\HasResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class TaskStatusHistoryAPIController extends Controller
{
    use HasResponseTrait;


    /**
     * @param Request $request
     * @param $taskId
     * @return JsonResponse
     * @OA\Get(
     *      path="/task-status-histories/{id}",
     *      summary="Get task status history",
     *      tags={"Task Status History"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Task status history retrieved"
     *      )
     *  )
     */
    public function index(Request $request, $taskId): JsonResponse
    {
        $history = TaskStatusHistory::query()->where('task_id', $taskId)
            ->with('oldStatus', 'newStatus')
            ->orderBy('changed_at', 'desc')
            ->paginate(10);

        return $this->successResponseWithResource('Status history fetched successfully',
            TaskStatusHistoryResource::collection($history)->response()->getData());
    }

}
