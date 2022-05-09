<?php

namespace App\Http\Controllers;

use App\Http\Requests\LinkStoreRequest;
use App\Http\Requests\PostStoreRequest;
use App\Http\Resources\PostMoreResource;
use App\Http\Resources\PostResource;
use App\Models\Link;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function front()
    {
        return PostResource::collection(Post::all());
    }

    public function back(Request $request)
    {
        $query = Post::query();

        if ($id = $request->input('id')) {
            $query->where('id', '=', $id);
            if ($request->input('fields')) {
                return PostMoreResource::collection($query->get());
            }
        }

        if ($sortP = $request->input('sortP')) {
            $query->orderBy('price', $sortP);
        }

        if ($sortD = $request->input('sortD')) {
            $query->orderBy('created_at', $sortD);
        }

        $perPage = 10;
        $page = $request->input('page', 1);
        $total = $query->count();


        $result = $query->offset(($page - 1) * $perPage)->limit($perPage)->get();

        return [
            'data' => PostResource::collection($result),
            'total' => $total,
            'page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }

    public function create(PostStoreRequest $request)
    {
    
        $createdPost = Post::create($request->validated());

        return [
            'id' => $createdPost->id,
            'code' => http_response_code()
        ];
    }
}
