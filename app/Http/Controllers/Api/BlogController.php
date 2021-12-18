<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    // CREATE BLOG
    public function create(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'name' => 'required',
            'title' => 'required',
            'description' => ' required'
        ]);

        //blog id + Create
        $user_id = auth()->user()->id;

        $blog = new Blog();

        $blog->user_id = $user_id;
        $blog->category_id = $request->category_id;
        $blog->name = $request->name;
        $blog->title = $request->title;
        $blog->description = $request->description;

        $blog->save();

        //send response
        return response()->json([
            'message' => "Blog has been created"
        ]);
    }

    // BLOG LIST
    public function blogs()
    {
        return response()->json([
            Blog::all()
        ]);
    }

    // SINGLE POST
    public function singleBlog($id)
    {
        $blog = Blog::find($id);
        if($blog){
            return response()->json([
                $blog,
                $blog->comments
            ]);
        }else{
            return response()->json([
                "message" => "Blog not found"
            ]);
        }
        
    }

    // DELETE POST
    public function delete($id)
    {
        $blog = Blog::find($id);
        if($blog){
            $blog->delete();
            return response()->json([
                "message" => "Blog deleted successfully.",
            ]);
        }else{
            return response()->json([
                "message" => "Blog not found.",
            ]);
        }
        
    }
}
