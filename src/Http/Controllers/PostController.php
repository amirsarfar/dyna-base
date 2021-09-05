<?php

namespace Amirsarfar\DynaBase\Http\Controllers;

use Amirsarfar\DynaBase\Models\Post;
use Amirsarfar\DynaBase\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Str;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //* so many things are available to do to get this job done
        $route_array = explode('.', $request->route()->getName());
        if($type = Type::where('key', $route_array[0])->first()){
            $posts = $type->posts->pluck('json_rep');
            return response([Str::plural($route_array[0]) => $posts]);
        }
        return response(['error' => 'Resources not found'], Response::HTTP_NOT_FOUND);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // TODO Validations
        $route_array = explode('.', $request->route()->getName());
        $type = Type::where('key', $route_array[0])->first();
        $post = Post::create([
            'type_id' => $type->id
        ]);
        foreach($type->definition as $attribute){
            $attribute_name = $attribute->name;
            $post->$attribute_name =  $request->get($attribute_name);
        }
        return response([$route_array[0] => $post->json_rep]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //* so many things are available to do to get this job done
        $route_array = explode('.', $request->route()->getName());
        if($type = Type::where('key', $route_array[0])->first()){
            $post = $type->posts()->where('id', $id)->first();
            return response([$route_array[0] => $post->json_rep]);
        }
        return response(['error' => 'Resources not found'], Response::HTTP_NOT_FOUND);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // TODO Validations
        $route_array = explode('.', $request->route()->getName());
        $type = Type::where('key', $route_array[0])->first();
        $post = $type->posts()->where('id', $id)->first();
        foreach($type->definition as $attribute){
            $attribute_name = $attribute->name;
            $post->$attribute_name =  $request->get($attribute_name);
        }
        return response([$route_array[0] => $post->json_rep]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $route_array = explode('.', $request->route()->getName());
        $type = Type::where('key', $route_array[0])->first();
        $post = $type->posts()->where('id', $id)->first();
        $post->delete();
        return response(['deleted' => $post->id]);
    }
}
