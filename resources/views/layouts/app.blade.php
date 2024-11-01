@php $isHomePage = $isHomePage ?? false; @endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-D4Q2EZ7SGK"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-D4Q2EZ7SGK');
</script>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<meta property="og:title" content="{{ $isHomePage ? 'ბუკინისტები' : (isset($book) ? $book->title : (isset($booknews) ? $booknews->title : 'ბუკინისტები')) }}">
<meta property="og:description" content="{{ $isHomePage ? 'პირველი ბუკინისტური ონლაინ მაღაზია საქართველოში' : (isset($book) ? $book->description : (isset($booknews) ? $booknews->description : 'პირველი ბუკინისტური ონლაინ მაღაზია საქართველოში')) }}">
<meta property="og:url" content="{{ Request::fullUrl() }}">
<meta property="og:image" content="{{ $isHomePage ? asset('default.jpg') : (isset($book) ? asset('storage/' . $book->photo) : (isset($booknews) ? asset('storage/' . $booknews->image) : asset('default.jpg'))) }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:site_name" content="bukinistebi.ge">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title', 'Bukinistebi.ge')</title>

<!-- Twitter Card Meta Tags (Optional) -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $book->title ?? 'bukinistebi - ბუკინისტური მაღაზია' }}">
<meta name="twitter:description" content="{{ $book->description ?? 'პირველი ბუკინისტური ონლაინ მაღაზია საქართველოში' }}">
<meta name="twitter:image" content="{{ 
    isset($book) && $book->photo ? asset('storage/' . $book->photo) : 
    (isset($booknews) && $booknews->image ? asset('storage/' . $booknews->image) : asset('default.jpg'))
}}">
<meta name="robots" content="index, follow">

 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
 
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="icon" href="{{ asset('uploads/favicon/favicon.png') }}" type="image/x-icon">

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

</head>
<body>
  
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container" >
            <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ asset('uploads/logo/bukinistebi.ge.png') }}" width="130px" style="position:relative; top:7px"></a>
            <button  style="position: relative; top: 7px;" class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" ></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto"  style="position: relative; top: 12px">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">საწყისი</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('books')}}">წიგნები</a>
                    </li>
                    
                    @if (!auth()->check() || auth()->user()->role !== 'publisher')
                    
                    <li class="nav-item">
                        @php
                        $cartCount = 0;
                        if (Auth::check() && Auth::user()->cart) {
                            $cartCount = Auth::user()->cart->cartItems->count();
                        }
                    @endphp
                     <!-- Cart Link in the Navbar -->
<a class="nav-link" href="{{ route('cart.show') }}">
    კალათა <span id="cart-count" class="circle">{{ $cartCount }}</span>
