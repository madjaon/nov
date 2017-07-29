@extends('admin.layouts.master')

@section('title', 'Steal novel')

@section('content')

<div class="row margin-bottom">
	<div class="col-xs-12">
		<a href="{{ route('admin.post.index') }}" class="btn btn-success btn-sm">Danh sách post</a>
		<a href="{{ route('admin.post.create') }}" class="btn btn-primary btn-sm">Thêm post</a>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title">Crawler</h3>
			</div>
			<div class="box-body no-padding">
				
			</div>
		</div>
	</div>
</div>

@stop