@if($breadcrumb)
<ol class="breadcrumb px-0 py-2">
  <li class="breadcrumb-item"><a href="{!! url('/') !!}" title="Trang chủ">Trang chủ</a></li>
  @foreach($breadcrumb as $value)
    @if($value['link'])
      <li class="breadcrumb-item"><a href="{!! $value['link'] !!}" title="{!! $value['name'] !!}">{!! $value['name'] !!}</a></li>
    @else
      <li class="breadcrumb-item active">{!! $value['name'] !!}</li>
    @endif
  @endforeach
</ol>
@endif