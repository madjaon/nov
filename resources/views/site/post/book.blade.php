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
  foreach($types as $value) {
    $breadcrumb[] = ['name' => $value->name, 'link' => CommonUrl::getUrlPostType($value->slug)];
  }
  $breadcrumb[] = ['name' => $h1, 'link' => ''];
?>
@include('site.common.breadcrumb', $breadcrumb)

<div class="row book mb-4">
  <div class="col-sm-4">
    <img src="{!! url($image) !!}" class="img-fluid rounded mb-4 w-100" alt="{!! $post->name !!}">
    @include('site.common.social')
  </div>
  <div class="col-sm">

    <h1 class="mb-2">{!! $h1 !!}</h1>

    @if(!empty($post->name2))
      <div class="mb-2 text-muted">{!! $post->name2 !!}</div>
    @endif

    <div class="book-epchap mb-3"><span class="badge badge-primary p-2">
      @if(!empty($post->epchap))
        Chương {!! $post->epchap !!}
      @else
        {!! $post->kindName !!}
      @endif    
    </span></div>
   
    <div class="book-info mb-3">Quốc Gia: 
      @if(!empty($post->nation))
        <a href="{!! CommonUrl::getUrlPostNation($post->nation) !!}" title="Đọc truyện {!! $post->nationName !!}">{!! $post->nationName !!}</a>
      @else 
        Không rõ
      @endif
    </div>

    <div class="book-info mb-3">Tác Giả: 
      @if(!empty($tags))
        @foreach($tags as $key => $value)
          <?php echo ($key > 0)?' - ':''; ?><a href="{!! CommonUrl::getUrlPostTag($value->slug) !!}" title="Đọc truyện của {!! $value->name !!}">{!! $value->name !!}</a>
        @endforeach
      @else
        Không rõ
      @endif
    </div>

    <div class="book-info mb-3">Thể Loại: 
      @foreach($types as $key => $value)
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

    @if(!empty($post->patterns))<div class="description mb-3">{!! $post->patterns !!}</div>@endif
    @if(!empty($post->summary))<div class="description mb-3">{!! $post->summary !!}</div>@endif
    @if(!empty($post->description))<div class="description mb-3">{!! $post->description !!}</div>@endif

    @if(!empty($post->seriInfo))
      <div class="seri mb-4">Seri: <a href="{!! CommonUrl::getUrlPostSeri($post->seriInfo->slug) !!}" title="Xem seri anime {!! $post->seriInfo->name !!}">{!! $post->seriInfo->name !!}</a></div>
      @if(!empty($post->seriData))
        <table class="table table-bordered mb-4">
          <thead>
            <tr>
              <th><a href="{!! CommonUrl::getUrlPostSeri($post->seriInfo->slug) !!}" title="Xem seri anime {!! $post->seriInfo->name !!}">Danh sách phim cùng seri {!! $post->seriInfo->name !!}</a></th>
              <th>Tình trạng</th>
            </tr>
          </thead>
          <tbody>
            @foreach($post->seriData as $value)
              <tr>
                <td>
                  <div><a href="{!! url($value->slug) !!}" title="{!! $value->name !!}">{!! $value->name !!}</a></div>
                  @if(!empty($value->name2))
                    <div class="text-muted">{!! $value->name2 !!}</div>
                  @endif
                </td>
                <td>{!! CommonOption::getKindPost($value->kind) !!}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    @endif

    @include('site.common.ad', ['posPc' => 17, 'posMobile' => 18])
    
    <div class="comment mb-5">
      <div class="fb-comments" data-numposts="10" data-colorscheme="dark" data-width="100%" data-href="{!! url($post->slug) !!}"></div>
    </div>

  </div>
</div>

@endsection