<?php 
  $extendData = array(
      'is404' => true,
      'meta_title' => '',
      'meta_keyword' => '',
      'meta_description' => '',
      'meta_image' => '',
    );
?>
@extends('site.layouts.default', $extendData)

@section('title', '404 Not Found')

@section('content')
<div class="row">
  <div class="col align-center">
    <h1 class="mb-3 pb-2">404 Not Found</h1>
    <p class="mb-3">Đường dẫn không tồn tại hoặc đã bị xóa. Xin mời bạn theo dõi các nội dung khác <a href="/"><strong>tại đây</strong></a></p>
  </div>
</div>
@endsection