<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiController;
use Illuminate\Auth\Access\AuthorizationException;
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
use App\Policies\V1\TicketPolicy;

class AuthorTicketsController extends ApiController
{
    protected $policyClass = TicketPolicy::class;

    public function index($author_id, TicketFilter $filter)
    {
        return TicketResource::collection(Ticket::where('user_id', $author_id)->filter($filter)->paginate());
    }

    public function store(StoreTicketRequest $request, $author_id)
    {
        try {
            // policy
            $this->isAble('store', Ticket::class);

            // TODO: create ticket
            return new TicketResource(Ticket::create($request->mappedAttributes(['author' => 'user_id'])));

        } catch (AuthorizationException $ex) {
            return $this->error('You are not authorized to create that resource', 401);
        }
    }

    public function destroy($author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::where('id', $ticket_id)->where('user_id', $author_id)->firstOrFail();
            $this->isAble('delete', $ticket);
            $ticket->delete();
            return $this->ok('Ticket deleted');
        } catch (ModelNotFoundException $e) {
            return $this->error('Ticket cannot be found', 404);
        } catch (AuthorizationException $ex) {
            return $this->error('You are not authorized to delete that resource', 401);
        }
    }

    public function replace(ReplaceTicketRequest $request, $author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::where('id', $ticket_id)
                ->where('user_id', $author_id)->firstOrFail();
            $this->isAble('replace', $ticket);
            $ticket->update($request->mappedAttributes());
            return new TicketResource($ticket);

        } catch (ModelNotFoundException $e) {
            return $this->ok('Ticket not found', [
                'error' =>
                    'Ticket cannot be found'
            ]);
        } catch (AuthorizationException $ex) {
            return $this->error('You are not authorized to update that resource', 401);
        }
    }


    public function update(UpdateTicketRequest $request, $author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::where('id', $ticket_id)->where('user_id', $author_id)->firstOrFail();
            $this->isAble('update', $ticket);
            $ticket->update($request->mappedAttributes());
            return new TicketResource($ticket);

        } catch (ModelNotFoundException $e) {
            return $this->ok('Ticket not found', [
                'error' =>
                    'Ticket cannot be found'
            ]);
        } catch (AuthorizationException $ex) {
            return $this->error('You are not authorized to update that resource', 401);
        }
    }
}


