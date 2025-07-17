<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRoleUpdateRequest;
use App\Models\User;
use App\Traits\HasResponseTrait;
use Illuminate\Http\JsonResponse;

class UserRolePermissionAPIController extends Controller
{
    use HasResponseTrait;
    public function updateUserRole(UserRoleUpdateRequest $request, User $user): JsonResponse
    {
        $user->syncRoles($request->validated('role_id'));
        $user->refresh();
        return $this->successResponseWithResource("User Role Updated", $user);

    }
}
