@extends('admin.layouts.master')

@section('title', 'Thêm post chap')

@section('content')

<div class="row margin-bottom">
	<div class="col-xs-12">
		<a href="{{ route('admin.postep.index') }}?post_id={{ $request->post_id }}&post_name={{ $request->post_name }}&post_slug={{ $request->post_slug }}" class="btn btn-success btn-sm">Danh sách post chaps</a>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<form action="{{ route('admin.postep.store') }}" method="POST">
				{!! csrf_field() !!}
				<div class="box-header">
					<h3 class="box-title">Thêm post chap</h3>
					<p></p>
					<p style="font-weight: bold;">ID post: {{ $request->post_id }} - <span style="color: red; font-weight: bold;">{{ $request->post_name }}</span> - <a href="{{ CommonUrl::getUrl($request->post_slug) }}" target="_blank">Xem</a> | <a href="{{ route('admin.post.edit', $request->post_id) }}">Sửa</a></p>
					<input type="hidden" name="post_id" value="{{ $request->post_id }}">
					<input type="hidden" name="post_name" value="{{ $request->post_name }}">
					<input type="hidden" name="post_slug" value="{{ $request->post_slug }}">
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-sm-8">
							<div class="form-group">
								<label>Name <span style="color: red;">(*)</span></label>
								<div class="row">
									<div class="col-sm-12">
										<input name="name" type="text" value="{{ old('name') }}" class="form-control" onkeyup="convert_to_slug(this.value);">
									</div>
								</div>
							</div>
							<div class="form-group">
								<label>Slug <span style="color: red;">(*)</span></label>
								<div class="row">
									<div class="col-sm-12">
										<input name="slug" type="text" value="{{ old('slug') }}" class="form-control">
									</div>
								</div>
							</div>
							<div class="form-group" style="display: none;">
								<label>Ngày đăng</label>
								<div class="row">
									<div class="col-sm-6">
										<div class="input-group date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input name="start_date" type="text" value="{{ old('start_date') }}" class="form-control pull-right datepicker">
						                </div>
									</div>
									<div class="col-sm-6">
										<div class="bootstrap-timepicker">
											<div class="input-group">
												<input name="start_time" type="text" class="form-control timepicker">
												<div class="input-group-addon">
													<i class="fa fa-clock-o"></i>
												</div>
											</div>	
										</div>
									</div>
								</div>
							</div>
							@include('admin.common.inputStatusLang', array('isCreate' => true))
							@include('admin.common.inputContent', array('isCreate' => true))
							@include('admin.common.inputMeta', array('isCreate' => true))
						</div>
						<div class="col-sm-4">
							<div class="box-footer">
								<input type="submit" class="btn btn-primary" value="Lưu lại" />
								<input type="reset" class="btn btn-default" value="Nhập lại" />
							</div>
							<div class="form-group">
								<label>Số chap hiện tại <span style="color: red;">(*)</span></label>
								<div class="row">
									<div class="col-sm-12">
										<input name="epchap" type="text" value="{{ old('epchap') }}" class="form-control">
									</div>
								</div>
							</div>
							<div class="form-group">
								<label>Số quyển hiện tại</label>
								<p>Nhập số 0 hoặc không nhập gì nếu không có</p>
								<div class="row">
									<div class="col-sm-12">
										<input name="volume" type="text" value="{{ old('volume') }}" class="form-control onlyNumber">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<input type="submit" class="btn btn-primary" value="Lưu lại" />
					<input type="reset" class="btn btn-default" value="Nhập lại" />
				</div>
			</form>
		</div>
	</div>
</div>

@stop