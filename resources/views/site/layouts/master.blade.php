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

@if(getDevice2() == PC && (CommonQuery::checkAdByPosition(5) || CommonQuery::checkAdByPosition(6)))
  <div class="scrollSticky" id="scrollLeft" data-top="110">
    @include('site.common.ad', ['posPc' => 5])
  </div>
  <div class="scrollSticky" id="scrollRight" data-top="110">
    @include('site.common.ad', ['posPc' => 6])
  </div>
  <script>
    var scrollWidth = 160;
    checkPos($(window).width());
    $(function () {
      $(window).resize(function () {
        checkPos($(window).width());
      });
    });
    function checkPos(windowWidth) {
      var posLeft = (windowWidth - 1000) / 2 - scrollWidth - 3;
      var posRight = (windowWidth - 1000) / 2 - scrollWidth + 1;
      if (windowWidth < 1300) {
        $('.scrollSticky').hide();
      } else {
        $('.scrollSticky').show();
        $("#scrollRight").css({ top: 110, right: posRight, position: "absolute",display:"block" });
        $("#scrollLeft").css({ top: 110, left: posLeft, position: "absolute",display:"block" });
      }
    }
    $(document).scroll(function () {
      var scrollTop = $(document).scrollTop();
      $('#scrollLeft').each(function () {
        var $scroll = $(this);
        var parentTop = parseInt($scroll.attr('data-top'));
        if (scrollTop > parentTop) {
          $scroll.css('top', scrollTop - parentTop + parseInt($scroll.attr('data-top')) + 10);
        } else {
          $scroll.css('top', parseInt($scroll.attr('data-top')));
        }
      });
      $('#scrollRight').each(function () {
        var $scroll = $(this);
        var parentTop = parseInt($scroll.attr('data-top'));
        if (scrollTop > parentTop) {
          $scroll.css('top', scrollTop - parentTop + parseInt($scroll.attr('data-top')) + 10);
        } else {
          $scroll.css('top', parseInt($scroll.attr('data-top')));
        }
      });
    });
  </script>
@endif

</body>
</html>
