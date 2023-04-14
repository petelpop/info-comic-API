<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(Request $request){
        $request -> validate([
            'comic_id' => 'required|exists:comics,id',
            'comment_content' => 'required'
        ]);

        $request['user_id'] = auth()->user()->id;

        $comment = Comment::create($request->all());

        return response()->json($comment);
    }
}
