<?php

namespace App\Http\Controllers;

use App\Desk;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Concerns\InteractsWithInput;
use Illuminate\Support\Str;

class DeskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Desk[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */
    public function index()
    {
        return Desk::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(),
            [
                'project_name' => ['required', 'string'],
                'project_description' => ['required', 'string'],
                'project_deadline' => ['required', 'string'],
            ]);

        if ($validated->fails()) {
            return response($validated->messages(), 400);
        }
        $token = $request->bearerToken();
        $user = User::query()->select('id')->where('api_token', $token)->first();
        try {
            $desk = Desk::create([
                'project_creator' => $user->id,
                'project_name' => $request->project_name,
                'project_description' => $request->project_description,
                'project_deadline' => $request->project_deadline,
            ]);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage())->setStatusCode(400, 'Bad request');
        }
        return response()->json($desk)->setStatusCode(201, 'Successful Created');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Desk::all()->where('id', $id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function update($desk_id, Request $request)
    {
        $validated = Validator::make($request->all(), $request->rules());

        if ($validated->fails()) {
            return response($validated->messages(), 400);
        }
        try {
            $desk = Desk::query()->where('id', $desk_id)
                ->update([
                    'project_name' => $request->project_name,
                    'project_description' => $request->project_description,
                    'project_deadline' => $request->project_deadline,
                ]);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage())->setStatusCode(400, 'Bad request');
        }
        return response()->json($desk)->setStatusCode(202, 'Successful Edited');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function destroy($id)
    {
        try {
            $desk = Desk::query()->where('id', $id)->delete();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage())->setStatusCode(400, 'Bad request');
        }
        return response()->json($desk)->setStatusCode(200, 'Successful deleted');
    }

    /**
     * Get the bearer token from the request headers.
     *
     * @return string|null
     */
    public function bearerToken()
    {
        $header = $this->header('Authorization', '');
        if (Str::startsWith($header, 'Bearer ')) {
            return Str::substr($header, 7);
        }
    }
}
