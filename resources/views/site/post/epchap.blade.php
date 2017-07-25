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
  $themescolor = isset($_COOKIE['themescolor'])?$_COOKIE['themescolor']:null;
  $themesfontsize = isset($_COOKIE['themesfontsize'])?$_COOKIE['themesfontsize']:'1.250rem';
  $themeslineheight = isset($_COOKIE['themeslineheight'])?$_COOKIE['themeslineheight']:'2';
  $themesmenu = isset($_COOKIE['themesmenu'])?$_COOKIE['themesmenu']:null;
  if(isset($themesfontsize) || isset($themeslineheight)) {
    $themestext = 'style="font-size: '.$themesfontsize.'; line-height: '.$themeslineheight.';"';
  } else {
    $themestext = 'style="font-size: 1.250rem; line-height: 2;"';
  }
?>
@extends('site.layouts.default', $extendData)

@section('title', $title)

@section('content')

<div class="row">
  <div class="col">

    <?php 
      $breadcrumb = array();
      foreach($post->types as $value) {
        $breadcrumb[] = ['name' => $value->name, 'link' => CommonUrl::getUrlPostType($value->slug)];
      }
      $breadcrumb[] = ['name' => $post->name, 'link' => url($post->slug)];
      $breadcrumb[] = ['name' => $data->name, 'link' => ''];
    ?>
    @include('site.common.breadcrumb', $breadcrumb)

    <h1 class="my-3 text-center">{!! $h1 !!}</h1>

    @if(!empty($post->name2))
      <div class="mb-2 text-muted text-center">{!! $post->name2 !!}</div>
    @endif

    <div class="text-center mb-3 d-flex justify-content-center align-items-center">
      @if(isset($data->epPrev))
        <a href="{!! CommonUrl::getUrl2($post->slug, $data->epPrev->slug) !!}" class="btn btn-primary m-2" rel="prev"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
      @else
        <a class="btn btn-secondary m-2 disabled"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
      @endif

      {!! Form::select(null, $post->epchapArray, CommonUrl::getUrl2($post->slug, $data->slug), array('class' =>'custom-select m-2', 'style'=>'width:200px;', 'onchange'=>'javascript:location.href = this.value;')) !!}

      @if(isset($data->epNext))
        <a href="{!! CommonUrl::getUrl2($post->slug, $data->epNext->slug) !!}" class="btn btn-primary m-2" rel="next"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
      @else
        <a class="btn btn-secondary m-2 disabled"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
      @endif
    </div>

    @include('site.common.ad', ['posPc' => 19, 'posMobile' => 20])

    @if(!empty($data->description))<div class="mb-3" {!! $themestext !!}>{!! $data->description !!}</div>@endif

    @include('site.common.ad', ['posPc' => 21, 'posMobile' => 22])

    <h2 class="mb-3 text-muted text-center">{!! $data->name !!}</h2>

    <div class="text-center mb-3 d-flex justify-content-center align-items-center">
      @if(isset($data->epPrev))
        <input type="hidden" id="prev" value="{!! CommonUrl::getUrl2($post->slug, $data->epPrev->slug) !!}">
        <a href="{!! CommonUrl::getUrl2($post->slug, $data->epPrev->slug) !!}" class="btn btn-primary m-2" rel="prev"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
      @else
        <input type="hidden" id="prev" value="">
        <a class="btn btn-secondary m-2 disabled"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
      @endif

      {!! Form::select(null, $post->epchapArray, CommonUrl::getUrl2($post->slug, $data->slug), array('class' =>'custom-select m-2', 'style'=>'width:200px;', 'onchange'=>'javascript:location.href = this.value;')) !!}

      @if(isset($data->epNext))
        <input type="hidden" id="next" value="{!! CommonUrl::getUrl2($post->slug, $data->epNext->slug) !!}">
        <a href="{!! CommonUrl::getUrl2($post->slug, $data->epNext->slug) !!}" class="btn btn-primary m-2" rel="next"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
      @else
        <input type="hidden" id="next" value="">
        <a class="btn btn-secondary m-2 disabled"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
      @endif
    </div>

    <div class="mb-3 text-center" id="errormessage">
      <div class="spinner ml-2"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>
      <button class="btn btn-danger btn-sm" onclick="errorreporting()" id="errorreporting"><i class="fa fa-exclamation-triangle mr-2" aria-hidden="true"></i>Báo lỗi chương</button>
    </div>

    <div class="social mb-4">
      <div class="fb-like" data-share="true" data-show-faces="false" data-layout="button_count"></div>
    </div>

    <div class="comment mb-5">
      <div class="fb-comments" data-numposts="10" data-colorscheme="dark" data-width="100%" data-href="{!! url($post->slug) !!}"></div>
    </div>

    <script>
      function errorreporting() {
        $.ajax(
        {
          type: 'post',
          url: '{!! url("errorreporting") !!}',
          data: {
            'url': window.location.href
          },
          beforeSend: function() {
            $('#errorreporting').attr('style', 'display:none');
            $('.spinner').attr('style', 'display:inline-block');
          },
          success: function()
          {
            $('.spinner').attr('style', 'display:none');
            $('#errormessage').html('<span class="badge badge-pill badge-success">Báo lỗi thành công! Cảm ơn bạn rất nhiều!</span>');
            return false;
          },
          error: function(xhr)
          {
            $('.spinner').attr('style', 'display:none');
            $('#errormessage').html('<span class="badge badge-pill badge-success">Báo lỗi thành công! Cảm ơn bạn rất nhiều!</span>');
            return false;
          }
        });
      }
      $(function () {
        var prev = document.getElementById('prev').value;
        var next = document.getElementById('next').value;
        document.onkeydown = function(e) {
          switch (e.keyCode) {
            case 37:
              window.location.href = prev;
              break;
            case 39:
              window.location.href = next;
              break;
          }
        };
        $(window).scroll(function() {
          if ($(this).scrollTop() == 0) {
            document.getElementById('themes').style.display='block';
          } else {
            document.getElementById('themes').style.display='none';
          }
        });
        $('#themesbtn').click(function() {
          document.getElementById('themesbtn').style.display='none';
          document.getElementById('themesbox').style.display='block';
          return false;
        });
        $('#themesboxbtn').click(function() {
          document.getElementById('themesbtn').style.display='block';
          document.getElementById('themesbox').style.display='none';
          return false;
        });
        $("#themescolor").change(function(event) {
          var themescolor = document.getElementById('themescolor').value;
          document.getElementsByTagName("body")[0].className=themescolor;
          setCookie('themescolor', themescolor, 3650);
          return false;
        });
        $("#themesfontsize").change(function(event) {
          var themesfontsize = document.getElementById('themesfontsize').value;
          document.getElementsByClassName("mb-3")[1].style.fontSize=themesfontsize;
          setCookie('themesfontsize', themesfontsize, 3650);
          return false;
        });
        $("#themeslineheight").change(function(event) {
          var themeslineheight = document.getElementById('themeslineheight').value;
          document.getElementsByClassName("mb-3")[1].style.lineHeight=themeslineheight;
          setCookie('themeslineheight', themeslineheight, 3650);
          return false;
        });
        $("#themesmenu").change(function(event) {
          if (event.target.checked) {
            document.getElementsByTagName("header")[0].style.display='none';
            document.getElementsByTagName("footer")[0].style.display='none';
            document.getElementsByTagName("ol")[0].style.display='none';
            setCookie('themesmenu', 1, 3650);
          } else {
            document.getElementsByTagName("header")[0].style.display='block';
            document.getElementsByTagName("footer")[0].style.display='block';
            document.getElementsByTagName("ol")[0].style.display='block';
            document.cookie = "themesmenu=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
          }
          return false;
        });

      })
      function setCookie(cname,cvalue,exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires=" + d.toGMTString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
      }
    </script>
  </div>
