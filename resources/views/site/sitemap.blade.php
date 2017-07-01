<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{!! url('/') !!}</loc>
        <lastmod>{!! date('Y-m-d') !!}</lastmod>
        <changefreq>always</changefreq>
        <priority>1</priority>
    </url>
    <?php 
        $postTypes = CommonQuery::getAllWithStatus('post_types');
        $postTags = CommonQuery::getAllWithStatus('post_tags');
        $posts = CommonQuery::getAllWithStatus('posts');
        $pages = CommonQuery::getAllWithStatus('pages');
        $postSeries = CommonQuery::getAllWithStatus('post_series');
        $postEps = CommonQuery::getAllWithStatus('post_eps');
    ?>
    @if($postTypes)
        @foreach($postTypes as $value)
        <url>
        	<loc>{!! CommonUrl::getUrlPostType($value->slug) !!}</loc>
    		<changefreq>weekly</changefreq>
    		<priority>0.8</priority>
        </url>
        @endforeach
    @endif
    @if($postTags)
        @foreach($postTags as $value)
        <url>
        	<loc>{!! CommonUrl::getUrlPostTag($value->slug) !!}</loc>
    		<changefreq>weekly</changefreq>
    		<priority>0.8</priority>
        </url>
        @endforeach
    @endif
    @if($posts)
        @foreach($posts as $value)
            <url>
                <loc>{!! url($value->slug) !!}</loc>
                <lastmod>{!! date('Y-m-d', strtotime($value->start_date)) !!}</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.8</priority>
            </url>
        @endforeach
    @endif
    @if($pages)
        @foreach($pages as $value)
    	    <url>
    	    	<loc>{!! url($value->slug) !!}</loc>
    	    	<lastmod>{!! date('Y-m-d', strtotime($value->created_at)) !!}</lastmod>
    			<changefreq>weekly</changefreq>
    			<priority>0.8</priority>
    	    </url>
    	@endforeach
    @endif
    @if($postSeries)
        @foreach($postSeries as $value)
        <url>
            <loc>{!! CommonUrl::getUrlPostSeri($value->slug) !!}</loc>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
        @endforeach
    @endif
    @if($postEps)
        @foreach($postEps as $value)
            <?php 
                $postSlug = CommonQuery::getFieldById('posts', $value->post_id, 'slug');
            ?>
            <url>
                <loc>{!! url($postSlug . '/' . $value->slug) !!}</loc>
                <lastmod>{!! date('Y-m-d', strtotime($value->start_date)) !!}</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.8</priority>
            </url>
        @endforeach
    @endif
</urlset>