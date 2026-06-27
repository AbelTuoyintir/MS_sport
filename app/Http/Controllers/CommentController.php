<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'article_id' => 'required|exists:articles,id',
            'user_name' => 'required|string|max:100',
            'content' => 'required|string|max:1000',
        ]);

        Comment::create($validated);

        return back()->with('success', 'Comment posted successfully.');
    }
}
