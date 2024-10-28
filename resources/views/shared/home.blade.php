@extends('layouts.app')

@section('content')
<section id="hero">
    <div id="heroCarousel" data-bs-interval="5000" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <ol class="carousel-indicators" id="hero-carousel-indicators"></ol>
        <div class="carousel-inner" role="listbox">

            <!-- Slide 1 -->
            <div class="carousel-item active" style="background-image: url(assets/img/slide/BackgroundLogin.jpeg)">
                <div class="carousel-container">
                    <div class="container">
                        <h2 class="animate__animated animate__fadeInDown"><span>Global Service Indonesia</span></h2>
                        <p class="animate__animated animate__fadeInUp">Welcome to Global Service Indonesia, PT Global
                            Service Indonesia was established on December 24, 2014 under Karya Bakti United Tractors
                            Foundation (Member of ASTRA). We have two lines of business which include Digital Solution
                            and Manpower Solution.</p>
                        <a href="#about" class="btn-get-started animate__animated animate__fadeInUp scrollto">Read
                            More</a>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-item" style="background-image: url(assets/img/slide/slide-2.jpg)">
                <div class="carousel-container">
                    <div class="container">
                        <h2 class="animate__animated animate__fadeInDown"><span>Global Service Indonesia</span></h2>
                        <p class="animate__animated animate__fadeInUp">Welcome to Global Service Indonesia, PT Global
                            Service Indonesia was established on December 24, 2014 under Karya Bakti United Tractors
                            Foundation (Member of ASTRA). We have two lines of business which include Digital Solution
                            and Manpower Solution.</p>
                        <a href="#about" class="btn-get-started animate__animated animate__fadeInUp scrollto">Read
                            More</a>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="carousel-item" style="background-image: url(assets/img/slide/slide-3.jpg)">
                <div class="carousel-container">
                    <div class="container">
                        <h2 class="animate__animated animate__fadeInDown"><span>Global Service Indonesia</span></h2>
                        <p class="animate__animated animate__fadeInUp">Welcome to Global Service Indonesia, PT Global
                            Service Indonesia was established on December 24, 2014 under Karya Bakti United Tractors
                            Foundation (Member of ASTRA). We have two lines of business which include Digital Solution
                            and Manpower Solution.</p>
                        <a href="#about" class="btn-get-started animate__animated animate__fadeInUp scrollto">Read
                            More</a>
                    </div>
                </div>
            </div>
        </div>

        <a class="carousel-control-prev" href="#heroCarousel" role="button" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
        </a>

        <a class="carousel-control-next" href="#heroCarousel" role="button" data-bs-slide="next">
            <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
        </a>
    </div>
</section>

<main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
        <div class="container">
            <div class="row content">
                <div class="col-lg-6">
                    <h2>Global Service Indonesia</h2>
                    <h3>PT Global Service Indonesia known as GSI was established on December 24, 2014 in Jakarta which
                        have two lines of business, Digital Solution Man Power Solution</h3>
                </div>
                <div class="col-lg-6 pt-4 pt-lg-0">
                    <p>
                        To become one of the leading digital solution and professional talent provider in Indonesia with
                        sustainable growth and consistently delivering added value to our stakeholders.
                    </p>
                    <ul>
                        <li><i class="ri-check-double-line"></i><b>Digital Solution</b>
                            <br>
                            Digital Platform (ERP Odoo, etc), Software Development,
                            Hardware, Server, Network, Software License, Managed Service, Other Services
                            We offer customizable end-to-end digital solutions that utilize appropriate digital
                            technologies to meet your specific requirements.
                        </li>

                        <li><i class="ri-check-double-line"></i><b>Manpower Solution</b>
                            <br>
                            Information Technology Position, ESR Position, Functional Expert Position
                            We offer customized recruitment services designed to match your specific needs, and our
                            flexible approach allows us to tailor our services accordingly.
                        </li>
                    </ul>
                    <p class="fst-italic">
                        Our expertise helps us implement solutions for a wide range of our clients from different
                        industries, such as heavy equipment, mining, construction, energy, manufacturing, financial,
                        property management, infrastructure and IT Industries.

                        Most of our clients are under United Tractors & Astra Group.
                    </p>
                </div>
            </div>
        </div>
    </section><!-- End About Section -->

    <!-- ======= Clients Section ======= -->
    <section id="clients" class="clients section-bg">
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-8 col-12 d-flex align-items-center justify-content-center">
                    <img src="assets/img/clients/allin.png" class="img-fluid" alt="">
                </div>
            </div>
        </div>
        <br>
        <br>
    </section><!-- End Clients Section -->
</main><!-- End #main -->
@endsection

<style>
    #clients .img-fluid {
        margin-top: 80px;
        max-width: 100%;
        height: auto;
    }
</style>