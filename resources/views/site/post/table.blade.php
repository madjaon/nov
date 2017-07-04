@if(!empty($data))
<div class="row">
  <div class="col">
    <table class="table table-hover">
      <tbody>
        @foreach($data as $value)
        <?php 
          $url = url($value->slug);
          $url2 = CommonUrl::getUrl2($value->slug, $value->ep_slug) ;
          if($value->ep_volume > 0) {
            $epchap = 'Quyển ' . $value->ep_volume . ' chương ' . $value->ep_epchap;
          } else {
            $epchap = 'Chương ' . $value->ep_epchap;
          }
        ?>
        <tr>
          <td>
            <h3 class="my-0 table-item-title"><a href="{!! $url !!}" title="{!! $value->name !!}">{!! $value->name !!}</a></h3>
          </td>
          <td><a href="{!! $url2 !!}" title="{!! $value->ep_name !!}" class="table-item-epchap">{!! $epchap !!}</a></td>
          <td class="table-item-time">{!! CommonMethod::time_elapsed_string($value->ep_start_date) !!}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endif