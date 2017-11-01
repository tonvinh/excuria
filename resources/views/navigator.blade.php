<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <title>ExcuriA+</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="/vendor/jquery/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/navigator.js"></script>
    <script type="text/javascript" src="/vendor/modernizr/modernizr.js"></script>
    <script type="text/javascript" src="/vendor/waterwheel/jquery.waterwheelCarousel.min.js"></script>
    <link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
<header>
    <div class="container menu-container">
        <nav class="navbar navbar-inverse navbar-default-cs">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="/navigator"><img src="/img/page-1.svg" class="logo" alt="image" title="image"></a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <div class="search-btn-area">
                            <label for="search-press" class="search-press-icon">
                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                            </label>
                            <label for="search-press" class="close-press-icon">
                              <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                            </label>
                            <input type="text" placeholder="what are your looking for?" class="search-btn" id="search-press">
                        </div>
                    </li>
                    <li><a href="#" class="button-cirle">Sign In</a></li>
                    <li><a href="#" class="button-cirle">Get Started For FREE</a></li>
                </ul>
            </div>
        </nav>
    </div>
</header>

<div class="wrap-content">
    <div class="container">
        @if (isset($city))
        <div class="row">
            <div class="col-md-12">
                <div class="main-imgarea-object main-content-bkcolor">
                    <div style="background: url('{{ $city['image'] }}')" class="main-img-object"></div>
                    <a href="#" class="button-child-object main">{{ $city['name'] }}</a>
                </div>
            </div>
        </div>
        @endif

        @if (isset($activity))

        <div class="row">
            <div class="col-md-12">
                <div class="main-imgarea-object main-content-bkcolor">

                    <div style="background: url({!! $activity['image'] !!})" class="main-img-object"></div>
                    <a href="#" class="button-child-object main">{{ $activity['name'] }}</a>
                </div>
            </div>
        </div>
        @endif

        <div class="row" id="template-title">
            <div class="col-md-12">
                <p class="object-template-title">Discover what is trending on Excuri ... </p>
            </div>
        </div>

        <div class="row" id="thumbnail-area-cities">
            <div class="child-imgarea-object main-content-bkcolor">
                @if( isset($cities))
					<?php $i = 1; ?>
                    @foreach( $cities as $city)
                        <div class="item-child-object" NumberOfObjects="5" ImageType="square" DisplayObjectIncluded="false">
                            <a href="{{ $search === 0 ? '?ObjectType=city&City=' . $city->name : '#next-search' }}" class="item-thumb-img">
                                <div class="child-img-object city" style="background: url('{{strlen($city->image) > 0 ? $city->image : '/img/city_1.png'}}');" title="{{ $city->name }}" id="item-{{ $i }}">
                                    <img src="{{ strlen($city->image) > 0 ? $city->image : '/img/city_1.png' }}" title="{{ $city->name }}">
                                </div>
                            </a>
                            <a href="?ObjectType=city&City={{ $city->name }}" class="button-child-object">{{ $city->name }}</a>
                        </div>
                        <?php $i++; ?>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="row" id="slider-area-cities">
            <div id="carousel-cities">
                @if( isset($cities))
                    <?php $i = 1; ?>
                    @foreach( $cities as $city)
                        <a href="{{ $search === 0 ? '?ObjectType=city&City=' . $city->name : '#next-search' }}" class="blank-title">
                            <img style="background: url({{ strlen($city->image) > 0 ? $city->image : '/img/city_1.png' }});" id="item-{{ $i }}" class="item-slider-object" title="{{ $city->name }}">
                        </a>
                        <?php $i++; ?>
                    @endforeach
                @endif

                @if( isset($cities) && sizeof($cities) > 1)
                    <a href="#" id="prev"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a>
                    <a href="#" id="next"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>
                @endif
                <div class="thumbnail-close">
                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                </div>
            </div>
            @if( isset($cities) && sizeof($cities) > 0)
                <div class="title-itemslider-area">
                    <a href="#" class="title-itemslider"></a>
                </div>
            @endif
        </div>

        <div class="row" id="thumbnail-area-activities">
            <div class="child-imgarea-object main-content-bkcolor">
                @if( isset($activities))
					<?php $i = 1; ?>
                    @foreach( $activities as $activity)
                        <div class="item-child-object" NumberOfObjects="5" ImageType="circle" >
                            <a href="{{ $search === 0 ? '?ObjectType=activity&Activity=' . $activity->name : '#next-search' }}" class="item-thumb-img">
                                <div class="child-img-object activity" style="background: url('{{strlen($activity->image) > 0 ? $activity->image : '/img/city_1.png'}}');" title="{{ $activity->name }}" id="item-{{ $i }}">
                                    <img src="{{ strlen($activity->image) > 0 ? $activity->image : '/img/city_1.png' }}" title="{{ $activity->name }}" title="{{ $activity->name }}">
                                </div>
                            </a>
                            <a href="?ObjectType=activity&Activity={{ $activity->name }}" class="button-child-object">{{ $activity->name }}</a>
							<?php $i++; ?>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="row" id="slider-area-activities">
            <div id="carousel-activities">
                @if( isset($activities))
                    <?php $i = 1; ?>
                    @foreach( $activities as $activity)
                        <a href="{{ $search === 0 ? '?ObjectType=activity&Activity=' . $activity->name : '#next-search' }}" class="blank-title">
                            <img style="background: url({{ strlen($activity->image) > 0 ? $activity->image : '/img/city_1.png' }});" id="item-{{ $i }}" class="item-slider-object" title="{{ $activity->name }}">
                        </a>
                        <?php $i++; ?>
                    @endforeach
                @endif

                @if( isset($activities) && sizeof($activities) > 1)
                    <a href="#" id="prev"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a>
                    <a href="#" id="next"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>
                @endif
                <div class="thumbnail-close">
                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                </div>
            </div>
            @if( isset($activities) && sizeof($activities) > 0)
            <div class="title-itemslider-area">
                <a href="#" class="title-itemslider"></a>
            </div>
            @endif
        </div>

    </div>
</div>
<footer class="footer-templatearea">
    <div class="container">
        <div class="row">
            <div class="footer-area-object">
                <div class="col-md-12 main-content-bkcolor">
                    <hr class="break-line-object">
                    <ul class="footer-group left">
                        <li><a href="#">Media Kit</a></li>
                        <li><a href="#">Mailling List</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Terms & Conditions</a></li>
                    </ul>
                    <ul class="footer-group right">
                        <li>
                            <p class="copy-right">Blippe PTE LTD</p>
                        </li>
                        <li>
                            <a href="#">
                                <div class="footer-faceicon"></div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="footer-insicon"></div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="footer-twintericon"></div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
</body>
</html>

