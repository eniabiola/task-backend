<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskStatusResource;
use App\Models\TaskStatus;
use App\Traits\HasResponseTrait;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class TaskStatusAPIController extends Controller
{
    use HasResponseTrait;

    /**
     * Display a listing of the resource.
     * @OA\Get(
     *      path="/task-statuses",
     *      summary="List task statuses",
     *      tags={"Task Status"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="List of task statuses"
     *      )
     *  )
     */
    public function index(): JsonResponse
    {

        $task_statuses = TaskStatus::query()->orderByDesc('created_at')->get();
        return $this->successResponseWithResource("Tasks Lists",  TaskStatusResource::collection($task_statuses));
    }


}
