@if(!empty($data))
<div class="row mt-4">
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
    <div class="col-12 col-sm-6">
      <div class="media mb-3 pb-3 list-item">
        <a href="{!! $url !!}" title="{!! $value->name !!}">
          <img class="d-flex mr-3 img-thumbnail img-fluid" src="{!! url($image) !!}" alt="{!! $value->name !!}">
        </a>
        <div class="media-body">
          <h2 class="mt-0 mb-2 list-item-title"><a href="{!! $url !!}" title="{!! $value->name !!}">{!! $value->name !!}</a></h2>
          @if(!empty($authors[$key]))
          <div class="mb-2 authors">Tác giả: {!! $authors[$key] !!}</div>
          @endif
          <div class="d-flex align-items-center">
            <span class="badge badge-{!! $badge !!}">{!! $kind !!}</span>
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>
@else
<div class="alert alert-warning" role="alert">
  <strong>Chú ý!</strong> Đang cập nhật dữ liệu. Mời bạn quay lại sau!
</div>
@endif