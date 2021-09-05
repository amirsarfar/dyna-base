<?php

namespace Amirsarfar\DynaBase\Http\Controllers;

use Amirsarfar\DynaBase\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = Type::all();
        return response(compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return response($request->input('definition'));
        if(Type::where(['key' => $request->input('key')])->first()){
            return response(
                [
                    'error' => 'Type key has been already taken !'
                ],
                Response::HTTP_CONFLICT
            );
        }
        $type = Type::create([
            'key' => $request->input('key'),
            'title' => $request->input('title'),
            'definition' =>  json_encode($request->input('definition')),
        ]);
        return response(compact('type'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function show(Type $type)
    {
        return response(compact('type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Type $type)
    {
        if(Type::where(['key' => $request->input('key')])->first()->id != $type->id){
            return response(
                [
                    'error' => 'Type key has been already taken !'
                ],
                Response::HTTP_CONFLICT
            );
        }
        $type->update([
            'key' => $request->input('key'),
            'title' => $request->input('title'),
            'definition' =>  json_encode($request->input('definition')),
        ]);
        return response(compact('type'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Type $type)
    {
        $type->delete();
        return response(['deleted' => $type->id]);
    }
}
