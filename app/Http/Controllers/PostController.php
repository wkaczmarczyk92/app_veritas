<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

use Inertia\Inertia;
use App\Models\PostLabel;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

use App\Services\Post\PostServiceGetter;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Post::with('post_labels')
            ->orderBy('order')
            ->get();

        return Inertia::render('Admin/PostList', [
            'data' => $data,
            'post_labels' => PostLabel::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/PostCreate', [
            'post_labels' => PostLabel::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
                'title' => 'required|string',
                'body' => 'required|string',
                'start_at' => 'sometimes|nullable|date|date_format:Y-m-d',
                'end_at' => 'sometimes|nullable|date|date_format:Y-m-d',
                'type' => 'required|string',
                'label_id' => 'required|integer'
            ]);

            $max = Post::max('order');

            Post::create([
                'title' => $request->title,
                'body' => $request->body,
                'start_at' => $request->start_at,
                'end_at' => $request->end_at,
                'type' => $request->type,
                'label_id' => $request->label_id,
                'order' => ($max + 1)
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()]);
        }

        return response()->json(['success' => true]);
    }

    public function updateOrder(Request $request)
    {
        // return response()->json(['success' => false]);
        $count_posts = Post::count();
        $min_order = Post::min('order');
        $max_order = Post::max('order');

        $new_order_start = $min_order > $count_posts ? 1 : ($max_order + 1);
        $posts = $request->posts;

        // dd($count_posts, $min_order, $max_order, $new_order_start);
        // dd($posts);

        DB::beginTransaction();

        try {
            foreach ($posts as $post) {
                DB::table('posts')
                    ->where('id', $post['id'])
                    ->update(['order' => $new_order_start]);

                $new_order_start++;
            }


            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false
            ]);
        }

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // dd($post);
        return Inertia::render('Admin/PostEdit', [
            'post' => $post,
            'post_labels' => PostLabel::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        try {
            $validate = $request->validate([
                'title' => 'required|string',
                'body' => 'required|string',
                'start_at' => 'sometimes|nullable|date|date_format:Y-m-d',
                'end_at' => 'sometimes|nullable|date|date_format:Y-m-d',
                'type' => 'required|string',
                'label_id' => 'required|integer'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()]);
        }

        $post->title = $request->title;
        $post->body = $request->body;
        $post->start_at = $request->start_at;
        $post->end_at = $request->end_at;
        $post->type = $request->type;
        $post->label_id = $request->label_id;
        $post->save();

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $response = $post->delete()
            ? ['success' => true]
            : ['success' => false];

        return response()->json($response);
    }
}
