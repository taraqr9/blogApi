<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // CREATE COMMENT
    public function create(Request $request)
    {
        $request->validate([
            'blog_id' => 'required',
            'comment' => 'required',
        ]);

        $comment = new Comment();

        $comment->user_id = auth()->user()->id;
        $comment->blog_id = $request->blog_id;
        $comment->comment = $request->comment;

        $comment->save();

        //send response
        return response()->json([
            'message' => "Comment posted successfully."
        ]);
    }

    // COMMENT LIST
    public function comments()
    {
        return response()->json([
            Comment::all()
        ]);
    }

    // SINGLE COMMENT
    public function singleComment($id)
    {
        if(Comment::find($id)){
            return response()->json([
                Comment::find($id)
            ]);
        }else{
            return response()->json([
                "message" => "Comment not found"
            ]);
        }
        
    }

    // DELETE COMMENT
    public function delete($id)
    {
        $comment = Comment::find($id);
        if($comment){
            $comment->delete();
            return response()->json([
                "message" => "Comment deleted successfully.",
            ]);
        }else{
            return response()->json([
                "message" => "Comment not found.",
            ]);
        }
        
    }
}
