<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\HasResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleAPIController extends Controller
{
    use HasResponseTrait;
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $roles = Role::query()->select('id', 'name')->get();
        return $this->successResponseWithResource("Roles Lists",  $roles);
    }
}
