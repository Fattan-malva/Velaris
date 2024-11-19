@extends('layouts.app')

@section('content')
<br>
<br>
<br>
<div class="container">
    <div>
        <div class="container">
            <div class="header-container">
                <div class="back-wrapper">
                    <i class='bx bxs-chevron-left back-icon' id="back-icon"></i>
                    <div class="back-text">
                        <span class="title">Back</span>
                        <span class="small-text">to previous page</span>
                    </div>
                </div>
                <h3 class="chats-title">
                    Chats&nbsp;&nbsp;
                    <span class="icon-wrapper">
                        <i class="fa-solid fa-sm fa-comment previous-icon"></i>
                    </span>
                </h3>
            </div>
            <br>
        </div>
    </div>
    <div class="chat">
        <div class="row">
            <!-- Left side: Ticket Info and To-Do List -->
            <div class="col-md-4 todo-column mb-2">
                <div class="card ticket-info-card">
                    <div class="card-body">
                        <h4 class="ticket-subject fw-bold">Subject: {{ $ticket->subject }}</h4>
                    </div>
                </div>
            </div>

            <!-- Right side: Chat Messages -->
            <div class="col-md-8 chat-column">
                @livewire('show-chat-mobile', ['ticketId' => $ticket->id])
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .text-decoration-line-through {
        text-decoration: line-through;
    }

    .card-body {
        padding: 10px;
    }

    .input-group {
        margin-bottom: 15px;
    }

    .card {
        box-shadow: rgba(0, 0, 0, 0.15) 2.4px 2.4px 3.2px;
    }

    .custom-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 10px 10px 0 0;
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }


    .header-left {
        display: flex;
        align-items: center;
    }

    .header-right {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-end;
        gap: 5px;
    }

    .badge-danger {
        background-color: #DC3545;
        /* Atur warna latar belakang merah */
        color: white;
        /* Warna teks putih */
        padding: 5px 10px;
        /* Padding untuk ukuran yang lebih baik */
        border-radius: 20px;
        /* Membuat sudut lebih bulat */
        font-size: 14px;
        /* Ukuran font untuk badge */
        cursor: pointer;
        /* Memberi efek pointer saat hover */
    }



    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .chat-header {
        font-size: 1.5rem;
        margin: 0;
    }

    .ticket-subject {
        font-size: 1.3rem;
        margin: 0;
        margin-bottom: 5px;
    }

    .chat-status {
        font-size: 1rem;
        color: #6c757d;
    }


    .header-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px;
        margin-top: 30px;
    }

    .back-icon {
        cursor: pointer;
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.1) -10%, #FCA918);
        height: 36px;
        width: 36px;
        border-radius: 4px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.25);
        margin-right: auto;
        transition: background 0.3s ease;
        /* Transition untuk efek hover */
    }

    #back-icon {
        text-align: center;
        align-items: center;
        display: flex;
    }

    .back-icon:hover {
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.1) -13%, #FBCA07);
        /* Warna gradien saat hover dengan putih sedikit di kiri */
    }

    .back-wrapper {
        display: flex;
        align-items: center;
        /* Center vertically */
        margin-right: auto;
        /* Push the dashboard title to the right */
    }

    .back-text {
        display: flex;
        flex-direction: column;
        /* Stack text vertically */
        margin-left: 10px;
        /* Space between icon and text */
    }

    .back-text .title {
        font-weight: 600;
        font-size: 17px;
    }

    .back-text .small-text {
        font-size: 0.8rem;
        /* Smaller font size for the second line */
        color: #aaa;
        /* Optional: a lighter color for the smaller text */
        margin-top: -3px;
    }

    .chats-title {
        font-weight: bold;
        font-size: 1.125rem;
    }

    .icon-wrapper {
        background: linear-gradient(90deg, rgba(255, 255, 255, 0.1) -10%, #FCA918);
        height: 36px;
        width: 36px;
        border-radius: 4px;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.25);
    }

    .previous-icon {
        font-size: 16px;
    }

    .ticket-container {
        position: relative;
    }


    /* CHAT  */

    .chat-container {
        max-width: 600px;
        margin: auto;
    }

    .chat-header {
        text-align: center;
        font-size: 1.5rem;
    }

    .chat-status {
        text-align: center;
        color: gray;
    }

    .date-badge {
        position: sticky;
        top: 0;
        text-align: center;
        display: flex;
        justify-content: center;
        font-size: 0.9rem;
        color: rgba(0, 0, 0, 0.6);
        background-color: rgba(255, 255, 255, 0.5);
        padding: 5px 10px;
        border-radius: 12px;
        margin: 10px auto;
        width: fit-content;
        z-index: 1;
    }


    .chat-messages {
        background: #f0f0f0;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 15px;
        max-height: 350px;
        height: 350px;
        overflow-y: scroll;
        /* Enables scrolling */
        scrollbar-width: none;
        /* For Firefox */
    }

    .ticket-closed-notification {
        background-color: rgba(255, 255, 255, 0.5);
        border-radius: 10px;
        text-align: center;
        color: black;
        padding: 2px 10px;
        max-width: 250px;
        padding-top: 10px;
        margin: 0 auto;
        font-size: 14px;
    }


    .chat-messages::-webkit-scrollbar {
        display: none;
    }

    .chat-message-list {
        list-style: none;
        padding: 0;
    }

    .chat-message-item {
        margin-bottom: 15px;
        display: flex;
        flex-wrap: wrap;
        /* Ensure the bubble wraps when the text is too long */
        align-items: flex-end;
        /* Align text and time to the bottom */
    }

    .chat-message-item.self {
        justify-content: flex-end;
    }

    .chat-message-item.other {
        justify-content: flex-start;
    }

    .message-bubble {
        max-width: 75%;
        padding: 10px 15px;
        border-radius: 15px;
        box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2);
        color: #333;
        display: flex;
        flex-direction: column;
        /* Stack the text and time vertically */
    }

    .message-bubble.self {
        background-color: #FACB77;
    }

    .message-bubble.other {
        background-color: #fff;
    }

    .message-sender {
        font-size: 0.9rem;
    }

    .message-text {
        font-size: 1rem;
        line-height: 1.4;
        word-wrap: break-word;
        /* Allow the text to break and wrap if too long */
    }

    .message-time {
        font-size: 0.8rem;
        color: gray;
        margin-top: 5px;
        /* Add space between the message text and time */
        align-self: flex-end;
        /* Align time to the right of the message */
    }

    .chat-input-container {
        margin-top: 10px;

    }

    .chat-textarea {
        width: 100%;
        border-radius: 10px;
        padding: 10px;
        resize: none;
        display: flex;
        border: 1px solid #ddd;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .chat-submit-button {
        background-color: #FBBB4C;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 1rem;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .ticket-info-card,
    .todo-list-card {
        margin-left: 110px;
        margin-right: -100px;
    }

    .todo-column {
        width: 30%;
    }

    .chat-column {
        width: 70%;
    }

    .todo-list-card {
        margin-top: 10px;
    }

    @media (max-width: 768px) {

        .chat {
            flex-direction: column;
            /* Stack chat and todo list vertically */
        }

        .todo-list-card {
            margin-bottom: 10px;
            margin-top: 10px;
        }

        .todo-column,
        .chat-column {
            width: 100%;
            margin-left: 0;
            margin-right: 0;
        }

        .ticket-info-card,
        .todo-list-card {
            margin-left: 0;
            margin-right: 0;
        }

        .header-container,
        .chats-title {
            display: none;
        }   

    }
</style>