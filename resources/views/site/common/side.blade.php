<!--history-->

@include('site.common.ad', ['posPc' => 11, 'posMobile' => 12])
<div class="side mb-5">
  <h2 class="mb-3">Xu Hướng <small class="text-muted">Top Trending</small></h2>
  <div class="trending">
    @if($configtoptrending)
    <ul class="list-unstyled">
      @foreach($configtoptrending as $value)
      <?php 
        $url = url($value->slug);
        $image = ($value->image)?CommonMethod::getThumbnail($value->image, 2):'/img/noimage80x80.jpg';
        $kind = CommonOption::getKindPost($value->kind);
        if($value->kind == SLUG_POST_KIND_UPDATING) {
          $badge = 'primary';
        } else {
          $badge = 'success';
        }
      ?>
      <li class="media mt-4">
        <a href="{!! $url !!}" title="{!! $value->name !!}">
          <img class="d-flex mr-3" src="{!! url($image) !!}" alt="{!! $value->name !!}">
        </a>
        <div class="media-body">
          <h3 class="mt-0 mb-1 side-item-title"><a href="{!! $url !!}" title="{!! $value->name !!}">{!! $value->name !!}</a></h3>
          <span class="badge badge-{!! $badge !!}">{!! $kind !!}</span>
        </div>
      </li>
      @endforeach
    </ul>
    @endif
  </div>
</div>
@include('site.common.ad', ['posPc' => 13, 'posMobile' => 14])
