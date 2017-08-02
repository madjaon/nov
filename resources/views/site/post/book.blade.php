<?php 
  $h1 = $post->h1;
  $title = ($post->meta_title!='')?$post->meta_title:$h1;
  $extendData = array(
    'meta_title' => $post->meta_title,
    'meta_keyword' => $post->meta_keyword,
    'meta_description' => $post->meta_description,
    'meta_image' => $post->meta_image,
    'book_release_date' => $post->book_release_date,
    'book_author' => $post->book_author,
    'book_tag' => $post->book_tag,
    'isPost' => true,
    'isBook' => true
  );
  $image = ($post->image)?CommonMethod::getThumbnail($post->image, 3):'/img/noimage320x420.jpg';
  $ratingCookieName = 'rating' . $post->id;
  $ratingCookie = isset($_COOKIE[$ratingCookieName])?$_COOKIE[$ratingCookieName]:null;
  $ratingValue = round($post->rating_value, 1, PHP_ROUND_HALF_UP);
  $ratingValueChecked = round($ratingValue, 0, PHP_ROUND_HALF_DOWN);
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

<div class="row book mb-3" itemscope itemtype="http://schema.org/Book">
  <div class="col-sm-4">

    <img src="{!! url($image) !!}" class="img-thumbnail img-fluid rounded mb-3 w-100" alt="{!! $post->name !!}" itemprop="image">

    <div class="social mb-3">
      <div class="fb-like" data-share="true" data-show-faces="false" data-layout="button_count"></div>
    </div>
    
  </div>
  <div class="col-sm">

    <h1 class="mb-3" itemprop="name">{!! $h1 !!}</h1>

    @if(!empty($post->name2))
      <div class="mb-3 text-muted">{!! $post->name2 !!}</div>
    @endif

    <?php 
      if($post->kind == SLUG_POST_KIND_UPDATING) {
        $badge = 'primary';
      } else {
        $badge = 'success';
      }
    ?>
    <div class="book-info mb-3"><span class="badge badge-{!! $badge !!}">
        {!! $post->kindName !!}
    </span></div>
   
    <div class="book-info mb-3">Mới nhất: 
      @if(!empty($post->epLast))
        <?php 
          if($post->epLast->volume > 0) {
            $epchap = 'Quyển ' . $post->epLast->volume . ' chương ' . $post->epLast->epchap;
          } else {
            $epchap = 'Chương ' . $post->epLast->epchap;
          }
        ?>
        {!! $epchap !!}
      @else 
        Cập nhật
      @endif
    </div>

    <div class="book-info mb-3">Quốc Gia: 
      @if(!empty($post->nation))
        <a href="{!! CommonUrl::getUrlPostNation($post->nation) !!}" title="{!! $post->nationName !!}">{!! $post->nationName !!}</a>
      @else 
        Không rõ
      @endif
    </div>

    <div class="book-info mb-3">Tác Giả: 
      @if(!empty($post->tags))
        @foreach($post->tags as $key => $value)
          <?php echo ($key > 0)?' - ':''; ?><a href="{!! CommonUrl::getUrlPostTag($value->slug) !!}" title="{!! $value->name !!}" itemprop="author">{!! $value->name !!}</a>
        @endforeach
      @else
        Không rõ
      @endif
    </div>

    <div class="book-info mb-3">Thể Loại: 
      @foreach($post->types as $key => $value)
        <?php echo ($key > 0)?' - ':''; ?><a href="{!! CommonUrl::getUrlPostType($value->slug) !!}" title="{!! $value->name !!}" itemprop="genre">{!! $value->name !!}</a>
      @endforeach
    </div>

    {{--<div class="book-info mb-3">Nguồn: 
      @if(!empty($post->source))
        {!! $post->source !!}
      @else 
        Không rõ
      @endif
    </div>--}}

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

  <div class="col-12">
    <div class="d-block d-sm-flex justify-content-center align-items-center text-center">
      <div class="d-flex justify-content-center align-items-center mr-3 mb-0" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
        <em><span id="ratingValue" itemprop="ratingValue">{!! $ratingValue !!}</span> điểm / <span id="ratingCount" itemprop="ratingCount">{!! $post->rating_count !!}</span> lượt đánh giá</em>
        <meta itemprop="bestRating" content="10">
        <meta itemprop="worstRating" content="1">
      </div>
      <form class="d-flex justify-content-center align-items-center" name="ratingfrm">
        <fieldset class="starability-growRotate">
          <input type="radio" id="growing-rate1" name="rating" value="1" @if(isset($ratingCookie)) disabled @endif @if($ratingValueChecked == 1) checked @endif>
          <label for="growing-rate1" title="Quá tệ hại">1 star</label>
          <input type="radio" id="growing-rate2" name="rating" value="2" @if(isset($ratingCookie)) disabled @endif @if($ratingValueChecked == 2) checked @endif>
          <label for="growing-rate2" title="Tốn thời gian">2 stars</label>
          <input type="radio" id="growing-rate3" name="rating" value="3" @if(isset($ratingCookie)) disabled @endif @if($ratingValueChecked == 3) checked @endif>
          <label for="growing-rate3" title="Không thể hiểu">3 stars</label>
          <input type="radio" id="growing-rate4" name="rating" value="4" @if(isset($ratingCookie)) disabled @endif @if($ratingValueChecked == 4) checked @endif>
          <label for="growing-rate4" title="Thiếu gia vị">4 stars</label>
          <input type="radio" id="growing-rate5" name="rating" value="5" @if(isset($ratingCookie)) disabled @endif @if($ratingValueChecked == 5) checked @endif>
          <label for="growing-rate5" title="Cũng tàm tạm">5 stars</label>
          <input type="radio" id="growing-rate6" name="rating" value="6" @if(isset($ratingCookie)) disabled @endif @if($ratingValueChecked == 6) checked @endif>
          <label for="growing-rate6" title="Cũng được">6 stars</label>
          <input type="radio" id="growing-rate7" name="rating" value="7" @if(isset($ratingCookie)) disabled @endif @if($ratingValueChecked == 7) checked @endif>
          <label for="growing-rate7" title="Khá hay">7 stars</label>
          <input type="radio" id="growing-rate8" name="rating" value="8" @if(isset($ratingCookie)) disabled @endif @if($ratingValueChecked == 8) checked @endif>
          <label for="growing-rate8" title="Cực hay">8 stars</label>
          <input type="radio" id="growing-rate9" name="rating" value="9" @if(isset($ratingCookie)) disabled @endif @if($ratingValueChecked == 9) checked @endif>
          <label for="growing-rate9" title="Siêu phẩm">9 stars</label>
          <input type="radio" id="growing-rate10" name="rating" value="10" @if(isset($ratingCookie)) disabled @endif @if($ratingValueChecked == 10) checked @endif>
          <label for="growing-rate10" title="Kiệt tác">10 stars</label>
        </fieldset>
        @push('starability')
          <link rel="stylesheet" href="{!! asset('css/starability.css') !!}">
        @endpush
      </form>
      @if(!isset($ratingCookie))
        @push('book')
          <script src="{!! asset('js/book.js') !!}"></script>
        @endpush
      @endif
    </div>
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
        @push('bookpaging')
          <script src="{!! asset('js/bookpaging.js') !!}"></script>
        @endpush
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

<input type="hidden" id="postId" value="{!! $post->id !!}">

@endsection