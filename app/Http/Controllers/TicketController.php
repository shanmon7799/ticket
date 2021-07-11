<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() :Response
    {
        return response(Ticket::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) :Response
    {
        $this->authorize('create', Ticket::class);

        $request->validate(
            [
                'description' => 'required',
                'summary'     => 'required',
            ]
        );
        $ticket = Ticket::create(
            [
                'description' => $request->description,
                'summary'     => $request->summary,
            ]
        );

        return response($ticket);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket) : Response
    {
        return response($ticket);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {
        if ($request->user()->role === 'RD') {
            $this->authorize('resolve', $ticket);
            $request->validate(['is_resolve' => 'required']);
            $ticket->update(['is_resolved' => 1]);
        } else {
            $this->authorize('update', $ticket);
            $request->validate(
                [
                    'description' => 'required',
                    'summary'     => 'required',
                ]
            );
            $ticket->update(
                [
                    'description' => $request->description,
                    'summary'     => $request->summary,
                ]
            );
        }

        return response($ticket);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        $this->authorize('delete', $ticket);

        $ticket->delete();

        return response()->noContent();
    }
}
