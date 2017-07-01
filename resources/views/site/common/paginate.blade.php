<?php
  // config
  $link_limit = 7; // maximum number of links (a little bit inaccurate, but will be ok for now)
?>
@if ($paginator->lastPage() > 1)
  <ul class="pagination justify-content-center mt-3">
    @if($paginator->currentPage() == 1)
    <li class="page-item disabled">
      <a class="page-link" href="#" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">Previous</span>
      </a>
    </li>
    @else
    <li class="page-item">
      <a class="page-link" href="{!! $paginator->url(1) !!}" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">Previous</span>
      </a>
    </li>
    @endif
    @for ($i = 1; $i <= $paginator->lastPage(); $i++)
      <?php
        $half_total_links = floor($link_limit / 2);
        $from = $paginator->currentPage() - $half_total_links;
        $to = $paginator->currentPage() + $half_total_links;
        if ($paginator->currentPage() < $half_total_links) {
           $to += $half_total_links - $paginator->currentPage();
        }
        if ($paginator->lastPage() - $paginator->currentPage() < $half_total_links) {
            $from -= $half_total_links - ($paginator->lastPage() - $paginator->currentPage()) - 1;
        }
      ?>
      @if ($from < $i && $i < $to)
          @if($paginator->currentPage() == $i)
          <li class="page-item active">
              <a class="page-link" href="#">{!! $i !!} <span class="sr-only">(current)</span></a>
          </li>
          @else
          <li class="page-item">
              <a class="page-link" href="{!! $paginator->url($i) !!}">{!! $i !!}</a>
          </li>
          @endif
      @endif
    @endfor
    @if($paginator->currentPage() == $paginator->lastPage())
    <li class="page-item disabled">
      <a class="page-link" href="#" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Next</span>
      </a>
    </li>
    @else
    <li class="page-item">
      <a class="page-link" href="{!! $paginator->url($paginator->lastPage()) !!}" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Next</span>
      </a>
    </li>
    @endif
  </ul>
@endif