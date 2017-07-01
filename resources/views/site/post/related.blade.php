@if($data && count($postData) > 0)
<div class="related">
	<div class="box-title clearfix">
		<h3><a href="{!! $typeMainUrl !!}" title="{!! $data->name !!}">{!! $data->name !!}</a></h3>
		<span>&nbsp;</span>
	</div>
	@include('site.post.box0', array('data' => $postData))
</div>
@endif
