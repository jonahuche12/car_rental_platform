@extends('layouts.app')
@section('title', "Central School System - Welcome")

@section('breadcrumb2')
<a href="{{route('home')}}">Home</a>
@endsection
@section('breadcrumb3')
<a href="{{route('/')}}">Welcome</a>
@endsection
@section('page_title', "Central School System")
@section('sidebar')
@include('sidebar')
@endsection

@section('style')
<!-- Additional CSS for styling improvements -->
<style>
    .card {
        transition: transform 0.3s;
    }
    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    }
    .icon {
        color: #007bff;
    }
    .card-title {
        font-size: 1.25rem;
        margin-bottom: 0.75rem;
    }
    .card-text {
        font-size: 1rem;
        color: #fff;
    }
    .container h2 {
        font-weight: bold;
        color: #fff;
    }
</style>
@endsection

@section('content')

<!-- Hero Section with Carousel -->
<div id="heroCarousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="{{ asset('dist/img/cloudtech.png') }}" class="d-block w-100" alt="First slide">
            <div class="carousel-caption d-none d-md-block">
                <!-- <h5>Transforming Education with Cloud Technology</h5> -->
                <!-- <p>Experience seamless data management and enhanced collaboration for your school.</p> -->
            </div>
        </div>
        <div class="carousel-item">
            <img src="{{ asset('dist/img/securecloudstogarge.png') }}" class="d-block w-100" alt="Second slide">
            <div class="carousel-caption d-none d-md-block">
                <!-- <h5>Secure Cloud Storage</h5> -->
                <!-- <p>Keep all your school data safe and accessib/l/e from anywhere, anytime.</p> -->
            </div>
        </div>
        <div class="carousel-item">
            <img src="{{ asset('dist/img/easydatamanagement.png') }}" class="d-block w-100" alt="Third slide">
            <div class="carousel-caption d-none d-md-block">
                <!-- <h5>Easy Data Management</h5> -->
                <!-- <p>Simplify administrative tasks with our intuitive data management tools.</p> -->
            </div>
        </div>
        <div class="carousel-item">
            <img src="{{ asset('dist/img/collaborativetoolsmain.png') }}" class="d-block w-100" alt="Fourth slide">
            <div class="carousel-caption d-none d-md-block">
                <!-- <h5>Collaborative Tools</h5> -->
                <!-- <p>Enhance collaboration among teachers, students, and parents.</p> -->
            </div>
        </div>
        <div class="carousel-item">
            <img src="{{ asset('dist/img/scalablesolutions.png') }}" class="d-block w-100" alt="Fifth slide">
            <div class="carousel-caption d-none d-md-block">
                <!-- <h5>Scalable Solutions</h5> -->
                <!-- <p>Our platform grows with your school, offering scalable solutions for institutions of all sizes.</p> -->
            </div>
        </div>
        <div class="carousel-item">
            <img src="{{ asset('dist/img/realtimeinsights.png') }}" class="d-block w-100" alt="Sixth slide">
            <div class="carousel-caption d-none d-md-block">
                <!-- <h5>Real-time Insights</h5> -->
                <!-- <p>Get instant access to important metrics and reports to make informed decisions.</p> -->
            </div>
        </div>
    </div>
    <a class="carousel-control-prev" href="#heroCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#heroCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

<!-- Features Section -->
<div class="container mt-5">
    <h2 class="text-center mb-5">Why Choose Central School System?</h2>
    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-lg">
                <div class="card-body">
                    <div class="icon mb-3">
                        <i class="fas fa-cloud fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title font-weight-bold">Secure Cloud Storage</h5>
                    <p class="card-text">Keep all your school data safe and accessible from anywhere, anytime.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-lg">
                <div class="card-body">
                    <div class="icon mb-3">
                        <i class="fas fa-folder-open fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title font-weight-bold">Easy Data Management</h5>
                    <p class="card-text">Simplify administrative tasks with our intuitive data management tools.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-lg">
                <div class="card-body">
                    <div class="icon mb-3">
                        <i class="fas fa-users fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title font-weight-bold">Collaborative Tools</h5>
                    <p class="card-text">Enhance collaboration among teachers, students, and parents.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-lg">
                <div class="card-body">
                    <div class="icon mb-3">
                        <i class="fas fa-chart-bar fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title font-weight-bold">Scalable Solutions</h5>
                    <p class="card-text">Our platform grows with your school, offering scalable solutions for institutions of all sizes.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-lg">
                <div class="card-body">
                    <div class="icon mb-3">
                        <i class="fas fa-chart-line fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title font-weight-bold">Real-time Insights</h5>
                    <p class="card-text">Get instant access to important metrics and reports to make informed decisions.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-lg">
                <div class="card-body">
                    <div class="icon mb-3">
                        <i class="fas fa-headset fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title font-weight-bold">24/7 Support</h5>
                    <p class="card-text">We're here to help you every step of the way with round-the-clock support.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-lg">
                <div class="card-body">
                    <div class="icon mb-3">
                        <i class="fas fa-eye fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title font-weight-bold">Progress Monitoring</h5>
                    <p class="card-text">Parents can follow and monitor the progress of their wards and children.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-lg">
                <div class="card-body">
                    <div class="icon mb-3">
                        <i class="fas fa-book-reader fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title font-weight-bold">Daily Study Connects</h5>
                    <p class="card-text">We offer free daily study connects for students to aid them in their studies.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-lg">
                <div class="card-body">
                    <div class="icon mb-3">
                        <i class="fas fa-chart-pie fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title font-weight-bold">Comprehensive Analytics</h5>
                    <p class="card-text">We provide comprehensive analytics for students, teachers, and schools.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Benefits Section -->
