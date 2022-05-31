<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Http\Resources\PostMoreResource;
use App\Http\Resources\PostResource;
use App\Mail\AdminDailyReport;
use App\Models\Admin;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
        $post = $request->validated();

        $links = implode(" ", $post['links']);
        $post['links'] = $links;

        $createdPost = Post::create($post);

        return [
            'id' => $createdPost->id,
            'message' => 'Success!'
        ];
    }

    public function edit(PostStoreRequest $request, $id)
    {
        $post = Post::find($id);
        $newData = $request->validated();

        $links = implode(" ", $newData['links']);
        $newData['links'] = $links;

        $post->update($newData);
        return response([
            'post' => new PostMoreResource($post)
        ], 201);
    }
}
