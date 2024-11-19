<?php

namespace App\Http\Controllers\Tickets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    // Show all tickets for the authenticated user
    public function index()
    {
        $tickets = Ticket::where('user_id', session('user_id'))->get();
        return view('tickets.index', compact('tickets'));
    }

    // Show all tickets for the admin
    public function adminIndex()
    {
        $tickets = Ticket::with('customer')->get();
        return view('tickets.admin', compact('tickets'));
    }

    // Display a single ticket with messages
    public function show($id)
    {
        $ticket = Ticket::with('customer','messages.sender')->findOrFail($id);
        return view('tickets.show', compact('ticket'));
    }
    // Show form to create a new ticket
    public function showMobile($id)
    {
        $ticket = Ticket::with('customer','messages.sender')->findOrFail($id);
        return view('tickets.show-mobile', compact('ticket'));
    }
    public function create()
    {
        return view('tickets.create');
    }

    // Store new ticket in the database
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
        ]);

        Ticket::create([
            'user_id' => session('user_id'),
            'subject' => $request->subject,
            'status' => 'Open',
        ]);

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully.');
    }

    // Store a new message in an existing ticket
    public function addMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        Message::create([
            'ticket_id' => $id,
            'sender_id' => session('user_id'),
            'message' => $request->message,
        ]);

        return redirect()->route('tickets.show', $id)->with('success', 'Message sent.');
    }
    
}
