@if(!empty($post->eps))
<div class="card card-outline-info mb-3">
  <h3 class="card-header">Danh sách chương</h3>
  <div class="card-block">
    <blockquote class="card-blockquote">
      <ul class="row list-unstyled">
        @foreach($post->eps as $value)
        <?php 
          if(getDevice() == PC) {
            $name = $value->name;
          } else {
            if($value->volume > 0) {
              $name = 'Q ' . $value->volume . ' chương ' . $value->epchap;
            } else {
              $name = 'Chương ' . $value->epchap;
            }
          }
        ?>
          <li class="col-6 blur">
            <a href="{!! CommonUrl::getUrl2($post->slug, $value->slug) !!}" title="{!! $value->name !!}"><i class="fa fa-angle-right mr-2" aria-hidden="true"></i>{!! $name !!}</a>
          </li>
        @endforeach
      </ul>
      <footer></footer>
    </blockquote>
  </div>
</div>
@endif