@if(!empty($data))
<div class="row">
  @foreach($data as $key => $value)
    <?php 
      $url = url($value->slug);
      $image = ($value->image)?CommonMethod::getThumbnail($value->image, 1):'/img/noimage185x240.jpg';
    ?>
    <div class="col-6 col-sm-3">
      <figure class="figure text-center">
        <a href="{!! $url !!}">
          <img src="{!! url($image) !!}" class="figure-img img-fluid rounded" alt="{!! $value->name !!}">
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