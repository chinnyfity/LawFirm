<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="" />
	<meta name="author" content="" />
	<meta name="robots" content="index, follow" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta name="description" content="" />
	<meta property="og:title" content="" />
	<meta property="og:description" content="" />
	<meta property="og:image" content="social-image.png"/>
	<meta name="format-detection" content="telephone=no">
    <title>{{ $page_title }} | ABC Law Firm </title>

	<link rel='stylesheet' href="{{ asset('css/sweetalert2.min.css') }}">
  	<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>

    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
	<link href="{{ asset('vendors/owl-carousel/owl.carousel.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style_dashboard.css') }}" rel="stylesheet">
	<link href="{{ asset('css/responsive_dashboard.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" href="{{ asset('css/responsive.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap.min.css') }}">

    <link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">

	<link rel="stylesheet" href="{{ asset('css/custom-bootstrap-margin-padding.css') }}" type="text/css"/>

</head>
<body>

	<input type="hidden" value="{{ csrf_token() }}" id="txt_token">
    <input type="text" value="{{ url('/') }}" id="site_url" style="display:none">
    <input type="text" value="{{ $page_name }}" id="page_name" style="display:none">
	<input type="text" value="{{ $user->email }}" id="user_email" style="display:none">
	<input type="text" value="{{ $user->uuid }}" id="user" style="display:none">

	<div class="alert1 alert-danger"></div>
	<div class="overlay1"></div>

    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>



    <div id="main-wrapper">
        <div class="nav-header">
            <a href="{{ url('/') }}" class="brand-logo">
				<img src="{{ asset('images/logo.png') }}" class="for_mobile small-logo">
				<img src="{{ asset('images/logo.png') }}" class="for_desktop">
            </a>

            <div class="nav-control">
                <div class="hamburger d-lg-block d-none">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>


		
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="dashboard_bar">
                                {{ $page_title }}
                            </div>
                        </div>

                        <ul class="navbar-nav header-right">
                            <li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link  ai-icon" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fa fa-bell"></i>
                                    <div class="pulse-css"></div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <div id="DZ_W_Notification1" class="widget-media dz-scroll p-3" style="height:240px;">
										
									</div>
                                    <a class="all-notification" href="#">See all notifications<i class="ti-arrow-right"></i></a>
                                </div>
                            </li>

							@php
							$imgs1 = asset('images/no_passport.jpg');
							@endphp
							
                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown">
                                    <img src="{{ $imgs1 }}" alt=""/>
									<div class="header-info">
										<span>{{ ucfirst($user->firstname) }}</span>
									</div>
                                </a>

								<div class="dropdown-menu dropdown-menu-end">
									<a href="#" class="dropdown-item ai-icon">
										<i class="fa fa-gear"></i>
                                        <span class="ms-2">Settings </span>
                                    </a>

									<a href="#" class="dropdown-item ai-icon">
										<i class="fa fa-power-off"></i>
                                        <span class="ms-2">Logout </span>
                                    </a>
								</div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
		

		
        <div class="deznav">
            <div class="deznav-scroll">
				<ul class="metismenu" id="menu">
                    <li><a class="has-arrow_ ai-icon active" href="{{ route('dashboard') }}/" aria-expanded="false">
							<i class="fa fa-home"></i>
							<span class="nav-text">Dashboard</span>
						</a>
                    </li>

                    <li><a class="has-arrow_ ai-icon" href="{{ route('add-clients') }}/" aria-expanded="false">
							<i class="fa fa-plus-circle"></i>
							<span class="nav-text">Add Clients</span>
						</a>
                    </li>

                    <li><a class="has-arrow_ ai-icon" href="{{ route('view-clients') }}/" aria-expanded="false">
							<i class="fa fa-eye"></i>
							<span class="nav-text">View Clients</span>
						</a>
                    </li>

					<li style="padding-bottom:90px"><a href="#" class="ai-icon" aria-expanded="false">
							<i class="fa fa-power-off"></i>
							<span class="nav-text">Logout</span>
						</a>
					</li>
                </ul>
			</div>
        </div>