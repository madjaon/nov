<?php 
  $h1 = $post->h1;
  $title = ($post->meta_title!='')?$post->meta_title:$h1;
  $extendData = array(
    'meta_title' => $post->meta_title,
    'meta_keyword' => $post->meta_keyword,
    'meta_description' => $post->meta_description,
    'meta_image' => $post->meta_image,
    'isPost' => true
  );
?>
@extends('site.layouts.master', $extendData)

@section('title', $title)

@section('content')

<?php 
  $breadcrumb = array();
  foreach($types as $value) {
    $breadcrumb[] = ['name' => $value->name, 'link' => CommonUrl::getUrlPostType($value->slug)];
  }
  $breadcrumb[] = ['name' => $h1, 'link' => ''];
?>
@include('site.common.breadcrumb', $breadcrumb)

<h1 class="mb-3">{!! $h1 !!}</h1>

@if(!empty($post->patterns))<div class="description mb-3">{!! $post->patterns !!}</div>@endif

@if(!empty($post->summary))<div class="description mb-3">{!! $post->summary !!}</div>@endif

@include('site.common.ad', ['posPc' => 15, 'posMobile' => 16])

@if(!empty($post->description))<div class="description mb-3">{!! $post->description !!}</div>@endif

@include('site.common.ad', ['posPc' => 17, 'posMobile' => 18])

@if(!empty($related))
<div class="description mb-4">
  <blockquote class="blockquote">
    <strong>Liên Quan</strong>
  </blockquote>
  <ul>
    @foreach($related as $value)
      <li><a href="{!! url($value->slug) !!}" title="{!! $value->name !!}">{!! $value->name !!}</a></li>
    @endforeach
  </ul>
</div>
@endif

@include('site.common.social')

<div class="comment mb-5">
  <div class="fb-comments" data-numposts="10" data-colorscheme="dark" data-width="100%" data-href="{!! url($post->slug) !!}"></div>
</div>

@endsection