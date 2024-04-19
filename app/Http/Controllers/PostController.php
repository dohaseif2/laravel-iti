<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use \Cviebrock\EloquentSluggable\Services\SlugService;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    function index(){
        $posts=Post::paginate(2);
        return view('posts.index',["posts"=>$posts]);
    }
    function create(){
        return view('posts.create');
    }
    function store(StorePostRequest $request){
        $file = $request->file('avatar');
        $extension = $file->getClientOriginalExtension();
        $name = time().".".$extension;
        $file->move('uploads/posts',$name);

        $post= new Post();
        $post->title = $request->title;
        $post->body = $request->body;
        $post->user_id = $request->author;
        $post->avatar=$name;
        $post->save();
        return redirect()->route('posts.index');
    }
    function show($post){
        $post=Post::find($post);
        return view('posts.show',['post'=>$post]);
    }
    function edit($post){
        $post=Post::find($post);
        return view('posts.edit',['post'=>$post]);
    }
    function update(StorePostRequest $request,$post){
        $file = $request->file('avatar');
        $extension = $file->getClientOriginalExtension();
        $name = time().".".$extension;
        $file->move('uploads/posts',$name);

        $post=Post::find($post);
        $post->title = $request->title;
        $post->body = $request->body;
        $post->user_id = $request->author;
        $post->avatar=$name;
        $post->save();
        return redirect()->route('posts.show', ['post' => $post]);
    }
    function destroy($post){
        $post=Post::find($post);
        $post->delete();
        return redirect()->route('posts.index');
    }
}
