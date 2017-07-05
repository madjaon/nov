<!doctype html>
<html lang="vi">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<meta name="robots" content="noindex,nofollow" />
	<meta name="revisit-after" content="1 days">
	<meta name="language" content="vietnamese" />
	<meta name="title" content="{!! $meta_title !!}">
	<meta name="keywords" content="{!! $meta_keyword !!}">
	<meta name="description" content="{!! $meta_description !!}">
	<meta property="og:url" content="{!! url()->current() !!}" />
	<meta property="og:title" content="{!! $meta_title !!}" />
	<meta property="og:description" content="{!! $meta_description !!}" />
	@if(!empty($meta_image))
	<meta property="og:image" content="{!! url($meta_image) !!}" />
	@endif
	{{-- getImageDimensionsOg($meta_image) --}}
	<meta property="og:type" content="website"/>
	@if(isset($configfbappid) && $configfbappid != '')
	<meta property="fb:app_id" content="{!! $configfbappid !!}" />
	@endif
	<link rel="shortcut icon" href="{!! url('img/favicon.png') !!}" type="image/x-icon">
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
	<title>@yield('title')</title>
	@if($configcode)
	{!! $configcode !!}
	@endif
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<link rel="stylesheet" href="{!! asset('css/app.css') !!}">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
	<script src="https://code.jquery.com/jquery-1.11.3.min.js" integrity="sha256-7LkWEzqTdpEfELxcZZlS6wAx5Ff13zZ83lYO2/ujj7g=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
	<script src="{!! asset('js/app.js') !!}"></script>
</head>
