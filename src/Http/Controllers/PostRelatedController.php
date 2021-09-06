<?php

namespace Amirsarfar\DynaBase\Http\Controllers;

use Amirsarfar\DynaBase\Models\Post;
use Amirsarfar\DynaBase\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Str;

class PostRelatedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id)
    {
        $route_array = explode('.', $request->route()->getName());
        $type = Type::where('key', $route_array[0])->first();
        if($type && $post = $type->posts()->where('id', $id)->first()){
            $related = $route_array[1];
            $post_relateds = $post->$related->get();
            return response([$route_array[1] => $post_relateds]);
        }
        return response(['error' => 'Resources not found'], Response::HTTP_NOT_FOUND);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id, $related_id)
    {
        $route_array = explode('.', $request->route()->getName());
        $type = Type::where('key', $route_array[0])->first();
        $related = $route_array[1];
        if($type && $post = $type->posts()->where('id', $id)->first()){
            if($post && $post_related = $post->$related->where('id', $related_id)->first()){
                return response([Str::singular($route_array[1]) => $post_related]);
            }
        }
        return response(['error' => 'Resources not found'], Response::HTTP_NOT_FOUND);
    }



    public function addChild(Request $request, $id)
    {
        return $this->handlePostRelatedRequest('addChildren', $request, $id);
    }

    public function removeChild(Request $request, $id)
    {
        return $this->handlePostRelatedRequest('removeChildren', $request, $id);
    }

    public function syncChild(Request $request, $id)
    {
        return $this->handlePostRelatedRequest('syncChildren', $request, $id);
    }

    public function addParent(Request $request, $id)
    {
        return $this->handlePostRelatedRequest('addParents', $request, $id);
    }

    public function removeParent(Request $request, $id)
    {
        return $this->handlePostRelatedRequest('removeParents', $request, $id);
    }

    public function syncParent(Request $request, $id)
    {
        return $this->handlePostRelatedRequest('syncParents', $request, $id);
    }
    
    public function handlePostRelatedRequest($dyna_relation_method, Request $request, $id)
    {
        // get all route parameters coded in route name
        $route_array = explode('.', $request->route()->getName());
        $type_key = $route_array[0];
        $related_name = $route_array[1];
        $action = $route_array[2];
        
        $type = Type::where('key', $type_key)->first();
        $related_type = $type->getRelatedTypeByName($related_name);
        $related_attribute = $type->getRelatedAttributeByName($related_name);

        if($type && $post = $type->posts()->where('id', $id)->first()){
            $ids = $request->get($related_name);
            if(! is_array($ids))
                $ids = array($ids);
            
            // check if all ids are of related type
            if($related_type->posts()->whereIn('id', $ids)->count() != count($ids))
                return response(['error' => 'Related Ids are not valid'], Response::HTTP_BAD_REQUEST);
            
            // check relations count for 'one' relations
            if($action == 'add' && $related_attribute->options->count == 'one' && (count($ids) + $post->$related_name->count()) > 1)
                return response(['error' => 'More than 1 related id for relation with count \'one\'. You should remove the existing relation before trying again.'], Response::HTTP_BAD_REQUEST);
            if($action == 'sync' && $related_attribute->options->count == 'one' && count($ids) > 1)
                return response(['error' => 'More than 1 related id for relation with count \'one\'.'], Response::HTTP_BAD_REQUEST);
            
            $post->$dyna_relation_method($ids, $request->get('reference_key'), $related_name);
            return response([$related_name => $ids]);
        }
        return response(['error' => 'Resources not found'], Response::HTTP_BAD_REQUEST);
    }
}
