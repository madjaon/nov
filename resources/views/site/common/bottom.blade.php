<?php 
  if(isset($_COOKIE['themesmenu'])) {
    $themesmenuDisplay = 'style=display:none;';
  } else {
    $themesmenuDisplay = '';
  }
?>
<footer {!! $themesmenuDisplay !!}>
  <div class="container">
    @if($configcredit)
      <div class="my-3 hidden-xs-down">{!! $configcredit !!}</div>
    @endif
    <p>© MMXVII / <span class="made-with-love">Made with ❤ Books</span> / <a href="{!! url('/contact') !!}" title="Contact">Contact</a> / <a href="{!! url('/privacy-policy') !!}" title="Privacy policy">Privacy policy</a> / <a href="{!! url('/terms-of-use') !!}" title="Terms of use">Terms of use</a> / <a href="#" title="Lên đầu trang" rel="nofollow">Lên đầu trang</a></p>
  </div>
</footer>
