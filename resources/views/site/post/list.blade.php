@if(!empty($data))
<div class="row">
  <div class="col">
    <ul class="list-unstyled">
      @foreach($data as $value)
      <?php 
        $url = url($value->slug);
        $url2 = CommonUrl::getUrl2($value->slug, $value->ep_slug) ;
        $image = ($value->image)?CommonMethod::getThumbnail($value->image, 2):'/img/noimage80x80.jpg';
        if($value->ep_volume > 0) {
          $epchap = 'Quyển ' . $value->ep_volume . ' chương ' . $value->ep_epchap;
        } else {
          $epchap = 'Chương ' . $value->ep_epchap;
        }
      ?>
      <li class="media mb-3 pb-3 list-item">
        <a href="{!! $url2 !!}" title="{!! $value->name !!}">
          <img class="d-flex mr-3 img-fluid rounded" src="{!! url($image) !!}" alt="{!! $value->name !!}">
        </a>
        <div class="media-body">
          <div class="row">
            <div class="col-9 col-sm-10">
              <h3 class="mt-0 mb-1 list-item-title"><a href="{!! $url !!}" title="{!! $value->name !!}">{!! $value->name !!}</a></h3>
              <p class="mb-1 list-item-epchap hidden-xs-down">{!! $value->ep_name !!}</p>
              <span class="badge badge-primary">{!! $epchap !!}</span>
              <small class="ml-0 ml-sm-2 mt-2 mt-sm-0 d-block d-sm-inline-block">{!! CommonMethod::time_elapsed_string($value->ep_start_date) !!}</small>
            </div>
            <div class="col-3 col-sm-2">
              <a href="{!! $url2 !!}" title="{!! $value->ep_name !!}" class="mt-2 p-1 p-sm-2 float-right list-item-read">Chương<span class="d-block">{!! $value->ep_epchap !!}</span></a>
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