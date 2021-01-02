<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function index() {
        // $todos = Todo::all();
        $todos = Todo::simplePaginate(2);

        return $todos;

        // $result['data'] = $todos->items();
        // $result['current_page'] = $todos->currentPage();
        // return $result;
    }

    public function search(Request $request) {
        $search = $request->get('search');

        $todos = Todo::where('title', 'like', "%{$search}%")->simplePaginate(2);
        // $todos = Todo::where('title', 'like', "%{$search}%")->get();

        return $todos;
        // return response()->json($todos);
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3'
        ]);

        if ($validator->fails()) {
            return response()
                ->json(['error' => $validator->errors()])
                ->setStatusCode(400);
        }

        $todo = new Todo();
        $todo->title = $request->input('title');
        $todo->save();

        return response()
            ->json($todo)
            ->setStatusCode(201);
    }

    public function show($id) {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()
                ->json(['error' => 'Todo not found'])
                ->setStatusCode(404);
        }

        return $todo;
    }

    public function update($id, Request $request) {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()
                ->json(['error' => 'Todo not found'])
                ->setStatusCode(404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'min:3',
            'done' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()
                ->json(['error' => $validator->errors()])
                ->setStatusCode(400);
        }

        $title = $request->input('title');
        $done = $request->input('done');

        if ($title) {
            $todo->title = $title;
        }

        if ($done !== NULL) {
            $todo->done = $done;
        }

        $todo->save();

        return response()->json($todo)->setStatusCode(202);
    }

    public function delete($id) {
        $todo = Todo::find($id);

        if (!$todo) {
            return response()
                ->json(['error' => 'Todo not found'])
                ->setStatusCode(404);
        }

        $todo->delete();

        return response()->json()->setStatusCode(204);
    }
}
