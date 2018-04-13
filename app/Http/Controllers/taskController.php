<?php

namespace App\Http\Controllers;

use App\task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class taskController extends Controller
{
    public function create(Request $request){
        $validatedData = $request->validate([
            'type' => 'required|boolean',
            'description' => 'required|string'
        ]);
        if($request->type == 0){
            $validatedData = $request->validate([
                'date' => 'required|date'
            ]);
        }
        else{
            $validatedData = $request->validate([
                'locationX' => 'required|numeric',
                'locationY' => 'required|numeric'
            ]);
        }
        $task = new task();
        $request['user_id'] = Auth::user()->id;
        $task->fill($request->all());
        $task->save();
        return redirect('/tasks');
    }
    public function read(){
        $tasks = task::where('user_id',Auth::user()->id)->get();
        return $tasks;
    }
    public function show(){
        $tasks = task::where('user_id',Auth::user()->id)->get();
        return view('tasks',['tasks' => $tasks]);
    }
    public function update(Request $request){
        $task = task::where('id',$request->id)->first();
        if(Auth::user()->id == $task->user_id){
            $request['user_id'] = Auth::user()->id;
            $task->fill($request->all());
            $task->save();
        }
        return redirect('/tasks');
    }
    public function delete(Request $request){
        $task = task::where('id',$request->id)->first();
        if(Auth::user()->id == $task->user_id){
            $task->delete();
        }
        return redirect('/tasks');
    }
    public function trigger(Request $request){
        $tasks = array();
        if(task::where('user_id',Auth::user()->id)->whereBetween('date',
            [Carbon::now()->subHours(4)->subMinutes(5),
            Carbon::now()->subHours(4)->addMinutes(5)])->where('notified',0)->get()->count()){
            $tasks['dates'] = task::whereBetween('date', [Carbon::now()->subHours(4)->subMinutes(5), Carbon::now()->subHours(4)->addMinutes(5)])->where('notified',0)->get();
            foreach ($tasks['dates'] as $task){
                $task->notified = 1;
                $task->save();
            }
        }
        else{
            $tasks['dates'] = ['none'];
        }
        if(task::where('user_id',Auth::user()->id)->whereBetween('locationX',
                [$request->locationX - .5, $request->locationX + .5])->get()->count() &&
            task::whereBetween('locationY', [$request->locationY - .5, $request->locationY + .5])->
            where('notified',0)->get()->count()){
            $tasks['locations'] = task::whereBetween('locationX', [$request->locationX - .5, $request->locationX + .5])->where('notified',0)->get();
            foreach ($tasks['locations'] as $task){
                $task->notified = 1;
                $task->save();
            }
        }
        else{
            $tasks['locations'] = ['none'];
        }
        return $tasks;
    }
}
