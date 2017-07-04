<header class="mb-4">
  <nav class="navbar navbar-toggleable-md py-1">
    <div class="container">
      <a class="navbar-brand" href="{!! url('/') !!}">Truyện On</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto mt-2 mt-md-0">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="hover" aria-haspopup="true" aria-expanded="false">Thể loại</a>
              {!! $menuoftypes !!}
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLinkList" data-toggle="hover" aria-haspopup="true" aria-expanded="false">Danh sách</a>
            <div class="dropdown-menu animated fadeInDownNew" aria-labelledby="navbarDropdownMenuLinkList">
              <a class="dropdown-item" href="{!! CommonUrl::getUrlPostKind('da-hoan-thanh') !!}">Truyện đã hoàn thành</a>
              <a class="dropdown-item" href="{!! CommonUrl::getUrlPostKind('con-tiep-tuc') !!}">Truyện còn tiếp tục</a>
              <a class="dropdown-item" href="{!! url('tac-gia') !!}">Tác giả</a>
            </div>
          </li>
        </ul>
        <form action="{!! route('site.search') !!}" method="GET" class="form-inline my-2 my-lg-0">
          <div class="input-group">
          <input name="s" type="text" value="" class="form-control search-input" placeholder="Tìm kiếm theo tên hoặc tác giả truyện" id="search">
            <span class="input-group-btn">
              <button class="btn btn-success" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
            </span>
          </div>
        </form>
      </div>
    </div>
  </nav>
</header>
