<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bukinistebi.ge')</title>
    <meta name="description" content="Find your favorite books at the best prices. Browse fiction, non-fiction, educational books, and more!">
    <meta name="keywords" content="buy books, bookshop, online book store, fiction books, non-fiction books">
    <meta name="robots" content="index, follow">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="icon" href="{{ asset('uploads/favicon/favicon.png') }}" type="image/x-icon">

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

</head>
<body>
  
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container" >
            <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ asset('uploads/logo/bukinistebi.ge.png') }}" width="130px"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto"  style="position: relative; top: 10px">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">საწყისი</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('books')}}">წიგნები</a>
                    </li>
                    
                    <li class="nav-item">
                        @php
                        $cartCount = 0;
                        if (Auth::check() && Auth::user()->cart) {
                            $cartCount = Auth::user()->cart->cartItems->count();
                        }
                    @endphp
                     <!-- Cart Link in the Navbar -->
<a class="nav-link" href="{{ route('cart.show') }}">
    კალათა (<span id="cart-count">{{ $cartCount }}</span>)
</a>
                    </li>

                     <!-- Right Side of Navbar --> 
                    @guest
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false" v-pre>
                           <i class="bi bi-file-earmark-person"></i>   შესვლა
                        </a>
            
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" style="width: 15vw;">
                            <li><a class="nav-link" href="{{ route('login') }}"> &nbsp;<i class="bi bi-box-arrow-in-right"></i> {{ __('ავტორიზაცია') }}</a></li>
                            @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">  &nbsp; <i class="bi bi-person-plus"></i>  {{ __('რეგისტრაცია') }}</a>
                            </li>
                        @endif
                    
                    </ul></li>
                    
   


                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" 
                               data-bs-toggle="dropdown" aria-expanded="false" v-pre>
                               <i class="bi bi-file-earmark-person"></i>   {{ Auth::user()->name }}
                            </a>
                
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li style="margin-top:15px; padding-bottom:10px;"><a class="dropdown-item" href="{{ route('account.edit') }}">
                                    <i class="bi bi-pencil"></i> &nbsp;{{ __('პროფილის რედაქტირება') }}
                                </a></li>
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
              
                  
                    <form class="d-flex" role="search" action="{{ route('search') }}" method="GET" onsubmit="return validateSearch()" style="position: relative; top:-3px;">
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
                <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Bukinistebi.ge - ონლაინ მაღაზია</h5>
                    <p>რასაც ეძებ - აქაა</p>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">კონტაქტი</h5>
                    <ul class="list-unstyled">
                        info@bukinistebi.ge 
                        
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">გვალაიქე</h5>
                    
                    <a href="#" class="fb-icon"><i class="bi bi-facebook fs-5"></i></a> 
                    <a href="#" class="insta-icon"><i class="bi bi-instagram fs-5"></i></a> 
                    <a href="#" class="youtube-icon"><i class="bi bi-youtube fs-3" style="position: relative; margin-top:5px"></i></a>
                    
                </div>
            </div>
        </div>
        <div class="text-center p-3 bg-dark">
         
        </div>
    </footer>

 <!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include Bootstrap JS if needed -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS for Cart Count -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" 
            integrity="sha384-XaTkjbZyUVlCFN+KmXjkVgKucOTvEBhzS5aUB+K/njDR8aONvyP6YL+Q71Kj7Qc9" 
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" 
            integrity="sha384-pPkmV66OFEjt68bxobSxMVqkhO/ENROeTOWSXssGq0HE11cbiS6L4AaFTxJ5k0l0" 
            crossorigin="anonymous"></script>
    

             
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
