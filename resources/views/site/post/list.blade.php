@if(!empty($data))
<ul class="list-group">
  @foreach($data as $key => $value)
  <?php 
    $url = url($value->slug);
    if(isset($dataEp[$key])) {
      $url2 = CommonUrl::getUrl2($value->slug, $dataEp[$key]->slug) ;
      if($dataEp[$key]->volume > 0) {
        $epchap = 'Quyển ' . $dataEp[$key]->volume . ' chương ' . $dataEp[$key]->epchap;
      } else {
        $epchap = 'Chương ' . $dataEp[$key]->epchap;
      }
    }
  ?>
  <li class="list-group-item d-block list-item">
    <h3 class="d-block list-item-title">
      <a href="{!! $url !!}" title="{!! $value->name !!}">{!! $value->name !!}</a>
    </h3>
    @if(isset($dataEp[$key]))
      <div class="d-block">
        <a href="{!! $url2 !!}" title="{!! $dataEp[$key]->name !!}" class="d-inline-block"><span class="badge badge-primary badge-pill">{!! $epchap !!}</span></a>
        <small class="ml-2 d-inline-block">{!! CommonMethod::time_elapsed_string($dataEp[$key]->start_date) !!}</small>
      </div>
    @endif
  </li>
  @endforeach
</ul>
@else
<div class="alert alert-warning" role="alert">
  <strong>Chú ý!</strong> Đang cập nhật dữ liệu. Mời bạn quay lại sau!
</div>
@endif