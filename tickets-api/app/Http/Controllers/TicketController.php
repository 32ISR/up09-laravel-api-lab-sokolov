<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // with('comments') — жадная загрузка.
        // Без неё при обращении к $ticket->comments в цикле Eloquent
        // делал бы отдельный SQL-запрос на каждый тикет.
        // С ним — один запрос: SELECT * FROM comments WHERE ticket_id IN (1,2,3,...)
        $tickets = $request->user()->tickets()->with('comments')->latest()->get();

        return response()->json($tickets);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
        ]);

        $ticket = $request->user()->tickets()->create($data);

        return response()->json($ticket, 201);
    }

    public function show(Request $request, Ticket $ticket): JsonResponse
    {
        if ($ticket->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Доступ запрещён.'], 403);
        }

        // load('comments.user') — точка означает вложенную загрузку:
        // загрузить comments, и для каждого из них загрузить его user
        $ticket->load('comments.user');

        return response()->json($ticket);
    }

    public function update(Request $request, Ticket $ticket): JsonResponse
    {
        if ($ticket->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Доступ запрещён.'], 403);
        }

        $data = $request->validate([
            'title'  => 'sometimes|string|max:255',
            'body'   => 'sometimes|string',
            'status' => 'sometimes|in:open,in_progress,closed',
        ]);

        $ticket->update($data);

        return response()->json($ticket);
    }

    public function destroy(Request $request, Ticket $ticket): JsonResponse
    {
        if ($ticket->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Доступ запрещён.'], 403);
        }

        $ticket->delete();

        return response()->json(['message' => 'Тикет удалён.']);
    }
}