</a>
                    </li>
                    @endif

                     <!-- Right Side of Navbar --> 
                     @guest
                     <li class="nav-item dropdown">
                         <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" 
                            data-bs-toggle="dropdown" aria-expanded="false" v-pre>
                            <i class="bi bi-file-earmark-person" style="position: relative; top:-1px; font-size: 14px"></i> შესვლა
                         </a>
                 
                         <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" style="width: 15vw;">
                             <li><a class="nav-link" href="{{ route('login') }}"> &nbsp;<i class="bi bi-box-arrow-in-right"></i> {{ __('ავტორიზაცია') }}</a></li>
                             @if (Route::has('register'))
                             <li class="nav-item">
                                 <a class="nav-link" href="{{ route('register') }}">  &nbsp; <i class="bi bi-person-plus"></i>  {{ __('რეგისტრაცია') }}</a>
                             </li>
                             @endif
                         </ul>
                     </li>
                 @else
                     <li class="nav-item dropdown">
                         <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" 
                            data-bs-toggle="dropdown" aria-expanded="false" v-pre>
                            <i class="bi bi-file-earmark-person" style="position: relative; top:-1px; font-size: 14px"></i>   {{ Auth::user()->name }}
                         </a>
                 
                         <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                             @if (Auth::user()->role === 'publisher')
                                 <!-- Publisher's Room Link -->
                                 <li style="margin-top:15px;"><a class="dropdown-item" href="{{ route('publisher.dashboard') }}">
                                     <i class="bi bi-door-open"></i> &nbsp;{{ __('ბუკინისტის ოთახი') }}
                                 </a></li>
                                 <li><a class="dropdown-item" href="{{ route('publisher.my_books') }}">
                                    <i class="bi bi-book"></i> &nbsp;ჩემი ატვირთული წიგნები
                                </a></li>
                                 <li><a class="dropdown-item" href="{{ route('publisher.account.edit') }}">
                                    <i class="bi bi-pencil"></i> &nbsp;პროფილის რედაქტირება
                                </a></li>
                             @else
                                 <!-- General User's Purchase History Link -->
                                 <li style="margin-top:15px;"><a class="dropdown-item" href="#">
                                     <i class="bi bi-credit-card-2-front"></i> &nbsp;{{ __('შენაძენის ისტორია') }}
                                 </a></li>
                                 <li style="margin-top:15px; padding-bottom:10px;">
                                     <a class="dropdown-item" href="{{ route('account.edit') }}">
                                         <i class="bi bi-pencil"></i> &nbsp;{{ __('პროფილის რედაქტირება') }}
                                     </a>
                                 </li>
                             @endif
                             <li>
                                 <form method="POST" action="{{ route('logout') }}">
                                     @csrf
                                     <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                  this.closest('form').submit();">
                                        <i class="bi bi-box-arrow-right"></i> &nbsp;{{ __('გამოსვლა') }}
                                     </a>
                                 </form>
                             </li>
                         </ul>
                     </li>
                 @endguest
                 
              
                  
                    <form class="d-flex" role="search" action="{{ route('search') }}" method="GET" onsubmit="return validateSearch()" style="position: relative; top:-5px;">
                        <input class="form-control me-2 styled-input" name="title" type="search" value="{{ request()->get('title') }}" placeholder="წიგნების ძიება..." aria-label="Search" id="searchInput">
                        <button class="btn btn-outline-success" type="submit" style="border-bottom-right-radius:0px"><i class="bi bi-search"></i></button>
                    </form>

                    <script>
                        function validateSearch() {
                            var searchInput = document.getElementById('searchInput').value.trim();
                            if (searchInput === "") {
                                 return false; // Prevent form submission
                            }
                            return true;
                        }
                    </script>
      
 
                </ul>

               
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5">
        @yield('content') <!-- Page-specific content goes here -->
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center text-lg-start mt-5">
    <div class="container p-4">
        <div class="row">
            <!-- Column 1 -->
            <div class="col-lg-4 col-md-12 mb-4 mb-md-0">
                <h5 class="text-uppercase">Bukinistebi.ge</h5>
                <div class="col-md-10">
                    <p>პირველი ბუკინისტური ონლაინ მაღაზია საქართველოში</p>
                </div>
            </div>

            <!-- Terms and Conditions Column (New) -->
            <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase">მოხმარებლებისთვის</h5>
                <p><a href="{{ route('terms_conditions') }}" class="text-white text-decoration-none">წესები და პირობები</a></p>
            </div>

            <!-- Column 3 -->
            <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase">კონტაქტი</h5>
                <ul class="list-unstyled">
                    info@bukinistebi.ge
                </ul>
            </div>

            <!-- Column 4 -->
            <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase">გვალაიქე</h5>
                <a href="#" class="fb-icon"><i class="bi bi-facebook fs-5"></i></a>
                <a href="#" class="insta-icon"><i class="bi bi-instagram fs-5"></i></a>
                <a href="#" class="youtube-icon"><i class="bi bi-youtube fs-3" style="position: relative; margin-top:5px"></i></a>
            </div>
        </div>
    </div>

    <div class="text-center p-3 bg-dark">
        <!-- Additional footer content if needed -->
    </div>
</footer>


 <!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Bootstrap JS if needed -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS for Cart Count 
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" 
            integrity="sha384-XaTkjbZyUVlCFN+KmXjkVgKucOTvEBhzS5aUB+K/njDR8aONvyP6YL+Q71Kj7Qc9" 
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" 
            integrity="sha384-pPkmV66OFEjt68bxobSxMVqkhO/ENROeTOWSXssGq0HE11cbiS6L4AaFTxJ5k0l0" 
            crossorigin="anonymous"></script>-->
    

             
            <script>
                
                document.addEventListener('DOMContentLoaded', function() {
        const navbar = document.querySelector('.navbar');

        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    });
</script>


    @yield('scripts')
     
    
</body>
</html>
