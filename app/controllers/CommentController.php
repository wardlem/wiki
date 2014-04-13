<?php

class CommentController extends \Illuminate\Routing\Controller
{
    public function postComment(Page $page)
    {
        if (!Input::has('content')){
            Session::flash('error', 'Comment posted with no content set');
        } else {
            $comment = new Comment();
            $comment->page_id = $page->id;
            $comment->parent_comment_id = null;
            $comment->user_id = Auth::user()->id;
            $comment->content = Input::get('content');

            $comment->save();
        }

        return Redirect::route('page', array('page' => $page->slug, 'tab' => 'discussion'));

    }

    public function updateComment(Comment $comment)
    {

    }

    public function deleteComment(Comment $comment)
    {

    }

    public function replyToComment(Comment $comment)
    {
        if (!Input::has('content')){
            Session::flash('error', 'Comment posted with no content set');
        } else {
            $reply = new Comment();
            $reply->page_id = $comment->page_id;
            $reply->parent_comment_id = $comment->id;
            $reply->user_id = Auth::user()->id;
            $reply->content = Input::get('content');

            $reply->save();
        }

        return Redirect::route('page', array('page' => $comment->page->slug, 'tab' => 'discussion'));

    }
}