<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  @if(isset($is404))
    <meta name="robots" content="noindex,nofollow,noodp" />
  @else
    <meta name="robots" content="noindex,nofollow,noodp" />
  @endif

  <meta name="revisit-after" content="1 days">
  <meta name="language" content="vietnamese" />
  <meta name="title" content="{!! $meta_title !!}">
  <meta name="keywords" content="{!! $meta_keyword !!}">
  <meta name="description" content="{!! $meta_description !!}">
  <meta property="og:site_name" content="Truyen On" />
  <meta property="og:url" content="{!! url()->current() !!}" />
  <meta property="og:title" content="{!! $meta_title !!}" />
  <meta property="og:description" content="{!! $meta_description !!}" />

  @if(isset($isBook))
    <meta property="og:type" content="book" />
    <meta property="book:release_date" content="{!! $book_release_date !!}">
    @foreach($book_tag as $value)
      <meta property="book:tag" content="{!! $value !!}">
    @endforeach
    @foreach($book_author as $value)
      <meta property="book:author" content="{!! CommonUrl::getUrlPostTag($value->slug) !!}">
      <meta property="book:tag" content="{!! $value->name !!}">
    @endforeach
  @else
    <meta property="og:type" content="website" />
  @endif

  @if(!empty($meta_image))
    <meta property="og:image" content="{!! url($meta_image) !!}" />
  @endif

  @if(isset($configfbappid) && $configfbappid != '')
    <meta property="fb:app_id" content="{!! $configfbappid !!}" />
  @endif

  <meta name="csrf-token" content="%%csrf_token%%">
  
  <title>@yield('title')</title>
  
  <link rel="alternate" hreflang="vi" href="{!! env('APP_URL', 'https://truyenon.com') !!}" />

  @if(isset($pagePrev))
    <link rel="prev" href="{!! preg_replace('/(\?|&)page=1/', '', $pagePrev) !!}">
  @endif

  @if(isset($pageNext))
    <link rel="next" href="{!! $pageNext !!}">
  @endif

  @if(!isset($pagePrev) && isset($pageNext))
    <link rel="canonical" href="{!! preg_replace('/(\?|&)page=2/', '', $pageNext) !!}">
  @endif

  @if($configcode)
    {!! $configcode !!}
  @endif

  <link rel="apple-touch-icon" sizes="180x180" href="{!! url('img/apple-touch-icon.png') !!}">
  <link rel="icon" type="image/png" sizes="32x32" href="{!! url('img/favicon-32x32.png') !!}">
  <link rel="icon" type="image/png" sizes="16x16" href="{!! url('img/favicon-16x16.png') !!}">
  <link rel="manifest" href="{!! url('img/manifest.json') !!}">
  <link rel="mask-icon" href="{!! url('img/safari-pinned-tab.svg') !!}" color="#5bbad5">
  <link rel="shortcut icon" href="{!! url('img/favicon.ico') !!}">
  <meta name="apple-mobile-web-app-title" content="Truyen On">
  <meta name="application-name" content="Truyen On">
  <meta name="msapplication-config" content="{!! url('img/browserconfig.xml') !!}">
  <meta name="theme-color" content="#ffffff">
  <!-- <meta name="format-detection" content="telephone=no"> -->

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  <link rel="stylesheet" href="{!! asset('css/app.css') !!}">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
  <script src="https://code.jquery.com/jquery-1.11.3.min.js" integrity="sha256-7LkWEzqTdpEfELxcZZlS6wAx5Ff13zZ83lYO2/ujj7g=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
  <script src="{!! asset('js/app.js') !!}"></script>
  @stack('starability')
</head>
