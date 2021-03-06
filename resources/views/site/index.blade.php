<?php 
  if(isset($seo)) {
    $title = ($seo->meta_title)?$seo->meta_title:'Trang chủ';
    $meta_title = $seo->meta_title;
    $meta_keyword = $seo->meta_keyword;
    $meta_description = $seo->meta_description;
    $meta_image = $seo->meta_image;
  } else {
    $title = 'Trang chủ';
    $meta_title = '';
    $meta_keyword = '';
    $meta_description = '';
    $meta_image = '';
  }
  $extendData = array(
      'meta_title' => $meta_title,
      'meta_keyword' => $meta_keyword,
      'meta_description' => $meta_description,
      'meta_image' => $meta_image,
      'isHome' => true
    );
?>
@extends('site.layouts.master', $extendData)

@section('title', $title)

@section('content')

@include('site.common.ad', ['posPc' => 7, 'posMobile' => 8])

<div class="box-style mb-3">
  <div class="d-inline-flex py-2 title">Mới Cập Nhật</div>
</div>

@include('site.post.table', array('data' => $data, 'dataEp' => $dataEp))

@include('site.common.ad', ['posPc' => 9, 'posMobile' => 10])

@endsection