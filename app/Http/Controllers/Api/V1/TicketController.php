<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiController;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Models\Ticket;
use App\Models\User;
use App\Http\Resources\V1\TicketResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(TicketFilter $filters)
    {


        return TicketResource::collection(Ticket::filter($filters)->paginate());




    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        try {
            $user = User::findOrFail($request->input('data.relationships.author.data.id'));

        } catch (ModelNotFoundException $e) {
            return $this->ok('User not found', [
                'error' =>
                    'The provided user id does not exist'
            ]);
        }

        // $ticket = $user->tickets()->create([
        //     'title' => $request->input('data.attributes.title'),
        //     'description' => $request->input('data.attributes.description'),
        //     'status' => $request->input('data.attributes.status'),
        //     'user_id' => $request->input('data.relationships.author.data.id'),
        // ]);

        return new TicketResource($request->mappedAttributes());
    }

    /**
     * Display the specified resource.
     */
    public function show($ticket_id, TicketFilter $filters)
    {
        try {
            $ticket = Ticket::filter($filters)->findOrFail($ticket_id);
            return new TicketResource($ticket);
        } catch (ModelNotFoundException $e) {
            return $this->error('Ticket cannot be found', 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, $ticket_id)
    {
        //PATCH
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            // $model = [
            //     'title' => $request->input('data.attributes.title'),
            //     'description' => $request->input('data.attributes.description'),
            //     'status' => $request->input('data.attributes.status'),
            //     'user_id' => $request->input('data.relationships.author.data.id'),
            // ];

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);

        } catch (ModelNotFoundException $e) {
            return $this->error('Ticket not found', 404);
        }

    }

    public function replace(ReplaceTicketRequest $request, $ticket_id)
    {
        //PUT
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            // $model = [
            //     'title' => $request->input('data.attributes.title'),
            //     'description' => $request->input('data.attributes.description'),
            //     'status' => $request->input('data.attributes.status'),
            //     'user_id' => $request->input('data.relationships.author.data.id'),
            // ];

            $ticket->update($request->mappedAttributes());
            return new TicketResource($ticket);

        } catch (ModelNotFoundException $e) {
            return $this->ok('Ticket not found', [
                'error' =>
                    'Ticket cannot be found'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            $ticket->delete();
            return $this->ok('Ticket deleted');
        } catch (ModelNotFoundException $e) {
            return $this->error('Ticket cannot be found', 404);

        }
    }
}
