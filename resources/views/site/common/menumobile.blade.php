<div class="menubox animated zoomInDownNew">
  <div class="list-group">
    <a class="list-group-item list-group-item-success text-white" title="Đóng Menu" onclick="document.getElementById('menumobile').style.display='none'"><i class="fa fa-window-close mr-2" aria-hidden="true"></i>Đóng Menu</a>
    <a href="{!! url('/') !!}" class="list-group-item list-group-item-action" title="Trang chủ"><i class="fa fa-home mr-2" aria-hidden="true"></i>Trang chủ</a>
    <a href="{!! CommonUrl::getUrlPostKind('da-hoan-thanh') !!}" class="list-group-item list-group-item-action" title="Danh sách truyện đã hoàn thành"><i class="fa fa-check-square-o mr-2" aria-hidden="true"></i>Danh sách truyện đã hoàn thành</a>
    <a href="{!! CommonUrl::getUrlPostKind('con-tiep-tuc') !!}" class="list-group-item list-group-item-action" title="Danh sách truyện còn tiếp tục"><i class="fa fa-square-o mr-2" aria-hidden="true"></i>Danh sách truyện còn tiếp tục</a>
    <a href="{!! url('tac-gia') !!}" class="list-group-item list-group-item-action" title="Danh sách tác giả"><i class="fa fa-user-circle-o mr-2" aria-hidden="true"></i>Danh sách tác giả</a>
    @foreach($data as $value)
      <a href="{!! CommonUrl::getUrlPostType($value->slug) !!}" class="list-group-item list-group-item-action" title="Thể loại {!! $value->name !!}">{!! $value->name !!}</a>
    @endforeach
    <a class="list-group-item list-group-item-success text-white" title="Đóng Menu" onclick="document.getElementById('menumobile').style.display='none'"><i class="fa fa-window-close mr-2" aria-hidden="true"></i>Đóng Menu</a>
  </div>
</div>
