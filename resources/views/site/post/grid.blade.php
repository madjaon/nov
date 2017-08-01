@if(!empty($data))
<div class="row">
  @foreach($data as $key => $value)
    <?php 
      $url = url($value->slug);
      $image = ($value->image)?CommonMethod::getThumbnail($value->image, 1):'/img/noimage185x240.jpg';
      $kind = CommonOption::getKindPost($value->kind);
      if($value->kind == SLUG_POST_KIND_UPDATING) {
        $badge = 'primary';
      } else {
        $badge = 'success';
      }
    ?>
    <div class="col-6 col-sm-2">
      <figure class="figure text-center grid-item">
        <a href="{!! $url !!}" title="{!! $value->name !!}" class="d-block">
          <img src="{!! url($image) !!}" class="figure-img img-thumbnail img-fluid rounded" alt="{!! $value->name !!}">
          <span class="badge badge-{!! $badge !!}">{!! $kind !!}</span>
          <span class="badge badge-warning">{!! $value->view !!} lượt đọc</span>
        </a>
        <figcaption class="figure-caption">
          <a href="{!! $url !!}" title="{!! $value->name !!}">{!! $value->name !!}</a>
        </figcaption>
      </figure>
    </div>
  @endforeach
</div>
@else
<div class="alert alert-warning" role="alert">
  <strong>Chú ý!</strong> Đang cập nhật dữ liệu. Mời bạn quay lại sau!
</div>
@endif