<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStatusUpdateRequest;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Scopes\UserTasksScope;
use App\Http\Service\TaskService;
use App\Traits\HasResponseTrait;
use App\Traits\SortingTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Task;

use OpenApi\Annotations as OA;


class TaskAPIController extends Controller
{
    use HasResponseTrait, SortingTrait;

    public function __construct(protected TaskService $taskService) {}

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @OA\Get(
     * path="/api/tasks",
     * summary="Get all tasks",
     * tags={"Tasks"},
     * security={{"bearerAuth":{}}},
     * @OA\Response(
     * response=200,
     * description="List of tasks"
     * )
     * )
     */
    public function index(Request $request): JsonResponse
    {

        $validSortColumns = ['task_status_id', 'due_date', 'created_at'];
        [$sortBy, $direction] = $this->applySortParams($request, $validSortColumns);

        $tasks = Task::query()
            ->where('user_id', auth()->id())
            ->orderBy($sortBy, $direction)
            ->paginate(10);

        return $this->successResponseWithResource("Tasks Lists",  TaskResource::collection($tasks)->response()->getData());
    }
    public function indexForAdmin(Request $request): JsonResponse
    {

        $validSortColumns = ['task_status_id', 'due_date', 'created_at'];
        [$sortBy, $direction] = $this->applySortParams($request, $validSortColumns);

        $tasks = Task::query()
            ->withoutGlobalScope(UserTasksScope::class)
            ->orderBy($sortBy, $direction)->paginate(10);
        return $this->successResponseWithResource("Tasks Lists",  TaskResource::collection($tasks)->response()->getData());
    }
    public function singleUserTasks(Request $request, $user_id): JsonResponse
    {
        $validSortColumns = ['task_status_id', 'due_date', 'created_at'];
        [$sortBy, $direction] = $this->applySortParams($request, $validSortColumns);

        $tasks = Task::query()
            ->withoutGlobalScope(UserTasksScope::class)
            ->where('user_id', $user_id)
            ->orderBy($sortBy, $direction)->paginate(10);
        return $this->successResponseWithResource("Tasks Lists",  TaskResource::collection($tasks)->response()->getData());
    }


    /**
     * @param $id
     * @return JsonResponse
     * @OA\Get(
     *      path="/api/tasks/{id}",
     *      summary="Get a specific task",
     *      tags={"Tasks"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Task details"
     *      )
     *  )
     */
    public function show(Task $task): JsonResponse
    {
        return $this->successResponseWithResource("Task returned", new TaskResource($task), 200);
    }

    /**
     * @param TaskStoreRequest $request
     * @return JsonResponse
     * @OA\Post(
     *      path="/api/tasks",
     *      summary="Create a new task",
     *      tags={"Tasks"},
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"title","task_status_id","description","due_date"},
     *              @OA\Property(property="title", type="string", example="New Task"),
     *              @OA\Property(property="task_status_id", type="integer", example=1),
     *              @OA\Property(property="description", type="string", example="Hello People"),
     *              @OA\Property(property="due_date", type="string", format="date-time", example="2025-05-18T14:25")
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Task created successfully"
     *      )
     *  )
     */
    public function store(TaskStoreRequest $request): JsonResponse
    {

        $dto = $request->toDTO();

        $task = $this->taskService->createTask([
            'title' => $dto->title,
            'description' => $dto->description,
            'task_status_id' => $dto->task_status_id,
            'due_date' => $dto->due_date,
            'user_id' => $dto->userId,
        ]);

        return $this->successResponseWithResource("Task created", new TaskResource($task), 201);
    }

    /**
     * @param TaskUpdateRequest $request
     * @param $id
     * @return JsonResponse
     *
     * @OA\Patch(
     *      path="/api/tasks/{id}/status",
     *      summary="Update task status",
     *      tags={"Tasks"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"task_status_id"},
     *              @OA\Property(property="task_status_id", type="integer", example=4)
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Task status updated successfully"
     *      )
     *  )
     */
    public function update(TaskUpdateRequest $request, Task $task): JsonResponse
    {

        $dto = $request->toDTO();
        $task = $this->taskService->updateTask($task, $dto);

        return $this->successResponseWithResource("Task updated", new TaskResource($task), 200);

    }


    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @OA\Patch(
     *      path="/api/tasks/{id}",
     *      summary="Update tasks",
     *      tags={"Tasks"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"task_status_id"},
     *              @OA\Property(property="task_status_id", type="integer", example=4)
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Task status updated successfully"
     *      )
     *  )
     */
    public function updateStatus(TaskStatusUpdateRequest $request, Task $task): JsonResponse
    {
        $dto = $request->toDTO();
        $task = $this->taskService->updateTaskStatus($task, $dto);

        return $this->successResponseWithResource("Task status updated", new TaskResource($task), 200);
    }

    /**
     * @param $id
     * @return JsonResponse
     *
     * @OA\Delete(
     *      path="/api/tasks/{id}",
     *      summary="Delete a task",
     *      tags={"Tasks"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Task deleted successfully"
     *      )
     *  )
     */
    public function destroy($id): JsonResponse
    {
        $task = Task::query()
            ->when(auth()->user()->hasRole('user'), function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->findOrFail($id);
        $task->delete();
        return $this->successResponse('Task deleted successfully', [], 204);
    }

}
