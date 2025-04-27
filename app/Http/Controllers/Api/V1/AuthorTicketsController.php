<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiController;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use App\Http\Resources\V1\TicketResource;
use App\Http\Filters\V1\TicketFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;

class AuthorTicketsController extends ApiController
{
    public function index($author_id, TicketFilter $filter)
    {
        return TicketResource::collection(Ticket::where('user_id', $author_id)->filter($filter)->paginate());
    }

    public function store($author_id, StoreTicketRequest $request)
    {
        // try {
        //     $user = User::findOrFail($author_id);

        // } catch (ModelNotFoundException $e) {
        //     return $this->ok('User not found', [
        //         'error' =>
        //             'The provided user id does not exist'
        //     ]);
        // }

        return new TicketResource(Ticket::create($request->mappedAttributes()));
    }

    public function destroy($author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            if ($ticket->user_id === $author_id) {
                $ticket->delete();
                return $this->ok('Ticket deleted');

            }
            return $this->error('You do not have permission to delete this ticket', 403);
        } catch (ModelNotFoundException $e) {
            return $this->error('Ticket cannot be found', 404);
        }
    }

    public function replace(ReplaceTicketRequest $request, $author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            if ($ticket->user_id == $author_id) {
                $ticket->update($request->mappedAttributes());
                return new TicketResource($ticket);
            }
            return $this->error('You do not have permission to update this ticket', 403);
        } catch (ModelNotFoundException $e) {
            return $this->ok('Ticket not found', [
                'error' =>
                    'Ticket cannot be found'
            ]);
        }
    }


    public function update(UpdateTicketRequest $request, $author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            if ($ticket->user_id == $author_id) {


                $ticket->update($request->mappedAttributes());
                return new TicketResource($ticket);
            }
            return $this->error('You do not have permission to update this ticket', 403);
        } catch (ModelNotFoundException $e) {
            return $this->ok('Ticket not found', [
                'error' =>
                    'Ticket cannot be found'
            ]);
        }
    }
}


