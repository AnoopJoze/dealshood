<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../frontend/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../frontend/img/favicon.png">
    <title>
        DealsHood
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- CSS Files -->
    <link id="pagestyle" href="../frontend/css/soft-design-system.css?v=1.1.0" rel="stylesheet" />
    <!-- Nepcha Analytics (nepcha.com) -->
    <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
    <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .nav-link {
            color: #000 !important;
        }

        .likeBtn.liked {
            color: red;
        }

        .likeBtn.liked {

            background: #ffebee;
            color: red;
            border-color: red;

        }

        .card-blog {

            transition: 0.3s;

        }

        .card-blog:hover {

            transform: translateY(-5px);

        }
    </style>
</head>

<body class="about-us">
    <!-- Navbar Transparent -->
    <nav class="navbar navbar-expand-lg fixed-top z-index-3 w-100 bg-white shadow-sm p-0">
        <div class="container">
            <a class="navbar-brand text-dark" href="{{ route('home') }}">
                <img src="../frontend/img/dealshood.png" class="img-fluid" alt="Logo" width="80">
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
    <header class="bg-gradient-white">
        <div class="page-header min-vh-75" style="background-image: url('../frontend/img/office-dark.jpg');">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center mx-auto my-auto">
                        <h1 class="text-white">Discover best deals !...</h1>
                        <p class="lead mb-4 text-white opacity-8">Find the best deals from your neighbourhood.</p>
                        <div class="d-flex justify-content-center">
                            <a href="javascript:;"><i class="fab fa-facebook text-lg text-white me-4"></i></a>
                            <a href="javascript:;"><i class="fab fa-instagram text-lg text-white me-4"></i></a>
                            <a href="javascript:;"><i class="fab fa-twitter text-lg text-white me-4"></i></a>
                            <a href="javascript:;"><i class="fab fa-google-plus text-lg text-white"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="position-absolute w-100 bottom-0">
                <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 24 150 40" preserveAspectRatio="none" shape-rendering="auto">
                    <defs>
                        <path id="gentle-wave"
                            d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
                    </defs>
                    <g class="moving-waves">
                        <use xlink:href="#gentle-wave" x="48" y="-1" fill="rgba(255,255,255,0.40" />
                        <use xlink:href="#gentle-wave" x="48" y="3" fill="rgba(255,255,255,0.35)" />
                        <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.25)" />
                        <use xlink:href="#gentle-wave" x="48" y="8" fill="rgba(255,255,255,0.20)" />
                        <use xlink:href="#gentle-wave" x="48" y="13" fill="rgba(255,255,255,0.15)" />
                        <use xlink:href="#gentle-wave" x="48" y="16" fill="rgba(255,255,255,1" />
                    </g>
                </svg>
            </div>
        </div>
        <div class="position-relative overflow-hidden" style="height:36px;margin-top:30px;">
            <div class="w-full absolute bottom-0 start-0 end-0"
                style="transform: scale(2);transform-origin: top center;color: #fff;">
                <svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="currentColor"></path>
                </svg>
            </div>
        </div>
        <form action="javascript:void(0);" method="GET" id="filterForm">
            <div class="container">
                <div class="row bg-white shadow-lg mt-n6 border-radius-md pb-4 p-3 mx-sm-0 mx-1 position-relative">
                    {{-- Locality --}}
                    <div class="col-lg-2 mt-lg-n2 mt-2">
                        <label>Localities</label>
                        <select class="form-control" name="locality_id" id="locality_id">
                            <option value="">Select Locality</option>
                            @foreach ($localities as $locality)
                                <option value="{{ $locality->slug }}"
                                    {{ request('locality_id') == $locality->slug ? 'selected' : '' }}>
                                    {{ $locality->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Category --}}
                    <div class="col-lg-2 mt-lg-n2 mt-2">
                        <label>Category</label>
                        <select class="form-control" name="category_id" id="category_id">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->slug }}"
                                    {{ request('category_id') == $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Sub Category --}}
                    <div class="col-lg-2 mt-lg-n2 mt-2">
                        <label>Sub Category</label>
                        <select class="form-control" name="subcategory_id" id="subcategory_id">
                            <option value="">Select Sub Category</option>

                            @foreach ($subcategories as $subcategory)
                                <option value="{{ $subcategory->slug }}"
                                    {{ request('subcategory_id') == $subcategory->slug ? 'selected' : '' }}>

                                    {{ $subcategory->name }}

                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4 mt-lg-n2 mt-2">
                        <label>Search By Keyword</label>
                        <input class="form-control" name="keyword" id="keyword" value="{{ request('keyword') }}">
                    </div>

                    {{-- Search Button --}}
                    <div class="col-lg-2 mt-lg-n2 mt-2">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn bg-gradient-dark w-100 mb-0">
                            Search
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </header>
    <section class="pt-7 pb-0">
        <div class="container">
            <div class="row" id="post-wrapper">
                @include('frontend.post-cards', ['posts' => $posts])
            </div>

            <div id="loading" style="display:none;text-align:center;">Loading...</div>

            <input type="hidden" id="next-page-url" value="{{ $posts->nextPageUrl() }}">
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
    <script src="../frontend/js/core/popper.min.js" type="text/javascript"></script>
    <script src="../frontend/js/core/bootstrap.min.js" type="text/javascript"></script>
    <script src="../frontend/js/plugins/perfect-scrollbar.min.js"></script>
    <!--  Plugin for TypedJS, full documentation here: https://github.com/inorganik/CountUp.js -->
    <script src="../frontend/js/plugins/countup.min.js"></script>
    <!--  Plugin for Parallax, full documentation here: https://github.com/wagerfield/parallax  -->
    <script src="../frontend/js/plugins/parallax.min.js"></script>
    <!-- Control Center for DealsHood Kit: parallax effects, scripts for the example pages etc -->
    <!--  Google Maps Plugin    -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTTfWur0PDbZWPr7Pmq8K3jiDp0_xUziI"></script>
    <script src="../frontend/js/soft-design-system.min.js?v=1.1.0" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
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
        $('#category_id').on('change', function() {

            let categoryId = $(this).val();
            let subCategoryDropdown = $('#subcategory_id');

            subCategoryDropdown.empty();
            subCategoryDropdown.append('<option value="">Loading...</option>');

            if (categoryId) {
                $.ajax({
                    url: '/get-subcategories/' + categoryId,
                    type: 'GET',
                    success: function(data) {

                        subCategoryDropdown.empty();
                        subCategoryDropdown.append('<option value="">Select Sub Category</option>');

                        $.each(data, function(key, value) {
                            subCategoryDropdown.append(
                                '<option value="' + value.slug + '">' + value.name +
                                '</option>'
                            );
                        });

                    },
                    error: function() {
                        subCategoryDropdown.empty();
                        subCategoryDropdown.append('<option value="">Error loading data</option>');
                    }
                });
            } else {
                subCategoryDropdown.empty();
                subCategoryDropdown.append('<option value="">Select Sub Category</option>');
            }

        });
        let loading = false;

        /*
        |--------------------------------------------------------------------------
        | FILTER FORM
        |--------------------------------------------------------------------------
        */

        document.getElementById('filterForm')
            .addEventListener('submit', function(e) {

                e.preventDefault();

                loadPosts(true);
            });

        /*
        |--------------------------------------------------------------------------
        | AUTO FILTER CHANGE
        |--------------------------------------------------------------------------
        */

        document.querySelectorAll('#filterForm select')
            .forEach(el => {

                el.addEventListener('change', function() {

                    loadPosts(true);
                });
            });

        /*
        |--------------------------------------------------------------------------
        | LOAD POSTS
        |--------------------------------------------------------------------------
        */

        function loadPosts(reset = false, nextPage = null) {
            if (loading) return;

            loading = true;

            document.getElementById('loading').style.display = 'block';

            let form = document.getElementById('filterForm');

            let formData = new FormData(form);

            let params = new URLSearchParams(formData);

            /*
            |--------------------------------------------------------------------------
            | URL
            |--------------------------------------------------------------------------
            */

            let url = nextPage || `{{ route('posts.listing') }}?${params.toString()}`;

            /*
            |--------------------------------------------------------------------------
            | UPDATE BROWSER URL (IMPORTANT)
            |--------------------------------------------------------------------------
            */

            if (reset) {

                window.history.pushState({}, '', url);
            }

            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {

                    document.getElementById('loading').style.display = 'none';

                    /*
                    |--------------------------------------------------------------------------
                    | RESET OR APPEND
                    |--------------------------------------------------------------------------
                    */

                    if (reset) {

                        document.getElementById('post-wrapper').innerHTML = data.html;

                    } else {

                        document.getElementById('post-wrapper')
                            .insertAdjacentHTML('beforeend', data.html);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | NEXT PAGE
                    |--------------------------------------------------------------------------
                    */

                    document.getElementById('next-page-url').value = data.next_page;

                    loading = false;
                })
                .catch(error => {

                    loading = false;

                    document.getElementById('loading').style.display = 'none';

                    console.error(error);
                });
        }

        /*
        |--------------------------------------------------------------------------
        | INFINITE SCROLL
        |--------------------------------------------------------------------------
        */

        window.addEventListener('scroll', function() {

            if (loading) return;

            let scrollTop = window.scrollY;

            let windowHeight = window.innerHeight;

            let docHeight = document.body.offsetHeight;

            if (scrollTop + windowHeight >= docHeight - 300) {

                let nextPage = document.getElementById('next-page-url').value;

                if (!nextPage) return;

                loadPosts(false, nextPage);
            }
        });
    </script>
</body>

</html>
