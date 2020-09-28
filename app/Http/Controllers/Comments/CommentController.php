<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comments\StoreComment;
use App\Http\Requests\Comments\UpdateComment;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Design;
use App\Repositories\Contracts\IComment;
use App\Repositories\Contracts\IDesign;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $comments, $designs;

    public function __construct(IComment $comments, IDesign $designs)
    {
        $this->comments = $comments;
        $this->designs = $designs;
    }

    public function store(StoreComment $request, Design $design)
    {
        $comment = $this->designs->addComment($design, [
            'body'=> $request->body,
            'user_id'=> auth()->id()
        ]);

        return new CommentResource($comment);

    }

    public function update(UpdateComment $request, Comment $comment)
    {
        $comment = $this->comments->update($comment, ['body'=>$request->body]);
        return new CommentResource($comment);

    }

    public function destroy()
    {


    }

}
