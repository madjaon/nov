@if(!empty($data))
<div class="row">
  <div class="col">
    <ul class="list-unstyled">
      @foreach($data as $key => $value)
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
      <li class="media mb-3 pb-3 list-item">
        <a href="{!! $url !!}" title="{!! $value->name !!}">
          <img class="d-flex mr-3 img-fluid rounded" src="{!! url($image) !!}" alt="{!! $value->name !!}">
        </a>
        <div class="media-body">
          <div class="row">
            <div class="col">
              <h2 class="mt-0 mb-2 list-item-title"><a href="{!! $url !!}" title="{!! $value->name !!}">{!! $value->name !!}</a></h2>
              <div class="mb-2 d-flex align-items-center">
                <span class="badge badge-{!! $badge !!}">{!! $kind !!}</span>
                @if(!empty($value->epchap))
                <small class="ml-2">Mới nhất: {!! $value->epchap !!}</small>
                @endif
              </div>
              @if(!empty($authors[$key]))
              <div class="authors">Tác giả: {!! $authors[$key] !!}</div>
              @endif
            </div>
          </div>
        </div>
      </li>
      @endforeach
    </ul>
  </div>
</div>
@else
<div class="alert alert-warning" role="alert">
  <strong>Chú ý!</strong> Đang cập nhật dữ liệu. Mời bạn quay lại sau!
</div>
@endif