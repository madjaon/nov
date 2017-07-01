<?php 
  $h1 = $data->h1;
  $title = ($data->meta_title!='')?$data->meta_title:$h1;
  $extendData = array(
    'meta_title' => $data->meta_title,
    'meta_keyword' => $data->meta_keyword,
    'meta_description' => $data->meta_description,
    'meta_image' => $data->meta_image,
    'isPost' => true,
    'isEpchap' => true
  );
?>
@extends('site.layouts.default', $extendData)

@section('title', $title)

@section('content')

<div class="row">
  <div class="col">

    <?php 
      $breadcrumb = array();
      foreach($types as $value) {
        $breadcrumb[] = ['name' => $value->name, 'link' => CommonUrl::getUrlPostType($value->slug)];
      }
      $breadcrumb[] = ['name' => $post->name, 'link' => url($post->slug)];
      $breadcrumb[] = ['name' => $data->name, 'link' => ''];
    ?>
    @include('site.common.breadcrumb', $breadcrumb)
    
    <h1 class="mb-3 align-center">{!! $h1 !!}</h1>

    @if(!empty($post->name2))
      <div class="mb-3 text-muted align-center">{!! $post->name2 !!}</div>
    @endif

    <div class="align-center mb-3 d-flex justify-content-center align-items-center">
      @if(isset($data->epPrev))
        <a href="{!! CommonUrl::getUrl2($post->slug, $data->epPrev->slug) !!}" class="btn btn-primary m-2"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
      @else
        <a class="btn btn-secondary m-2 disabled"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
      @endif

      {!! Form::select(null, $epchapArray, CommonUrl::getUrl2($post->slug, $data->slug), array('class' =>'form-control m-2', 'style'=>'width:200px;', 'onchange'=>'javascript:location.href = this.value;')) !!}

      @if(isset($data->epNext))
        <a href="{!! CommonUrl::getUrl2($post->slug, $data->epNext->slug) !!}" class="btn btn-primary m-2"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
      @else
        <a class="btn btn-secondary m-2 disabled"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
      @endif
    </div>
    
    @include('site.common.ad', ['posPc' => 19, 'posMobile' => 20])

    @if(!empty($data->description))<div class="mb-3">{!! $data->description !!}</div>@endif

    @include('site.common.ad', ['posPc' => 21, 'posMobile' => 22])

    <h2 class="mb-3 text-muted align-center">{!! $data->name !!}</h2>

    <div class="align-center mb-3 d-flex justify-content-center align-items-center">
      @if(isset($data->epPrev))
        <a href="{!! CommonUrl::getUrl2($post->slug, $data->epPrev->slug) !!}" class="btn btn-primary m-2"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
      @else
        <a class="btn btn-secondary m-2 disabled"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
      @endif

      {!! Form::select('epchapUrl', $epchapArray, CommonUrl::getUrl2($post->slug, $data->slug), array('class' =>'form-control', 'style'=>'width:200px;')) !!}

      @if(isset($data->epNext))
        <a href="{!! CommonUrl::getUrl2($post->slug, $data->epNext->slug) !!}" class="btn btn-primary m-2"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
      @else
        <a class="btn btn-secondary m-2 disabled"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
      @endif
    </div>

    @include('site.common.social')

    <div class="comment mb-5">
      <div class="fb-comments" data-numposts="10" data-colorscheme="dark" data-width="100%" data-href="{!! url($post->slug) !!}"></div>
    </div>

  </div>
</div>

@endsection