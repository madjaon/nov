@include('site.common.head')
<body>

@if(isset($isPost) && $isPost == true && isset($configfbappid) && $configfbappid != '')
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '{!! $configfbappid !!}',
      xfbml      : true,
      version    : 'v2.9'
    });
    FB.AppEvents.logPageView();
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
@endif

@include('site.common.top')

@include('site.common.ad', ['posPc' => 1, 'posMobile' => 2])

<div class="container">
  <div class="row">
    <div class="col-md-9">
      <div class="content">
        @yield('content')
      </div>
    </div>
    <div class="col-md-3">
      @include('site.common.side')
    </div>
  </div>
</div>

@include('site.common.ad', ['posPc' => 3, 'posMobile' => 4])

@include('site.common.bottom')

</body>
</html>
