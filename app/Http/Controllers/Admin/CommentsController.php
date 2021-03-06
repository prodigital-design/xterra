<?php

namespace App\Http\Controllers\Admin;

use App\Blog\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;

class CommentsController extends Controller
{

    public function index()
    {
        $start_date = request('start', Carbon::today()->subDays(14)->format('Y-m-d'));
        $end_date = request('end', Carbon::today()->format('Y-m-d'));

        return Comment::with('translation')
                           ->whereBetween('created_at', [
                               Carbon::parse($start_date)->startOfDay(),
                               Carbon::parse($end_date)->endOfDay()
                           ])
                           ->get()->map->asInfoForReview();
    }

    public function destroy(Comment $comment)
    {
        $comment->safeDelete();
    }
}
