<?php 
  $h1 = $seo->h1;
  $title = ($seo->meta_title!='')?$seo->meta_title:$h1;
  $extendData = array(
    'meta_title' => $seo->meta_title,
    'meta_keyword' => $seo->meta_keyword,
    'meta_description' => $seo->meta_description,
    'meta_image' => $seo->meta_image,
  );
?>
@extends('site.layouts.master', $extendData)

@section('title', $title)

@section('content')

<?php
  $breadcrumb = array(
    ['name' => $h1, 'link' => '']
  );
?>
@include('site.common.breadcrumb', $breadcrumb)

<h1 class="mb-3">{!! $h1 !!}</h1>

<div class="row">
  @foreach($data as $value)
    <div class="col-sm-4 mb-3">
      <a href="{!! CommonUrl::getUrlPostTag($value->slug) !!}" title="{!! $value->name !!}">{!! $value->name !!}</a>
    </div>
  @endforeach 
</div>

@endsection