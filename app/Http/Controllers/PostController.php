<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostImage;
use Auth;
use Storage;

class PostController extends Controller
{
    public function getAllPosts() 
    {
        $posts = Post::with('post_images', 'author')->orderBy('created_at', 'desc')->get();
        return response()->json(['error' => false, 'data' => $posts]);
    }

    public function createPost(Request $request) 
    {
        $user = Auth::user();
        $title = $request->title;
        $body = $request->body;
        $image = $request->image;

        $post = Post::create([
            'title' => $title,
            'body' => $body,
            'user_id' => $user->id
        ]);

        $imagePath = Storage::disk('uploads')->put($user->email . '/posts', $image);
            PostImage::create([
                'post_image_caption' => $title,
                'post_image_path' => '/uploads/' . $imagePath,
                'post_id' => $post->id
            ]);

        return response()->json(['error' => false, 'data' => $post]);
    }

    public function editPost(Request $request)
    {
        $user = Auth::user();
        $title = $request->title;
        $body = $request->body;
        $image = $request->image;
        $previousImage = $request->previousImage;
        $id = $request->id;

        $post = Post::where('id', $id)
                    ->update([
                        'title' => $title,
                        'body' => $body,
                    ]);
        
        if ($image) {
            PostImage::where('id',$previousImage)->delete();
            $imagePath = Storage::disk('uploads')->put($user->email . '/posts', $image);
            PostImage::create([
                'post_image_caption' => $title,
                'post_image_path' => '/uploads/' . $imagePath,
                'post_id' => $id
            ]);
        }

        return response()->json(['error' => false, 'data' => $post]);
    }

    public function deletePost(Request $request)
    {
        $id = $request->id;
        PostImage::where('post_id', $id)->delete();
        $deletePost = Post::with('post_images')->where('id', $id)->delete();
        return response()->json(['error' => false, 'data' => $deletePost]);
    }

    public function getUserPosts()
    {
        $user = Auth::user();
        $posts = Post::with('post_images', 'author')->orderBy('created_at', 'desc')->where('user_id', $user->id)->get();
        return response()->json(['error' => false, 'data' => $posts]);
    }

    public function getPostById($id)
    {
        $post = Post::with('post_images', 'author')->orderBy('created_at', 'desc')->where('id', $id)->get();
        return response()->json(['error' => false, 'data' => $post]);
    }
}
