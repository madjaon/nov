@if(!empty($data))
<div class="row">
  <?php $objL = '{'; ?>
  @foreach($data as $key => $value)
    <?php 
      $url = url($value->slug);
      $image = ($value->image)?CommonMethod::getThumbnail($value->image, 1):'/img/img.jpg';
      $kind = CommonOption::getKindPost($value->kind);
      if($value->kind == SLUG_POST_KIND_UPDATING) {
        $badge = 'primary';
      } else {
        $badge = 'success';
      }
      if($key == count($data) - 1) {
        $objL .= 'L' . $value->id . ':"' . $value->summary . '"';
      } else {
        $objL .= 'L' . $value->id . ':"' . $value->summary . '",';
      }
    ?>
    <div class="col-6 col-sm-3">
      <figure class="figure text-center grid-item">
        <a href="{{ $url }}" class="showTip L{{ $value->id }}">
          <img src="{{ url($image) }}" class="figure-img img-thumbnail img-fluid rounded" alt="{{ $value->name }}">
          <span class="badge badge-{{ $badge }}">{{ $kind }}</span>
          <span class="badge badge-warning">{{ CommonMethod::numberFormatDot($value->view) }} lượt đọc</span>
        </a>
        <figcaption class="figure-caption">
          <a href="{{ $url }}" title="{{ $value->name }}">{{ $value->name }}</a>
        </figcaption>
      </figure>
    </div>
  @endforeach
  <?php $objL .= '}'; ?>
  <script>
    dw_Tooltip.defaultProps = {
      supportTouch: true
    }
    dw_Tooltip.content_vars = {{ $objL }}
  </script>
</div>
@else
<div class="alert alert-warning" role="alert">
  <strong>Chú ý!</strong> Đang cập nhật dữ liệu. Mời bạn quay lại sau!
</div>
@endif