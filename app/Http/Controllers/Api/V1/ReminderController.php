<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreReminderRequest;
use App\Http\Requests\Api\V1\UpdateReminderRequest;
use App\Http\Resources\Api\V1\ReminderResource;
use App\Models\Reminder;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $reminders = Reminder::where('user_id', $user->id)
            ->orderBy('reminder_at', 'asc')
            ->paginate(15);

        return ApiResponse::success(
            data: ReminderResource::collection($reminders),
            message: 'Reminders retrieved.',
            code: 'REMINDERS_RETRIEVED',
        );
    }

    public function store(StoreReminderRequest $request)
    {
        $user = $request->user();
        $reminder = Reminder::create(array_merge($request->validated(), [
            'user_id' => $user->id,
        ]));

        return ApiResponse::success(
            data: new ReminderResource($reminder),
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
            data: new ReminderResource($reminder),
            message: 'Reminder retrieved.',
            code: 'REMINDER_RETRIEVED',
        );
    }

    public function update(UpdateReminderRequest $request, Reminder $reminder)
    {
        if ($reminder->user_id !== $request->user()->id) {
            return ApiResponse::forbidden();
        }

        $reminder->update($request->validated());

        return ApiResponse::success(
            data: new ReminderResource($reminder),
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