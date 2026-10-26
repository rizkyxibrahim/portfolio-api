<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReminderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $reminders = Reminder::where('user_id', $user->id)
            ->orderBy('reminder_at', 'asc')
            ->paginate(15);
        return ApiResponse::success(
            data: $reminders,
            message: 'Reminders retrieved.',
            code: 'REMINDERS_RETRIEVED',
        );
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'reminder_at' => 'required|date',
            'is_sent' => 'sometimes|boolean',
            'is_completed' => 'sometimes|boolean',
        ]);
        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors()->toArray());
        }
        $reminder = Reminder::create(array_merge($validator->validated(), [
            'user_id' => $user->id,
        ]));
        return ApiResponse::success(
            data: $reminder,
            message: 'Reminder created.',
            code: 'REMINDER_CREATED',
            status: 201,
        );
    }

    public function show(Request $request, Reminder $reminder)
    {
        if ($reminder->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }
        return ApiResponse::success(
            data: $reminder,
            message: 'Reminder retrieved.',
            code: 'REMINDER_RETRIEVED',
        );
    }

    public function update(Request $request, Reminder $reminder)
    {
        if ($reminder->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'reminder_at' => 'sometimes|date',
            'is_sent' => 'sometimes|boolean',
            'is_completed' => 'sometimes|boolean',
        ]);
        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors()->toArray());
        }
        $reminder->update($validator->validated());
        return ApiResponse::success(
            data: $reminder,
            message: 'Reminder updated.',
            code: 'REMINDER_UPDATED',
        );
    }

    public function destroy(Request $request, Reminder $reminder)
    {
        if ($reminder->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }
        $reminder->delete();
        return ApiResponse::success(
            message: 'Reminder deleted.',
            code: 'REMINDER_DELETED',
        );
    }
}
