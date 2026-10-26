<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreTodoRequest;
use App\Http\Requests\Api\V1\UpdateTodoRequest;
use App\Http\Resources\Api\V1\TodoResource;
use App\Models\Todo;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Todo::where('user_id', $user->id);

        if ($request->boolean('completed') !== null) {
            $query->where('is_completed', $request->boolean('completed'));
        }

        $todos = $query->orderBy('due_at', 'asc')->paginate(15);

        return ApiResponse::success(
            data: TodoResource::collection($todos),
            message: 'Todos retrieved.',
            code: 'TODOS_RETRIEVED',
        );
    }

    public function store(StoreTodoRequest $request)
    {
        $user = $request->user();
        $todo = Todo::create(array_merge($request->validated(), [
            'user_id' => $user->id,
        ]));

        return ApiResponse::success(
            data: new TodoResource($todo),
            message: 'Todo created.',
            code: 'TODO_CREATED',
            status: 201,
        );
    }

    public function show(Request $request, Todo $todo)
    {
        if ($todo->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }

        return ApiResponse::success(
            data: new TodoResource($todo),
            message: 'Todo retrieved.',
            code: 'TODO_RETRIEVED',
        );
    }

    public function update(UpdateTodoRequest $request, Todo $todo)
    {
        if ($todo->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }

        $todo->update($request->validated());

        return ApiResponse::success(
            data: new TodoResource($todo),
            message: 'Todo updated.',
            code: 'TODO_UPDATED',
        );
    }

    public function destroy(Request $request, Todo $todo)
    {
        if ($todo->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }

        $todo->delete();

        return ApiResponse::success(
            message: 'Todo deleted.',
            code: 'TODO_DELETED',
        );
    }
}