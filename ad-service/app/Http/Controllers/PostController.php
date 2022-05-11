<?php

namespace App\Http\Controllers;

use App\Http\Requests\LinkStoreRequest;
use App\Http\Requests\PostStoreRequest;
use App\Http\Resources\PostMoreResource;
use App\Http\Resources\PostResource;
use App\Models\Link;
use App\Models\Post;
use Exception;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Mockery\Exception\InvalidOrderException;

class PostController extends Controller
{

    public function index(Request $request)
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

        $links = array();

        $links = $request->input('links.*');

        foreach ($links as $l) {
            $linksr[] =  new Link([
                'link' => $l,
            ]);
        }


        $createdPost->links()->saveMany($linksr);

        return [
            'id' => $createdPost->id,
            'message' => 'Success!'
        ];
    }
}
