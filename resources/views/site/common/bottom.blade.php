<footer>
  <div class="container">
    @if($configcredit)
      <div class="my-3 hidden-xs-down">{!! $configcredit !!}</div>
    @endif
    <p>© MMXVII / <span class="made-with-love">Made with ❤ Books</span> / <a href="{!! url('/contact') !!}">Contact</a> / <a href="{!! url('/privacy-policy') !!}">Privacy policy</a> / <a href="{!! url('/terms-of-use') !!}">Terms of use</a></p>
  </div>
</footer>
