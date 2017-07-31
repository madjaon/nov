@if(!empty($data))
<div class="row">
  <div class="col">
    <table class="table table-bordered table-striped">
      <tbody>
        @foreach($data as $key => $value)
        <?php 
          $url = url($value->slug);
          if(isset($dataEp[$key])) {
            $url2 = CommonUrl::getUrl2($value->slug, $dataEp[$key]->slug);
            if($dataEp[$key]->volume > 0) {
              $epchap = 'Quyển ' . $dataEp[$key]->volume . ' chương ' . $dataEp[$key]->epchap;
            } else {
              $epchap = 'Chương ' . $dataEp[$key]->epchap;
            }
          }
        ?>
        <tr>
          <td class="align-middle">
            <h3 class="my-0"><a href="{!! $url !!}" title="{!! $value->name !!}">{!! $value->name !!}</a></h3>
          </td>
          <td class="align-middle text-center">
            @if(isset($dataEp[$key]))
            <a href="{!! $url2 !!}" title="{!! $dataEp[$key]->name !!}">{!! $epchap !!}</a>
            @endif
          </td>
          <td class="align-middle text-center">
            @if(isset($dataEp[$key]))
            {!! CommonMethod::time_elapsed_string($dataEp[$key]->start_date) !!}
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endif