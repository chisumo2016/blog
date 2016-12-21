<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\PostsCreateRequest;
use App\Photo;
use App\Post;
use Illuminate\Http\Request;


use App\Http\Requests;
use Illuminate\Support\Facades\Auth;


class AdminPostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //$posts = Post ::all();
        $posts = Post ::paginate(2);

        return view ('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //

        $categories = Category::lists('name', 'id')->all();

        return view ('admin.posts.create', compact('categories'));
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostsCreateRequest $request)
    {
        //Store information in the databse
        $input = $request->all();  // asssign the request to the input
        //$title = str_slug($request->title, '-');
        $user = Auth::user();
        if($file = $request->file('photo_id')){

           $name  = time(). $file->getClientOriginalName();
           $file ->move('images',$name);
          $photo = Photo::create(['file' =>$name]);

          $input['photo_id'] = $photo->id;
        }

        //return $request->all();
        $user->posts()->create(  $input);
        return redirect('/admin/posts');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //Find all Post
        $post       =  Post::findOrFail($id);
        $categories =  Category::lists('name', 'id')->all();

       return view('admin.posts.edit', compact('post','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //update Post
        $input     = $request ->all();

        if($file   = $request->file('photo_id')){
            $name  = time(). $file->getClientOriginalName();
            $file ->move('images',$name);
            $photo = Photo :: create(['file' =>$name]);

            $input['photo_id'] = $photo->id;
        }
        Auth::user()->posts()->whereId($id)->first()->update(  $input );

        return redirect('/admin/posts');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //

        //Delete post
        //$post = Post::findOrFail($id)->delete();
        $post = Post::findOrFail($id);
        unlink(public_path() . $post->photo->file);
        $post->delete();
        return redirect('/admin/posts');
       /// Session::flash('deleted_post', 'The post has been deleted');

        //return "DESTROY";
    }

    public  function  post($slug)
    {

        //$post = Post::findOrFail($id);

         $post = Post::findBySlugOrFail($slug);
         $comments = $post->comments()->whereIsActive(1)->get();


        return view('post', compact('post', 'comments'));

    }
}
