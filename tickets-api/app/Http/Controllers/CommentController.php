<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Ticket $ticket): JsonResponse
    {
        $data = $request->validate([
            'body' => 'required|string',
        ]);

        $comment = $ticket->comments()->create([
            'user_id' => $request->user()->id,
            'body'    => $data['body'],
        ]);

        // Подгружаем автора, чтобы вернуть его в ответе
        $comment->load('user');

        return response()->json($comment, 201);
    }

    public function destroy(Request $request, Ticket $ticket, Comment $comment): JsonResponse
    {
        if ($comment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Доступ запрещён.'], 403);
        }

        // Без этой проверки можно передать в URL comment_id из другого тикета
        // и удалить комментарий, который к этому тикету не относится
        if ($comment->ticket_id !== $ticket->id) {
            return response()->json(['message' => 'Комментарий не найден.'], 404);
        }

        $comment->delete();

        return response()->json(['message' => 'Комментарий удалён.']);
    }
}