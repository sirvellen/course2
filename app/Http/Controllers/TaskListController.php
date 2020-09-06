<?php

namespace App\Http\Controllers;

use App\Exceptions\TaskException;
use App\Http\Requests\TaskListRequest;
use App\Http\Requests\TaskListUpdateRequest;
use App\TaskList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TaskListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return TaskList[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */
    public function index()
    {
        return TaskList::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function store(TaskListRequest $request)
    {

        $validated = $request->validated();

        if ($validated->fails()) {
            return response($validated->messages(), 400);
        }

        try {
            $tasklist = TaskList::create([
            'desk_id' => $request->desk_id,
            'list_name' => $request->list_name,
            ]);
        } catch (TaskException $exception) {
            return response()->json()->setStatusCode(400, 'Bad request');
        }
            return response()->json($tasklist)->setStatusCode(201, 'Successful Created');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function show(Request $request)
    {
//        $data = TaskList::select('id', 'desk_id', 'list_name')->where('id', $request->list_id)->get();
        try {
            $data = TaskList::select()->where('id', $request->list_id)->get();
        } catch (TaskException $exception) {
            return response()->json()->setStatusCode(400, 'Bad request');
        }
        return response()->json($data)->setStatusCode(200, 'Successful Found');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TaskList $tasklist
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function update(TaskListRequest $request)
    {
        $validated = $request->validated();

        if ($validated->fails()) {
            return response($validated->messages(), 400);
        }
        try {
            $tasklist = TaskList::query()->where('id', $request->list_id)
                ->update(['list_name' => $request->list_name]);
        } catch (TaskException $exception) {
            return response()->json()->setStatusCode(400, 'Bad request');
        }
        return response()->json($tasklist)->setStatusCode(202, 'Successful Edited');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function destroy(Request $request)
    {
        try {
            $taskList = TaskList::query()->where('id', $request->list_id)->delete();
        } catch (TaskException $exception) {
            return response()->json()->setStatusCode(400, 'Bad request');
        }
        return response()->json($taskList)->setStatusCode(200, 'Successful deleted');
    }
}
