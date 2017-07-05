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
  $image = ($post->image)?CommonMethod::getThumbnail($post->image, 3):'/img/noimage320x420.jpg';
?>
@extends('site.layouts.master', $extendData)

@section('title', $title)

@section('content')

<?php 
  $breadcrumb = array();
  foreach($post->types as $value) {
    $breadcrumb[] = ['name' => $value->name, 'link' => CommonUrl::getUrlPostType($value->slug)];
  }
  $breadcrumb[] = ['name' => $h1, 'link' => ''];
?>
@include('site.common.breadcrumb', $breadcrumb)

<div class="row book mb-4">
  <div class="col-sm-4">

    <img src="{!! url($image) !!}" class="img-thumbnail img-fluid rounded mb-4 w-100" alt="{!! $post->name !!}">

    <div class="social mb-4">
      <div class="fb-like" data-share="true" data-show-faces="false" data-layout="button_count"></div>
    </div>
    
  </div>
  <div class="col-sm">

    <h1 class="mb-2">{!! $h1 !!}</h1>

    @if(!empty($post->name2))
      <div class="mb-2 text-muted">{!! $post->name2 !!}</div>
    @endif

    <?php 
      if($post->kind == SLUG_POST_KIND_UPDATING) {
        $badge = 'primary';
      } else {
        $badge = 'success';
      }
    ?>
    <div class="book-epchap mb-3"><span class="badge badge-{!! $badge !!} p-2">
        {!! $post->kindName !!}
    </span></div>
   
    <div class="book-info mb-3">Mới nhất: 
      @if(!empty($post->epchap))
        {!! $post->epchap !!}
      @else 
        Không rõ
      @endif
    </div>

    <div class="book-info mb-3">Quốc Gia: 
      @if(!empty($post->nation))
        <a href="{!! CommonUrl::getUrlPostNation($post->nation) !!}" title="Đọc truyện {!! $post->nationName !!}">{!! $post->nationName !!}</a>
      @else 
        Không rõ
      @endif
    </div>

    <div class="book-info mb-3">Tác Giả: 
      @if(!empty($post->tags))
        @foreach($post->tags as $key => $value)
          <?php echo ($key > 0)?' - ':''; ?><a href="{!! CommonUrl::getUrlPostTag($value->slug) !!}" title="Đọc truyện của {!! $value->name !!}">{!! $value->name !!}</a>
        @endforeach
      @else
        Không rõ
      @endif
    </div>

    <div class="book-info mb-3">Thể Loại: 
      @foreach($post->types as $key => $value)
        <?php echo ($key > 0)?' - ':''; ?><a href="{!! CommonUrl::getUrlPostType($value->slug) !!}" title="Đọc truyện thể loại {!! $value->name !!}">{!! $value->name !!}</a>
      @endforeach
    </div>

    <div class="book-info mb-3">Nguồn: 
      @if(!empty($post->source))
        {!! $post->source !!}
      @else 
        Không rõ
      @endif
    </div>

    @if(isset($post->epFirst) || isset($post->epLast))
      @if(($post->epFirst->id == $post->epLast->id) || $post->type == POST_SHORT)
        <div class="row">
          <div class="col-sm-6">
            <a class="btn btn-danger mb-3 w-100 book-full" href="{!! CommonUrl::getUrl2($post->slug, $post->epFirst->slug) !!}">Đọc Ngay</a>
          </div>
        </div>
      @else
        <div class="row">
          <div class="col">
            <a class="btn btn-info mb-3 w-100 book-first" href="{!! CommonUrl::getUrl2($post->slug, $post->epFirst->slug) !!}">Đọc Từ Chương Đầu</a>
          </div>
          <div class="col">
            <a class="btn btn-danger mb-3 w-100 book-last" href="{!! CommonUrl::getUrl2($post->slug, $post->epLast->slug) !!}">Đọc Chương Mới Nhất</a>
          </div>
        </div>
      @endif
    @else
    <div class="row">
      <div class="col-sm-6">
        <a class="btn btn-secondary mb-3 w-100 book-comming">Truyện Sắp Có Nhé</a>
      </div>
    </div>
    @endif

  </div>
