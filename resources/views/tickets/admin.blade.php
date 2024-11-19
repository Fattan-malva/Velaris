@extends('layouts.app')
@section('content')
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
                <h3 class="tickets-title">
                    Tickets&nbsp;&nbsp;
                    <span class="icon-wrapper">
                        <i class="fa-solid fa-sm fa-envelope-open-text previous-icon"></i>
                    </span>
                </h3>
            </div>
            <div class="header-container-mobile">
                <h3 class="tickets-title">
                    <span class="icon-wrapper">
                        <i class="fa-solid fa-sm fa-envelope-open-text previous-icon"></i>
                    </span>
                    &nbsp;&nbsp;Tickets
                </h3>
            </div>
            <br>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            @livewire('admin-ticket-index')
        </div>
    </div>
</div>

@endsection
<style>
    /* Loading spinner in the center of the container */
    .loading-overlay {
        position: absolute;
        top: 100;
        left: 45%;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .card {
        box-shadow: rgba(0, 0, 0, 0.15) 2.4px 2.4px 3.2px;
    }

    /* Header Styles */
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

    .tickets-title {
        font-weight: bold;
        font-size: 1.125rem;
    }

    .ticket-container {
        position: relative;
        transition: transform 0.2s;
    }

    .ticket-container:hover {
        transform: translateY(-4px);
    }


    .progres-badge {
        position: absolute;
        top: 20%;
        right: 15;
        transform: translateY(-50%);
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        color: white;
        font-weight: bold;
        font-size: 0.75rem;
    }



    .progres-badge.open {
        background-color: #28a745;
        /* Green for Open */
    }

    .progres-badge.in-progress {
        background-color: #007bff;
        /* Blue for In Progress */
    }

    .progres-badge.closed {
        background-color: #6c757d;
        /* Gray for Closed */
    }
</style>