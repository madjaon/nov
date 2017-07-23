@if(isset($data))
<div class="side mb-5 animated fadeInDownNew">
  <div class="card">
    <div class="card-block p-3">
      <h4 class="card-title">Bạn đã đọc<i class="fa fa-question-circle-o ml-2" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Được lưu vĩnh viễn cho tới khi lịch sử trên trình duyệt web bị xóa"></i></h4>
      <p class="card-text">
        <a href="{!! $data->url !!}" title="{!! $data->postName !!}" class="mr-2">{!! $data->postName !!}<br><span class="badge badge-primary d-inline-block">{!! $data->epchapName !!}</span></a>
      </p>
    </div>
  </div>
</div>
@endif