</div>

<div class="row">
  <div class="col">

    @include('site.common.ad', ['posPc' => 15, 'posMobile' => 16])

    @if(!empty($post->epsLastest))
    <div class="mb-3">
      <h3>Chương mới nhất</h3>
      <blockquote class="blockquote">
        <ul class="list-unstyled">
          @foreach($post->epsLastest as $value)
            <li class="blur">
              <a href="{!! CommonUrl::getUrl2($post->slug, $value->slug) !!}" title="{!! $value->name !!}"><i class="fa fa-dot-circle-o mr-2" aria-hidden="true"></i>{!! $value->name !!}</a>
            </li>
          @endforeach
        </ul>
      </blockquote>
    </div>
    @endif

    @if(!empty($post->eps))
      <div class="card card-outline-info mb-3" id="booklistbox">
        <h3 class="card-header">Danh sách chương<div class="spinner ml-2"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></h3>
        <div class="card-block" id="booklist">
          @include('site.post.booklist')
        </div>
      </div>
      @if($post->totalPageEps > 1)
      <script>
        function bookpaging(page, p)
        {
          $.ajax(
          {
            type: 'post',
            url: '{{ url("bookpaging") }}',
            data: {
              'id': {{ $post->id }},
              'page': page,
              '_token': '{{ csrf_token() }}'
            },
            beforeSend: function() {
              scrollTo();
              $('.spinner').attr('style', 'display:inline-block');
            },
            success: function(data)
            {
              $('.spinner').attr('style', 'display:none');
              $('#booklist').html(data);
            },
            error: function(xhr)
            {
              $('.spinner').attr('style', 'display:none');
              // $('#booklist').html(xhr);
            }
          });
        }
        function scrollTo() {
          $('html, body').animate({ scrollTop: $('#booklistbox').offset().top }, 'fast');
          return false;
        }
      </script>
      @endif
    @endif

    @if(!empty($post->patterns))<div class="description mb-3">{!! $post->patterns !!}</div>@endif
    @if(!empty($post->summary))<div class="description mb-3">{!! $post->summary !!}</div>@endif
    @if(!empty($post->description))<div class="description mb-3">{!! $post->description !!}</div>@endif

    @if(!empty($post->seriInfo))
      <div class="seri mb-3">Seri: <a href="{!! CommonUrl::getUrlPostSeri($post->seriInfo->slug) !!}" title="Seri truyện {!! $post->seriInfo->name !!}">{!! $post->seriInfo->name !!}</a></div>
      @if(!empty($post->seriData))
      <blockquote class="blockquote">
        <ul class="list-unstyled">
          @foreach($post->seriData as $value)
          <?php 
            $url = url($value->slug);
            $kind = CommonOption::getKindPost($value->kind);
            if($value->kind == SLUG_POST_KIND_UPDATING) {
              $badge = 'primary';
            } else {
              $badge = 'success';
            }
          ?>
            <li class="blur">
              <a href="{!! $url !!}" title="{!! $value->name !!}"><i class="fa fa-angle-right mr-2" aria-hidden="true"></i>{!! $value->name !!}<span class="badge badge-{!! $badge !!} ml-2 align-middle hidden-xs-down">{!! $kind !!}</span></a>
            </li>
          @endforeach
        </ul>
      @endif
      </blockquote>
    @endif

    @include('site.common.ad', ['posPc' => 17, 'posMobile' => 18])
    
    <div class="comment mb-5">
      <div class="fb-comments" data-numposts="10" data-colorscheme="dark" data-width="100%" data-href="{!! url($post->slug) !!}"></div>
    </div>

  </div>
</div>

@endsection