</div>

<div id="themes" class="animated fadeInLeft">
  <button id="themesbtn" class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="Tùy chỉnh văn bản"><i class="fa fa-cogs" aria-hidden="true"></i></button>
  <div id="themesbox" class="p-3 animated bounceInLeft">
    <select id="themescolor" class="custom-select mb-3">
      <option value="themelight" @if($themescolor == 'themelight') selected="selected" @endif>Nền Sáng</option>
      <option value="themedark" @if($themescolor == 'themedark') selected="selected" @endif>Nền Tối</option>
      <option value="themegray" @if($themescolor == 'themegray') selected="selected" @endif>Nền Xám</option>
    </select>
    <select id="themesfontsize" class="custom-select mb-3">
      <option value="1.000rem" @if($themesfontsize == '1.000rem') selected="selected" @endif>Cỡ chữ 16</option>
      <option value="1.125rem" @if($themesfontsize == '1.125rem') selected="selected" @endif>Cỡ chữ 18</option>
      <option value="1.250rem" @if($themesfontsize == '1.250rem') selected="selected" @endif>Cỡ chữ 20</option>
      <option value="1.375rem" @if($themesfontsize == '1.375rem') selected="selected" @endif>Cỡ chữ 22</option>
      <option value="1.500rem" @if($themesfontsize == '1.500rem') selected="selected" @endif>Cỡ chữ 24</option>
      <option value="1.625rem" @if($themesfontsize == '1.625rem') selected="selected" @endif>Cỡ chữ 26</option>
      <option value="1.750rem" @if($themesfontsize == '1.750rem') selected="selected" @endif>Cỡ chữ 28</option>
    </select>
    <select id="themeslineheight" class="custom-select mb-3">
      <option value="2" @if($themeslineheight == '2') selected="selected" @endif>Cách dòng 2</option>
      <option value="2.2" @if($themeslineheight == '2.2') selected="selected" @endif>Cách dòng 2.2</option>
      <option value="2.4" @if($themeslineheight == '2.4') selected="selected" @endif>Cách dòng 2.4</option>
      <option value="2.6" @if($themeslineheight == '2.6') selected="selected" @endif>Cách dòng 2.6</option>
      <option value="2.8" @if($themeslineheight == '2.8') selected="selected" @endif>Cách dòng 2.8</option>
    </select>
    <label class="custom-control custom-checkbox mb-3 d-flex align-items-center">
      <input type="checkbox" id="themesmenu" class="custom-control-input" @if(isset($themesmenu)) checked="checked" @endif>
      <span class="custom-control-indicator"></span>
      <span class="custom-control-description">Ẩn Menu</span>
    </label>
    <button id="themesboxbtn" class="btn btn-success"><i class="fa fa-close mr-2" aria-hidden="true"></i>Đóng lại</button>
  </div>
</div>

@endsection