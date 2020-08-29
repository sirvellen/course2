<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Task[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */
    public function index()
    {
        return Task::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function store(Request $request)
    {
        if ($task = Task::create([
            'list_id' => $request->list_id,
            'task_name' => $request->task_name,
            'task_description' => $request->task_description,
        ])) {
            return response()->json($task)->setStatusCode(201, 'Successful Created');
        } else return response()->json()->setStatusCode(400, 'Bad end');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function show(Request $request)
    {
        $data = Task::select()->where('id', $request->task_id)->get();

        return response()->json($data)->setStatusCode(201, 'Successful Found');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function update(Request $request)
    {
        $task = DB::table('tasks')->where('id', $request->task_id)
            ->update([
                'task_name' => $request->task_name,
                'task_description' => $request->task_description,
                ]);
        return response()->json($task)->setStatusCode(202, 'Successful Edited');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function destroy(Request $request)
    {
        $task = DB::table('tasks')->where('id', $request->task_id)->delete();

        return response()->json($task)->setStatusCode(202, 'Successful deleted');
    }

    public function done(Request $request) {
        $task = DB::table('tasks')->where('id', $request->task_id)->update(['task_done' => true]);

        return response()->json($task)->setStatusCode(202, 'Successful marked');
    }

    public function undone(Request $request) {
        $task = DB::table('tasks')->where('id', $request->task_id)->update(['task_done' => false]);

        return response()->json($task)->setStatusCode(202, 'Successful marked');
    }
}
