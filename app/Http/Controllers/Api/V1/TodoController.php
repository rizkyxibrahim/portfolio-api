<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Todo::where('user_id', $user->id);
        if ($request->boolean('completed') !== null) {
            $query->where('is_completed', $request->boolean('completed'));
        }
        $todos = $query->orderBy('due_date', 'asc')->paginate(15);
        return ApiResponse::success(
            data: $todos,
            message: 'Todos retrieved.',
            code: 'TODOS_RETRIEVED',
        );
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_completed' => 'sometimes|boolean',
            'due_date' => 'nullable|date',
            'priority' => 'sometimes|in:low,medium,high',
        ]);
        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors()->toArray());
        }
        $todo = Todo::create(array_merge($validator->validated(), [
            'user_id' => $user->id,
        ]));
        return ApiResponse::success(
            data: $todo,
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
            data: $todo,
            message: 'Todo retrieved.',
            code: 'TODO_RETRIEVED',
        );
    }

    public function update(Request $request, Todo $todo)
    {
        if ($todo->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'is_completed' => 'sometimes|boolean',
            'due_date' => 'nullable|date',
            'priority' => 'sometimes|in:low,medium,high',
        ]);
        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors()->toArray());
        }
        $todo->update($validator->validated());
        return ApiResponse::success(
            data: $todo,
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