<div class="container mt-5">
    <h2 class="text-center mb-5">Empowering Schools to Achieve More</h2>
    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-lg">
                <img src="{{ asset('dist/img/improvedefficiency.png') }}" class="card-img-top" alt="Improved Efficiency">
                <div class="card-body">
                    <h5 class="card-title font-weight-bold">Improved Efficiency</h5>
                    <p class="card-text">Automate routine tasks and free up time for what matters most—teaching.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-lg">
                <img src="{{ asset('dist/img/enhancedleaning.png') }}" class="card-img-top" alt="Enhanced Learning Experience">
                <div class="card-body">
                    <h5 class="card-title font-weight-bold">Enhanced Learning Experience</h5>
                    <p class="card-text">Leverage technology to provide a better learning experience for students.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-lg">
                <img src="{{ asset('dist/img/seemlesscommunication.png') }}" class="card-img-top" alt="Seamless Communication">
                <div class="card-body">
                    <h5 class="card-title font-weight-bold">Seamless Communication</h5>
                    <p class="card-text">Keep everyone on the same page with streamlined communication tools.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row text-center">
        <div class="col-md-4 offset-md-2 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-lg">
                <img src="{{ asset('dist/img/datadrivendecisions.png') }}" class="card-img-top" alt="Data-Driven Decisions">
                <div class="card-body">
                    <h5 class="card-title font-weight-bold">Data-Driven Decisions</h5>
                    <p class="card-text">Make data-driven decisions with our comprehensive reporting and analytics features.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-lg">
                <img src="{{ asset('dist/img/247support.png') }}" class="card-img-top" alt="24/7 Support">
                <div class="card-body">
                    <h5 class="card-title font-weight-bold">24/7 Support</h5>
                    <p class="card-text">We're here to help you every step of the way with round-the-clock support.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scholarships Section -->
<div class="container mt-5">
    <h2 class="text-center mb-5">Scholarships/Awards</h2>
    <div class="row d-flex justify-content-center text-center">
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-lg">
                <img src="{{ asset('dist/img/studentscholarship.png') }}" class="card-img-top" alt="Student Scholarships">
                <div class="card-body">
                    <h5 class="card-title font-weight-bold">Student Scholarships</h5>
                    <p class="card-text">We offer regular scholarships to outstanding students to support their education.</p>
                </div>
            </div>
        </div>
        <!-- Uncomment if needed
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-lg">
                <img src="https://via.placeholder.com/300" class="card-img-top" alt="School Grants">
                <div class="card-body">
                    <h5 class="card-title font-weight-bold">School Grants</h5>
                    <p class="card-text">Schools can apply for grants to improve infrastructure and learning resources.</p>
                </div>
            </div>
        </div> -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-lg">
                <img src="{{ asset('dist/img/teacherawards.png') }}" class="card-img-top" alt="Teacher Awards">
                <div class="card-body">
                    <h5 class="card-title font-weight-bold">Teacher Awards</h5>
                    <p class="card-text">We recognize and reward teachers who contribute significantly to student success.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Earn from Lessons Section -->
<div class="container mt-5">
    <h2 class="text-center mb-5">Earn from Lessons</h2>
    <div class="row text-center">
        <div class="col-md-8 offset-md-2 mb-4">
            <div class="card h-100 shadow-sm border-0 rounded-lg">
                <img src="{{ asset('dist/img/earnfromlessons.png') }}" class="card-img-top" alt="Earn from Lessons">
                <div class="card-body">
                    <h5 class="card-title font-weight-bold">Monetize Your Expertise</h5>
                    <p class="card-text">Teachers can earn from the lessons they post on our platform. Share your knowledge and get rewarded.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Testimonials Section -->
<div class="container mt-5">
    <h2 class="text-center">Hear from Our Users</h2>
    <div class="row mt-4">
        <div class="col-md-4">
            <blockquote class="blockquote">
                <p class="mb-0">"Central School System has revolutionized the way we manage our school data. It's user-friendly and incredibly reliable."</p>
                <footer class="blockquote-footer">Director of Shammah Academy</footer>
            </blockquote>
        </div>
        <div class="col-md-4">
            <blockquote class="blockquote">
                <p class="mb-0">"The collaborative tools have made communication with parents and students so much easier. Our productivity has significantly increased."</p>
                <footer class="blockquote-footer">Teacher at Gaint Step Academy</footer>
            </blockquote>
        </div>
        <div class="col-md-4">
            <blockquote class="blockquote">
                <p class="mb-0">"Having all our data securely stored in the cloud has given us peace of mind. We can access everything we need, anytime, anywhere."</p>
                <footer class="blockquote-footer">Admin Manager at Australian Academy</footer>
            </blockquote>
        </div>
    </div>
</div>

<!-- Call to Action Section -->
<div class="container mt-5 text-center">
    <h2>Ready to Simplify Education?</h2>
    <p>Join hundreds of schools that trust Central School System to manage their data.</p>
    <a href="{{route('register')}}" class="btn bg-info btn-lg">Sign Up for Free</a>
    <a href="{{route('login')}}" class="btn bg-primary btn-lg">Login</a>
    <p class="mt-3">Contact us for more information.</p>
</div>


@endsection
