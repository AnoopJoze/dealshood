<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="/frontend/img/apple-icon.png">
    <link rel="icon" type="image/png" href="/frontend/img/favicon.png">
    <title>
        {{ $post->title }}
    </title>

    <meta name="description" content="{{ $post->meta_description ?: Str::limit(strip_tags($post->description), 160) }}">

    <meta name="keywords" content="{{ $post->keywords }}">

    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="article">

    <meta property="og:title" content="{{ $post->meta_title ?: $post->title }}">

    <meta property="og:description"
        content="{{ $post->meta_description ?: Str::limit(strip_tags($post->description), 160) }}">

    <meta property="og:url" content="{{ url()->current() }}">

    <meta property="og:image" content="{{ $post->getFirstMediaUrl('posts') }}">
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- CSS Files -->
    <link id="pagestyle" href="/frontend/css/soft-design-system.css?v=1.1.0" rel="stylesheet" />
    <!-- Nepcha Analytics (nepcha.com) -->
    <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
    <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .nav-link {
            color: #000 !important;
        }
    </style>
</head>

<body class="about-us">
    <div id="reading-progress"
        style="position:fixed;top:0;left:0;height:3px;width:0%;
            background:#0d6efd;z-index:9999;">
    </div>
    <!-- Navbar Transparent -->
    <nav class="navbar navbar-expand-lg fixed-top z-index-3 w-100 bg-white shadow-sm p-0">
        <div class="container">
            <a class="navbar-brand text-dark" href="{{ route('home') }}">
                <img src="/frontend/img/dealshood.png" class="img-fluid" alt="Logo" width="80">
            </a>

            <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse"
                data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false"
                aria-label="Toggle navigation">

                <span class="navbar-toggler-icon mt-2">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                </span>
            </button>

            <div class="collapse navbar-collapse justify-content-end pt-3 pb-2 py-lg-0" id="navigation">

                <ul class="navbar-nav navbar-nav-hover ms-auto">

                    <li class="nav-item dropdown dropdown-hover mx-2">
                        <a href="https://www.instagram.com/dealshood?igsh=NHJpdDhkYmJ2dTlj" target="_blank"
                            class="btn btn-danger m-0 p-1 mt-1">
                            <i class="bi bi-instagram" style="font-size:14px;"></i> Follow Us
                        </a>
                    </li>

                    <li class="nav-item dropdown dropdown-hover mx-2">
                        <a href="https://wa.me/918086087050?text=Hello%20I%20am%20interested%20in%20your%20listing"
                            target="_blank" class="btn btn-sm btn-success m-0 p-1 mt-1">
                            <i class="bi bi-whatsapp" style="font-size:14px;"></i> Contact Us
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->
    <!-- -------- START HEADER 7 w/ text and video ------- -->
    <header class="position-relative overflow-hidden bg-light">

    <!-- Background Image (Right Side) -->
    <div class="position-absolute top-0 end-0 w-50 h-100 d-none d-md-block"
         style="">
    </div>

    <!-- Overlay -->
    <div class="position-absolute top-0 start-0 w-100 h-100"
         style="background: linear-gradient(90deg, #ffffff 40%, rgba(255,255,255,0.85) 60%, transparent);">
    </div>

    <div class="container position-relative py-6" style="margin-top:90px;">

        <div class="row align-items-center">

            <div class="col-lg-12">

                @php
                    $images = $post->getMedia('posts');
                    $video = $post->getFirstMediaUrl('videos');
                @endphp

                <!-- HERO CARD -->
                <div class="p-4 p-md-5 rounded-4 shadow-lg bg-white"
                     style="border:1px solid rgba(0,0,0,0.05);">

                    <!-- CATEGORY -->
                    <span class="badge bg-primary px-3 py-2 mb-3">
                        {{ $post->category?->name ?? 'General' }}
                    </span>

                    <!-- TITLE -->
                    <h1 class="fw-bold display-6 mb-3">
                        {{ $post->title }}
                    </h1>

                    <!-- META -->
                    <div class="text-muted small mb-3">
                        📍 {{ $post->locality?->name }} |
                        🕒 {{ $post->created_at->diffForHumans() }} |
                        👁 {{ number_format($post->views) }} views
                    </div>

                    <!-- ================= MEDIA ================= -->
                    <div class="mb-4">

                        {{-- VIDEO --}}
                        @if($video)

                            <video controls class="w-100 rounded-4 shadow-sm"
                                   style="max-height:420px; object-fit:cover;">
                                <source src="{{ $video }}">
                            </video>

                        {{-- MULTIPLE IMAGES CAROUSEL --}}
                        @elseif($images->count() > 1)

                            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">

                                <div class="carousel-inner rounded-4 overflow-hidden">

                                    @foreach($images as $key => $media)

                                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">

                                            <img src="{{ $media->getUrl() }}"
                                                 class="d-block w-100"
                                                 style="height:420px; object-fit:cover;"
                                                 alt="post image">

                                        </div>

                                    @endforeach

                                </div>

                                <!-- Controls -->
                                <button class="carousel-control-prev" type="button"
                                        data-bs-target="#heroCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>

                                <button class="carousel-control-next" type="button"
                                        data-bs-target="#heroCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>

                            </div>

                        {{-- SINGLE IMAGE --}}
                        @elseif($images->count() == 1)

                            <img src="{{ $images->first()->getUrl() }}"
                                 class="w-100 rounded-4 shadow-sm"
                                 style="max-height:420px; object-fit:cover;">

                        {{-- FALLBACK --}}
                        @else

                            <img src="{{ asset('frontend/img/default.jpg') }}"
                                 class="w-100 rounded-4 shadow-sm"
                                 style="max-height:420px; object-fit:cover;">

                        @endif

                    </div>

                    <!-- DESCRIPTION PREVIEW -->
                    <p class="lead text-muted mb-4">
                        {{ \Illuminate\Support\Str::limit(strip_tags($post->description), 80) }}
                    </p>

                    <!-- CTA BUTTONS -->
                    <div class="d-flex flex-wrap gap-2">

                        <a href="#content" class="btn btn-dark px-4">
                            Read More
                        </a>

                        <button class="btn btn-outline-danger likeBtn"
                                data-id="{{ $post->id }}">
                            ❤️ Like ({{ number_format($post->likesData->count()) }})
                        </button>

                        <button class="btn btn-outline-dark shareBtn"
                                data-id="{{ $post->id }}"
                                data-url="{{ $post->url }}">
                            🔄 Share
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>
</header>

    <!-- CONTENT SECTION -->
    <section id="content" class="py-6 bg-white">

        <div class="container">

            <div class="row justify-content-center">

                <div class="col-lg-9">

                    <!-- CONTENT CARD -->
                    <div class="p-5 rounded-4 shadow-sm bg-white border">

                        <!-- SECTION TITLE -->
                        <h2 class="fw-bold mb-4">
                            Details
                        </h2>

                        <!-- CONTENT -->
                        <div class="post-content text-dark fs-6 lh-lg">

                            {!! $post->description !!}


                            <h6 class="fw-bold mb-3">Contact Information</h6>

                            @php
                                $user = $post->user;
                            @endphp

                            <!-- COMPANY -->
                            <div class="mb-2">
                                <strong>🏢 Company:</strong>
                                {{ $user?->company_name ?? 'N/A' }}
                            </div>

                            <!-- OWNER NAME -->
                            <div class="mb-2">
                                <strong>👤 Owner:</strong>
                                {{ $user?->name ?? 'Admin' }}
                            </div>

                            <!-- ADDRESS -->
                            <div class="mb-2">
                                <strong>📍 Address:</strong>
                                {{ $user?->address ?? 'Not available' }}
                            </div>

                            <!-- PHONE -->
                            <div class="mb-3">
                                <strong>📞 Phone:</strong>
                                <a href="tel:{{ $user?->phone }}" class="text-decoration-none">
                                    {{ $user?->phone ?? 'N/A' }}
                                </a>
                            </div>

                            <!-- ACTION BUTTONS -->
                            <div class="d-flex gap-2 flex-wrap">

                                @if ($user?->phone)
                                    <a href="tel:{{ $user->phone }}" class="btn btn-success btn-sm">
                                        📞 Call Now
                                    </a>

                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}"
                                        target="_blank" class="btn btn-success btn-sm">
                                        💬 WhatsApp
                                    </a>
                                @endif

                            </div>
                        </div>

                    </div>

                    <!-- DIVIDER -->
                    <hr class="my-5">

                    <!-- SOCIAL SHARE -->
                    <div class="text-center">

                        <h5 class="mb-3">Share this post</h5>

                        <div class="d-flex justify-content-center gap-3">

                            <a href="#" class="btn btn-outline-primary btn-sm">
                                Facebook
                            </a>

                            <a href="#" class="btn btn-outline-dark btn-sm">
                                Twitter
                            </a>

                            <a href="#" class="btn btn-outline-success btn-sm">
                                WhatsApp
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </section>

    <!-- -------- END HEADER 7 w/ text and video ------- -->
    <footer class="footer pt-5 mt-5">
        <hr class="horizontal dark mb-5">
        <div class="container">
            <div class=" row">
                <div class="col-md-3 mb-4 ms-auto">
                    <div>
                        <h6 class="text-gradient text-primary font-weight-bolder">DealsHood System</h6>
                    </div>
                    <div>
                        <h6 class="mt-3 mb-2 opacity-8">Social</h6>
                        <ul class="d-flex flex-row ms-n3 nav">
                            <li class="nav-item">
                                <a class="nav-link pe-1" href="https://www.facebook.com/CreativeTim/"
                                    target="_blank">
                                    <i class="fab fa-facebook text-lg opacity-8"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link pe-1" href="https://twitter.com/creativetim" target="_blank">
                                    <i class="fab fa-twitter text-lg opacity-8"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link pe-1" href="https://dribbble.com/creativetim" target="_blank">
                                    <i class="fab fa-dribbble text-lg opacity-8"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link pe-1" href="https://github.com/creativetimofficial"
                                    target="_blank">
                                    <i class="fab fa-github text-lg opacity-8"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link pe-1"
                                    href="https://www.youtube.com/channel/UCVyTG4sCw-rOvB9oHkzZD1w" target="_blank">
                                    <i class="fab fa-youtube text-lg opacity-8"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 col-6 mb-4">
                    <div>
                        <h6 class="text-gradient text-primary text-sm">Company</h6>
                        <ul class="flex-column ms-n3 nav">
                            <li class="nav-item">
                                <a class="nav-link" href="https://www.creative-tim.com/presentation" target="_blank">
                                    About Us
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="https://www.creative-tim.com/templates/free"
                                    target="_blank">
                                    Freebies
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="https://www.creative-tim.com/templates/premium"
                                    target="_blank">
                                    Premium Tools
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="https://www.creative-tim.com/blog" target="_blank">
                                    Blog
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 col-6 mb-4">
                    <div>
                        <h6 class="text-gradient text-primary text-sm">Resources</h6>
                        <ul class="flex-column ms-n3 nav">
                            <li class="nav-item">
                                <a class="nav-link" href="https://iradesign.io/" target="_blank">
                                    Illustrations
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="https://www.creative-tim.com/bits" target="_blank">
                                    Bits & Snippets
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="https://www.creative-tim.com/affiliates/new"
                                    target="_blank">
                                    Affiliate Program
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 col-6 mb-4">
                    <div>
                        <h6 class="text-gradient text-primary text-sm">Help & Support</h6>
                        <ul class="flex-column ms-n3 nav">
                            <li class="nav-item">
                                <a class="nav-link" href="https://www.creative-tim.com/contact-us" target="_blank">
                                    Contact Us
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="https://www.creative-tim.com/knowledge-center"
                                    target="_blank">
                                    Knowledge Center
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="https://services.creative-tim.com/?ref=ct-soft-ui-footer"
                                    target="_blank">
                                    Custom Development
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="https://www.creative-tim.com/sponsorships" target="_blank">
                                    Sponsorships
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-2 col-sm-6 col-6 mb-4 me-auto">
                    <div>
                        <h6 class="text-gradient text-primary text-sm">Legal</h6>
                        <ul class="flex-column ms-n3 nav">
                            <li class="nav-item">
                                <a class="nav-link" href="https://www.creative-tim.com/terms" target="_blank">
                                    Terms &amp; Conditions
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="https://www.creative-tim.com/privacy" target="_blank">
                                    Privacy Policy
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="https://www.creative-tim.com/license" target="_blank">
                                    Licenses (EULA)
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-12">
                    <div class="text-center">
                        <p class="my-4 text-sm">
                            All rights reserved. Copyright ©
                            <script>
                                document.write(new Date().getFullYear())
                            </script> DealsHood Design System by <a href="https://www.creative-tim.com"
                                target="_blank">DealsHood</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--   Core JS Files   -->
    <script src="/frontend/js/core/popper.min.js" type="text/javascript"></script>
    <script src="/frontend/js/core/bootstrap.min.js" type="text/javascript"></script>
    <script src="/frontend/js/plugins/perfect-scrollbar.min.js"></script>
    <!--  Plugin for TypedJS, full documentation here: https://github.com/inorganik/CountUp.js -->
    <script src="/frontend/js/plugins/countup.min.js"></script>
    <!--  Plugin for Parallax, full documentation here: https://github.com/wagerfield/parallax  -->
    <script src="/frontend/js/plugins/parallax.min.js"></script>
    <!-- Control Center for DealsHood Kit: parallax effects, scripts for the example pages etc -->
    <!--  Google Maps Plugin    -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTTfWur0PDbZWPr7Pmq8K3jiDp0_xUziI"></script>
    <script src="/frontend/js/soft-design-system.min.js?v=1.1.0" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        // get the element to animate
        var element = document.getElementById('count-stats');
        var elementHeight = element.clientHeight;

        // listen for scroll event and call animate function

        document.addEventListener('scroll', animate);

        // check if element is in view
        function inView() {
            // get window height
            var windowHeight = window.innerHeight;
            // get number of pixels that the document is scrolled
            var scrollY = window.scrollY || window.pageYOffset;
            // get current scroll position (distance from the top of the page to the bottom of the current viewport)
            var scrollPosition = scrollY + windowHeight;
            // get element position (distance from the top of the page to the bottom of the element)
            var elementPosition = element.getBoundingClientRect().top + scrollY + elementHeight;

            // is scroll position greater than element position? (is element in view?)
            if (scrollPosition > elementPosition) {
                return true;
            }

            return false;
        }

        var animateComplete = true;
        // animate element when it is in view
        function animate() {

            // is element in view?
            if (inView()) {
                if (animateComplete) {
                    if (document.getElementById('state1')) {
                        const countUp = new CountUp('state1', document.getElementById("state1").getAttribute("countTo"));
                        if (!countUp.error) {
                            countUp.start();
                        } else {
                            console.error(countUp.error);
                        }
                    }
                    if (document.getElementById('state2')) {
                        const countUp1 = new CountUp('state2', document.getElementById("state2").getAttribute("countTo"));
                        if (!countUp1.error) {
                            countUp1.start();
                        } else {
                            console.error(countUp1.error);
                        }
                    }
                    if (document.getElementById('state3')) {
                        const countUp2 = new CountUp('state3', document.getElementById("state3").getAttribute("countTo"));
                        if (!countUp2.error) {
                            countUp2.start();
                        } else {
                            console.error(countUp2.error);
                        };
                    }
                    animateComplete = false;
                }
            }
        }

        if (document.getElementById('typed')) {
            var typed = new Typed("#typed", {
                stringsElement: '#typed-strings',
                typeSpeed: 90,
                backSpeed: 90,
                backDelay: 200,
                startDelay: 500,
                loop: true
            });
        }
    </script>
    <script>
        if (document.getElementsByClassName('page-header')) {
            window.addEventListener('scroll', function() {
                var scrollPosition = window.pageYOffset;
                var bgParallax = document.querySelector('.page-header');
                var limit = bgParallax.offsetTop + bgParallax.offsetHeight;
                if (scrollPosition > bgParallax.offsetTop && scrollPosition <= limit) {
                    bgParallax.style.backgroundPositionY = (50 - 10 * scrollPosition / limit * 3) + '%';
                } else {
                    bgParallax.style.backgroundPositionY = '50%';
                }
            });
        }

        $(document).on('click', '.likeBtn', function() {

            let button = $(this);

            let id = button.data('id');

            $.ajax({
                url: '/posts/' + id + '/toggle-like',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },

                success: function(res) {

                    $('#like-count-' + id).text(res.likes);

                    if (res.liked) {
                        button.addClass('liked');
                    } else {
                        button.removeClass('liked');
                    }
                }
            });

        });


        /*
        |--------------------------------------------------------------------------
        | SHARE
        |--------------------------------------------------------------------------
        */

        $(document).on('click', '.shareBtn', function() {

            let id = $(this).data('id');

            let url = $(this).data('url');

            // share api
            if (navigator.share) {

                navigator.share({
                    url: url
                });

            } else {

                navigator.clipboard.writeText(url);

                alert('Link copied');

            }

            // increase share count
            $.ajax({

                url: '/posts/' + id + '/share',

                type: 'POST',

                data: {
                    _token: '{{ csrf_token() }}',
                    platform: 'web'
                }

            });

        });
    </script>
    <script type="application/ld+json">
</script>
</body>

</html>
