<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use DB;
use Cache;
use App\Helpers\CommonMethod;
use App\Helpers\CommonOption;
use Validator;
use App\Models\Contact;

class SiteController extends Controller
{
    // public function __construct()
    // {
    //     //
    // }

    public function index()
    {
        if(CACHE == 1) {
            // cache name
            $cacheName = 'index';
            if(getDevice2() == MOBILE) {
                $cacheName = $cacheName.'_mobile';
            }
            // get cache
            if(Cache::has($cacheName)) {
                return Cache::get($cacheName);
            }
        }
        // query
        $query = $this->getEpchapLatest();
        // epchap moi nhat
        $data = $query->take(PAGINATE_LIST)->get();

        // epchap moi nhat tiep theo
        $data2 = $query->offset(PAGINATE_LIST)->take(PAGINATE_TABLE)->get();

        //seo meta
        $seo = DB::table('configs')->where('status', ACTIVE)->first();
        
        if(CACHE == 1) {
            // put cache
            $html = view('site.index', ['data' => $data, 'data2' => $data2, 'seo' => $seo])->render();
            Cache::forever($cacheName, $html);
        }
        // return view
        return view('site.index', ['data' => $data, 'data2' => $data2, 'seo' => $seo]);
    }
    public function author()
    {
        if(CACHE == 1) {
            // cache name
            $cacheName = 'taglist_tac-gia';
            if(getDevice2() == MOBILE) {
                $cacheName = $cacheName.'_mobile';
            }
            // get cache
            if(Cache::has($cacheName)) {
                return Cache::get($cacheName);
            }
        }
        $data = DB::table('post_tags')
            ->join('post_tag_relations', 'post_tags.id', '=', 'post_tag_relations.tag_id')
            ->select('post_tags.id', 'post_tags.name', 'post_tags.slug', 'post_tags.image')
            ->where('post_tags.status', ACTIVE)
            ->groupBy('post_tag_relations.tag_id')
            ->orderBy('post_tags.name')
            ->get();
        if(!empty($data)) {
            // auto meta for seo
            $seo = new \stdClass();
            $seo->h1 = 'Danh sách tác giả';
            $seo->meta_title = 'Danh sách tác giả';
            $seo->meta_keyword = 'tác giả truyện, tac gia truyen';
            $seo->meta_description = 'Danh sách các tác giả truyện, tiểu thuyết';
            $seo->meta_image = '/img/noimage600x315.jpg';
            
            if(CACHE == 1) {
                // put cache
                $html = view('site.post.author', ['data' => $data, 'seo' => $seo])->render();
                Cache::forever($cacheName, $html);    
            }
            // return view
            return view('site.post.author', ['data' => $data, 'seo' => $seo]);
        }
        return response()->view('errors.404', [], 404);
    }
    public function tag(Request $request, $slug)
    {
        trimRequest($request);
        // check page
        $page = ($request->page)?$request->page:1;
        if(CACHE == 1) {
            // cache name
            $cacheName = 'tag_'.$slug.'_'.$page;
            if(getDevice2() == MOBILE) {
                $cacheName = $cacheName.'_mobile';
            }
            // get cache
            if(Cache::has($cacheName)) {
                return Cache::get($cacheName);
            }
        }
        // query
        $tag = DB::table('post_tags')
            ->select('id', 'name', 'slug', 'patterns', 'summary', 'description', 'image', 'meta_title', 'meta_keyword', 'meta_description', 'meta_image')
            ->where('status', ACTIVE)
            ->where('slug', $slug)
            ->first();
        // posts tags
        if(isset($tag)) {
            $tag->patterns = CommonMethod::replaceText($tag->patterns);
            $tag->summary = CommonMethod::replaceText($tag->summary);
            $tag->description = CommonMethod::replaceText($tag->description);
            $data = $this->getPostByRelationsQuery('tag', $tag->id)->paginate(PAGINATE);
            if($data->total() > 0) {
                // auto meta tag for seo
                $tagName = ucwords(mb_strtolower($tag->name));
                $tag->h1 = 'Tác giả ' . $tagName;
                if(empty($tag->meta_title)) {
                    if($page > 1) {
                        $tag->meta_title = 'Đọc truyện của ' . $tagName.' trang '.$page;
                    } else {
                        $tag->meta_title = 'Đọc truyện của ' . $tagName;
                    }
                }
                if(empty($tag->meta_keyword)) {
                    // $tagNameNoLatin = CommonMethod::convert_string_vi_to_en($tagName);
                    // $tag->meta_keyword = $tagNameNoLatin.','.$tagName;
                    $tag->meta_keyword = $tagName;
                }
                if(empty($tag->meta_description)) {
                    $tag->meta_description = $tagName;
                }
                if(empty($tag->meta_image)) {
                    $tag->meta_image = '/img/noimage600x315.jpg';
                }

                if(CACHE == 1) {
                    // put cache
                    $html = view('site.post.tag', ['data' => $data, 'tag' => $tag])->render();
                    Cache::forever($cacheName, $html);    
                }
                // return view
                return view('site.post.tag', ['data' => $data, 'tag' => $tag]);
            }
        }
        return response()->view('errors.404', [], 404);
    }
    public function type(Request $request, $slug)
    {
        trimRequest($request);
        // check page
        $page = ($request->page)?$request->page:1;
        if(CACHE == 1) {
            // cache name
            $cacheName = 'type_'.$slug.'_'.$page;
            if(getDevice2() == MOBILE) {
                $cacheName = $cacheName.'_mobile';
            }
            // get cache
            if(Cache::has($cacheName)) {
                return Cache::get($cacheName);
            }
        }
        // query
        $type = DB::table('post_types')
            ->select('id', 'name', 'slug', 'patterns', 'summary', 'description', 'image', 'meta_title', 'meta_keyword', 'meta_description', 'meta_image')
            ->where('status', ACTIVE)
            ->where('slug', $slug)
            ->first();
        // posts types
        if(isset($type)) {
            $type->patterns = CommonMethod::replaceText($type->patterns);
            $type->summary = CommonMethod::replaceText($type->summary);
            $type->description = CommonMethod::replaceText($type->description);
            $data = $this->getPostByRelationsQuery('type', $type->id)->paginate(PAGINATE);
            if($data->total() > 0) {
                // auto meta type for seo
                $typeName = ucwords(mb_strtolower($type->name));
                $type->h1 = 'Thể loại ' . $typeName;
                if(empty($type->meta_title)) {
                    if($page > 1) {
                        $type->meta_title = 'Đọc truyện thể loại ' . $typeName.' trang '.$page;
                    } else {
                        $type->meta_title = 'Đọc truyện thể loại ' . $typeName;
                    }
                }
                if(empty($type->meta_keyword)) {
                    // $typeNameNoLatin = CommonMethod::convert_string_vi_to_en($typeName);
                    // $type->meta_keyword = $typeNameNoLatin.','.$typeName;
                    $type->meta_keyword = $typeName;
                }
                if(empty($type->meta_description)) {
                    $type->meta_description = $typeName;
                }
                if(empty($type->meta_image)) {
                    $type->meta_image = '/img/noimage600x315.jpg';
                }

                if(CACHE == 1) {
                    // put cache
                    $html = view('site.post.type', ['data' => $data, 'type' => $type])->render();
                    Cache::forever($cacheName, $html);    
                }
                // return view
                return view('site.post.type', ['data' => $data, 'type' => $type]);
            }
        }
        return response()->view('errors.404', [], 404);
    }
    public function seri(Request $request, $slug)
    {
        trimRequest($request);
        // check page
        $page = ($request->page)?$request->page:1;
        if(CACHE == 1) {
            // cache name
            $cacheName = 'seri_'.$slug.'_'.$page;
            if(getDevice2() == MOBILE) {
                $cacheName = $cacheName.'_mobile';
            }
            // get cache
            if(Cache::has($cacheName)) {
                return Cache::get($cacheName);
            }
        }
        // query
        $seri = DB::table('post_series')
            ->select('id', 'name', 'slug', 'patterns', 'summary', 'description', 'image', 'meta_title', 'meta_keyword', 'meta_description', 'meta_image')
            ->where('status', ACTIVE)
            ->where('slug', $slug)
            ->first();
        // posts seris
        if(isset($seri)) {
            $seri->patterns = CommonMethod::replaceText($seri->patterns);
            $seri->summary = CommonMethod::replaceText($seri->summary);
            $seri->description = CommonMethod::replaceText($seri->description);
            $data = $this->getPostBySeriQuery($seri->id)->paginate(PAGINATE);
            if($data->total() > 0) {
                // auto meta seri for seo
                $seriName = ucwords(mb_strtolower($seri->name));
                $seri->h1 = 'Seri truyện ' . $seriName;
                if(empty($seri->meta_title)) {
                    if($page > 1) {
                        $seri->meta_title = 'Seri truyện ' . $seriName.' trang '.$page;
                    } else {
                        $seri->meta_title = 'Seri truyện ' . $seriName;
                    }
                }
                if(empty($seri->meta_keyword)) {
                    // $seriNameNoLatin = CommonMethod::convert_string_vi_to_en($seriName);
                    // $seri->meta_keyword = $seriNameNoLatin.','.$seriName;
                    $seri->meta_keyword = $seriName;
                }
                if(empty($seri->meta_description)) {
                    $seri->meta_description = $seriName;
                }
                if(empty($seri->meta_image)) {
                    $seri->meta_image = '/img/noimage600x315.jpg';
                }

                if(CACHE == 1) {
                    // put cache
                    $html = view('site.post.seri', ['data' => $data, 'seri' => $seri])->render();
                    Cache::forever($cacheName, $html);    
                }
                // return view
                return view('site.post.seri', ['data' => $data, 'seri' => $seri]);
            }
        }
        return response()->view('errors.404', [], 404);
    }
    public function nation(Request $request, $slug)
    {
        if(!in_array($slug, [SLUG_NATION_JAPAN, SLUG_NATION_USA, SLUG_NATION_KOREAN, SLUG_NATION_CHINA, SLUG_NATION_VIETNAM, SLUG_NATION_OTHER])) {
            return response()->view('errors.404', [], 404);
        }

        trimRequest($request);
        // check page
        $page = ($request->page)?$request->page:1;
        if(CACHE == 1) {
            // cache name
            $cacheName = 'nation_'.$slug.'_'.$page;
            if(getDevice2() == MOBILE) {
                $cacheName = $cacheName.'_mobile';
            }
            // get cache
            if(Cache::has($cacheName)) {
                return Cache::get($cacheName);
            }
        }
        // query
        $data = DB::table('posts')
            ->select('id', 'name', 'slug', 'name2', 'patterns', 'image', 'summary', 'type', 'kind', 'epchap', 'view')
            ->where('nation', $slug)
            ->where('status', ACTIVE)
            ->where('start_date', '<=', date('Y-m-d H:i:s'))
            ->orderBy('start_date', 'desc')
            ->paginate(PAGINATE);
        // posts
        if($data->total() > 0) {
            // auto meta for seo
            $seo = new \stdClass();
            $seo->h1 = 'Danh sách truyện ' . CommonOption::getNation($slug);
            if($page > 1) {
                $seo->meta_title = 'Danh sách truyện ' . CommonOption::getNation($slug) . 'hay nhất trang ' . $page;
            } else {
                $seo->meta_title = 'Danh sách truyện ' . CommonOption::getNation($slug) . 'hay nhất';
            }
            $seo->meta_keyword = 'truyện ' . CommonOption::getNation($slug);
            $seo->meta_description = 'Danh sách truyện ' . CommonOption::getNation($slug) . ' hay nhất';
            $seo->meta_image = '/img/noimage600x315.jpg';

            if(CACHE == 1) {
                // put cache
                $html = view('site.post.box', ['data' => $data, 'seo' => $seo])->render();
                Cache::forever($cacheName, $html);    
            }
            // return view
            return view('site.post.box', ['data' => $data, 'seo' => $seo]);
        }
        return response()->view('errors.404', [], 404);
    }
    public function kind(Request $request, $slug)
    {
        if(!in_array($slug, [SLUG_POST_KIND_FULL, SLUG_POST_KIND_UPDATING])) {
            return response()->view('errors.404', [], 404);
        }

        trimRequest($request);
        // check page
        $page = ($request->page)?$request->page:1;
        if(CACHE == 1) {
            // cache name
            $cacheName = 'kind_'.$slug.'_'.$page;
            if(getDevice2() == MOBILE) {
                $cacheName = $cacheName.'_mobile';
            }
            // get cache
            if(Cache::has($cacheName)) {
                return Cache::get($cacheName);
            }
        }
        
        // query
        $data = DB::table('posts')
            ->select('id', 'name', 'slug', 'name2', 'patterns', 'image', 'summary', 'type', 'kind', 'epchap', 'view')
            ->where('kind', $slug)
            ->where('status', ACTIVE)
            ->where('start_date', '<=', date('Y-m-d H:i:s'))
            ->orderBy('start_date', 'desc')
            ->paginate(PAGINATE);
        // posts
        if($data->total() > 0) {
            // auto meta for seo
            $seo = new \stdClass();
            $seo->h1 = 'Danh sách truyện ' . CommonOption::getKindPost($slug);
            if($page > 1) {
                $seo->meta_title = 'Danh sách truyện ' . CommonOption::getKindPost($slug) . ' trang ' . $page;
            } else {
                $seo->meta_title = 'Danh sách truyện ' . CommonOption::getKindPost($slug);
            }
            $seo->meta_keyword = 'Danh sách truyện ' . CommonOption::getKindPost($slug);
            $seo->meta_description = 'Danh sách truyện ' . CommonOption::getKindPost($slug);
            $seo->meta_image = '/img/noimage600x315.jpg';

            if(CACHE == 1) {
                // put cache
                $html = view('site.post.box', ['data' => $data, 'seo' => $seo])->render();
                Cache::forever($cacheName, $html);    
            }
            // return view
            return view('site.post.box', ['data' => $data, 'seo' => $seo]);
        }
        return response()->view('errors.404', [], 404);
    }
    public function page($slug)
    {
        $this->forgetCache('lien-he');
        
        //update count view post
        // DB::table('posts')->where('slug', $slug)->increment('view');

        if(CACHE == 1) {
            // cache name
            $cacheName = 'page_'.$slug;
            if(getDevice2() == MOBILE) {
                $cacheName = $cacheName.'_mobile';
            }
            // get cache
            if(Cache::has($cacheName)) {
                return Cache::get($cacheName);
            }
        }
        
        // IF SLUG IS PAGE
        // query
        $singlePage = DB::table('pages')->where('slug', $slug)->where('status', ACTIVE)->first();
        // page
        if(isset($singlePage)) {
            $singlePage->patterns = CommonMethod::replaceText($singlePage->patterns);
            $singlePage->summary = CommonMethod::replaceText($singlePage->summary);
            $singlePage->description = CommonMethod::replaceText($singlePage->description);

            // auto meta singlePage for seo
            $singlePageName = ucwords(mb_strtolower($singlePage->name));
            $singlePage->h1 = $singlePageName;
            if(empty($singlePage->meta_title)) {
                $singlePage->meta_title = $singlePageName;
            }
            if(empty($singlePage->meta_keyword)) {
                // $singlePageNameNoLatin = CommonMethod::convert_string_vi_to_en($singlePageName);
                // $singlePage->meta_keyword = $singlePageNameNoLatin.','.$singlePageName;
                $singlePage->meta_keyword = $singlePageName;
            }
            if(empty($singlePage->meta_description)) {
                $singlePage->meta_description = $singlePageName;
            }
            if(empty($singlePage->meta_image)) {
                $singlePage->meta_image = '/img/noimage600x315.jpg';
            }

            if(CACHE == 1) {
                // put cache
                $html = view('site.page', ['data' => $singlePage])->render();
                Cache::forever($cacheName, $html);    
            }
            // return view
            return view('site.page', ['data' => $singlePage]);
        }

        // IF SLUG IS A POST
        // post
        $post = DB::table('posts')
            ->where('slug', $slug)
            ->where('status', ACTIVE)
            ->where('start_date', '<=', date('Y-m-d H:i:s'))
            ->first();
        if(isset($post)) {
            $post->patterns = CommonMethod::replaceText($post->patterns);
            $post->summary = CommonMethod::replaceText($post->summary);
            $post->description = CommonMethod::replaceText($post->description);

            // auto meta post for seo
            $postName = ucwords(mb_strtolower($post->name));
            $post->h1 = $postName;
            if(empty($post->meta_title)) {
                $post->meta_title = 'Đọc truyện '.$postName;
            }
            if(empty($post->meta_keyword)) {
                $post->meta_keyword = 'Đọc truyện '.$postName.', doc truyen '.$post->name2;
            }
            if(empty($post->meta_description)) {
                $post->meta_description = limit_text(strip_tags($post->description), 200);
            }
            if(empty($post->meta_image)) {
                $post->meta_image = '/img/noimage600x315.jpg';
            }

            // tinh trang kind
            $post->kindName = CommonOption::getKindPost($post->kind);
     
            // nation
            $post->nationName = CommonOption::getNation($post->nation);

            // seri
            $seri = DB::table('post_series')
                    ->select('id', 'name', 'slug')
                    ->where('id', $post->seri)
                    ->where('status', ACTIVE)
                    ->first();
            if(isset($seri)) {
                $post->seriInfo = $seri;
                // seri data: danh sach thuoc seri nay
                $post->seriData = $this->getPostBySeriQuery($post->seri, $post->id)->get();
            }

            // list tags
            $tags = $this->getRelationsByPostQuery('tag', $post->id);
            $post->tags = $tags;

            // list type
            $types = $this->getRelationsByPostQuery('type', $post->id);
            $post->types = $types;

            // epchap list
            $eps = $this->getEpchapListByPostId($post->id, 'asc')->take(PAGINATE_BOX)->get();
            $post->eps = $eps;

            // epchap list latest
            $epsLastest = $this->getEpchapListByPostId($post->id, 'desc')->take(PAGINATE_RELATED)->get();
            $post->epsLastest = $epsLastest;

            // list post by type 
            // $related = $this->getPostRelated($post->id, [$post->id], $post->type_main_id);

            // first & last epchap
            // $epFirst = $this->getEpchapListByPostId($post->id, 'asc')->first();
            if(!empty($eps)) {
                $post->epFirst = $eps[0];
            }
            // $epLast = $this->getEpchapListByPostId($post->id, 'desc')->first();
            if(!empty($epsLastest)) {
                $post->epLast = $epsLastest[0];
            }

            $countEps = $this->countEpchapListByPostId($post->id);
            $totalPageEps = ceil($countEps / PAGINATE_BOX);
            $currentPageEps = 1;
            $listPageEps = null;
            if($totalPageEps > 0) {
                for($i = 1; $i <= $totalPageEps; $i++) {
                    $listPageEps[$i] = 'Trang ' . $i;
                }
            }
            $post->countEps = $countEps;
            $post->totalPageEps = $totalPageEps;
            $post->currentPageEps = $currentPageEps;
            $post->listPageEps = $listPageEps;
            $post->prevPageEps = ($currentPageEps > 1)?($currentPageEps - 1):null;
            $post->nextPageEps = ($currentPageEps < $totalPageEps)?($currentPageEps + 1):null;

            if(CACHE == 1) {
                // put cache
                $html = view('site.post.book', ['post' => $post])->render();
                Cache::forever($cacheName, $html);
            }
            // return view
            return view('site.post.book', ['post' => $post]);
        }
        return response()->view('errors.404', [], 404);
    }
    public function page2($slug1, $slug2)
    {
        // set cookie epchap reading
        $cookie = cookie()->forever(COOKIE_NAME, $slug1 . '_' . $slug2);

        if(CACHE == 1) {
            // cache name
            $cacheName = 'page2_'.$slug1.'_'.$slug2;
            if(getDevice2() == MOBILE) {
                $cacheName = $cacheName.'_mobile';
            }
            // get cache
            if(Cache::has($cacheName)) {
                // return Cache::get($cacheName);
                return response(Cache::get($cacheName))->withCookie($cookie);
            }
        }
        // query
        // post
        $post = DB::table('posts')
            ->select('id', 'name', 'slug', 'name2')
            ->where('slug', $slug1)
            ->where('status', ACTIVE)
            ->where('start_date', '<=', date('Y-m-d H:i:s'))
            ->first();
        if(isset($post)) {
            // current epchap
            $data = DB::table('post_eps')
                ->where('slug', $slug2)
                ->where('post_id', $post->id)
                ->where('status', ACTIVE)
                ->where('start_date', '<=', date('Y-m-d H:i:s'))
                ->first();
            if(isset($data)) {
                // auto meta post for seo
                $postName = ucwords(mb_strtolower($post->name));
                $data->h1 = $postName . ' - ' . $data->name;
                if(empty($data->meta_title)) {
                    $data->meta_title = $postName.' - '.$data->name;
                }
                if(empty($data->meta_keyword)) {
                    $data->meta_keyword = $postName.' - '.$data->name;
                }
                if(empty($data->meta_description)) {
                    $data->meta_description = limit_text(strip_tags($data->description), 200);
                }
                if(empty($data->meta_image)) {
                    $data->meta_image = '/img/noimage600x315.jpg';
                }

                // list type
                $types = $this->getRelationsByPostQuery('type', $post->id);
                $post->types = $types;

                // epchap list
                $eps = $this->getEpchapListByPostId($post->id, 'asc')->get();

                // SELECT BOX EPCHAP
                $epchapArray = array();
                foreach($eps as $key => $value) {
                    $epchapUrl = url($post->slug . '/' . $value->slug);
                    if($value->volume > 0) {
                      $epchap = 'Quyển ' . $value->volume . ' chương ' . $value->epchap;
                    } else {
                      $epchap = 'Chương ' . $value->epchap;
                    }
                    $epchapArray[$epchapUrl] = $epchap;
                }
                $post->epchapArray = $epchapArray;

                // PREV & NEXT EPCHAP
                // epchap dua vao position (bat buoc phai nhap dung position)
                $epPrev = $this->getEpchapListByPostId($post->id, 'desc')->where('position', '<', $data->position)->first();
                $epNext = $this->getEpchapListByPostId($post->id, 'asc')->where('position', '>', $data->position)->first();
                
                // gan gia tri vao $data
                if(isset($epPrev)) {
                    $data->epPrev = $epPrev;
                }
                if(isset($epNext)) {
                    $data->epNext = $epNext;
                }
                // END PREV & NEXT EPCHAP

                if(CACHE == 1) {
                    // put cache
                    $html = view('site.post.epchap', [
                            'post' => $post, 
                            'data' => $data, 
                        ])->render();
                    Cache::forever($cacheName, $html);
                }
                // return view
                return response()->view('site.post.epchap', [
                        'post' => $post, 
                        'data' => $data, 
                    ])->withCookie($cookie);
            }
        }
        return response()->view('errors.404', [], 404);
    }
    public function search(Request $request)
    {
        trimRequest($request);

        // check page
        $page = ($request->page)?$request->page:1;

        // auto meta tag for seo
        $seo = new \stdClass();
        $seo->h1 = 'Kết quả tìm kiếm ' . $request->s;
        if($page > 1) {
            $seo->meta_title = 'Kết quả tìm kiếm ' . $request->s . ' trang ' . $page;
        } else {
            $seo->meta_title = 'Kết quả tìm kiếm ' . $request->s;
        }
        $seo->meta_keyword = 'tìm truyện ' . $request->s . ', tim truyen ' . $request->s;
        $seo->meta_description = 'Kết quả tìm kiếm từ khóa ' . $request->s . ', tìm truyện ' . $request->s;
        $seo->meta_image = '/img/noimage600x315.jpg';

        if($request->s == '' || strlen($request->s) < 2) {
            return view('site.post.search', ['data' => null, 'seo' => $seo, 'request' => $request]);
        }
        
        if(CACHE == 1) {
            // cache name
            $cacheName = 'search_'.$request->s.'_'.$page;
            if(getDevice2() == MOBILE) {
                $cacheName = $cacheName.'_mobile';
            }
            // get cache
            if(Cache::has($cacheName)) {
                return Cache::get($cacheName);
            }
        }
        // query
        // post
        $data = $this->searchQueryPostTag($request->s)->paginate(PAGINATE);
        $authors = array();
        if(!empty($data)) {
            foreach($data as $value) {
                $author = '';
                // list tags
                $tags = $this->getRelationsByPostQuery('tag', $value->id);
                if(!empty($tags)) {
                    foreach($tags as $k => $v) {
                        if($k > 0) {
                            $author .= ' - ';
                        }
                        $author .= '<a href="'.url('tac-gia/'.$v->slug).'" title="'.$v->name.'">'.$v->name.'</a>';
                    }
                }
                $authors[] = $author;
            }
        }

        if(CACHE == 1) {
            // put cache
            $html = view('site.post.search', ['data' => $data->appends($request->except('page')), 'seo' => $seo, 'authors' => $authors, 'request' => $request])->render();
            Cache::forever($cacheName, $html);
        }
        // return view
        return view('site.post.search', ['data' => $data->appends($request->except('page')), 'seo' => $seo, 'authors' => $authors, 'request' => $request]);
    }
    public function livesearch(Request $request)
    {
        trimRequest($request);

        if($request->s == '' || strlen($request->s) < 2) {
            return null;
        }
        
        if(CACHE == 1) {
            // cache name
            $cacheName = 'livesearch_suggestion_response_json_'.$request->s;
            // get cache
            if(Cache::has($cacheName)) {
                return Cache::get($cacheName);
            }
        }
        $array = array();
        // AJAX SEARCH
        // Search theo ten post va ten tac gia
        $data = $this->searchQueryPostTag($request->s)->take(PAGINATE_RELATED)->get();

        if(!empty($data)) {
            foreach($data as $value) {
                // neu search theo ten post & ten tac gia thi them authors!
                $authors = '';
                // list tags
                $tags = $this->getRelationsByPostQuery('tag', $value->id);
                if(!empty($tags)) {
                    foreach($tags as $k => $v) {
                        if($k > 0) {
                            $authors .= ' - ';
                        }
                        $authors .= $v->name;
                    }
                }
                $array[] = [
                    'suggestion' => $value->name.'<br>'.'<small>Tác giả: '.$authors.'</small>',
                    'url' => url($value->slug),
                    // "attr" => [["class" => "suggestion"]]
                ];
            }
        }
        $res = ['results' => $array];
        if(CACHE == 1) {
            // put cache
            $jsonData = response()->json($res);
            Cache::forever($cacheName, $jsonData);
        }
        return response()->json($res);
    }
    public function sitemap()
    {
        dd('Too big');
        if(CACHE == 1) {
            // cache name
            $cacheName = 'sitemap';
            // get cache
            if(Cache::has($cacheName)) {
                $content = Cache::get($cacheName);
                return response($content)->header('Content-Type', 'text/xml;charset=utf-8');
            }
        }
        // return view
        $content = view('site.sitemap');
        if(CACHE == 1) {
            // put cache
            $html = $content->render();
            Cache::forever($cacheName, $html);
        }
        return response($content)->header('Content-Type', 'text/xml;charset=utf-8');
    }
    // asuna: lay tat ca du lieu post (null) / hay chi lay danh sach id cua post (not null)
    private function getPostRelated($id, $ids, $typeId, $asuna = null)
    {
        // lay danh sach posts
        if($asuna == null) {
            // post moi hon
            $post1Query = $this->getPostTypeQuery($id, $ids, $typeId);
            $post1 = $post1Query->get();
            // post cu hon
            $post2Query = $this->getPostTypeQuery($id, $ids, $typeId, 1);
            $post2 = $post2Query->get();
            $posts = array_merge($post1, $post2);
            return $posts;
        }
        // lay danh sach id posts
        else {
            // post moi hon
            $post1Query = $this->getPostTypeQuery($id, $ids, $typeId);
            $post1 = $post1Query->pluck('id');
            // post cu hon
            $post2Query = $this->getPostTypeQuery($id, $ids, $typeId, 1);
            $post2 = $post2Query->pluck('id');
            $posts = array_merge($post1, $post2);
            return $posts;
        }
    }
    // lay ra post cu hon (time not null) va moi hon (time null) theo id
    // id: id post hien tai
    // typeId: id type main / related cua post hien tai. ids: danh sach id da lay ra (tranh trung lap)
    private function getPostTypeQuery($id, $ids, $typeId, $time = null)
    {
        $data = DB::table('posts')
            ->join('post_type_relations', 'posts.id', '=', 'post_type_relations.post_id')
            ->select('posts.id', 'posts.name', 'posts.slug',  'posts.name2', 'posts.patterns', 'posts.image', 'posts.summary', 'posts.type', 'posts.kind', 'posts.epchap', 'posts.view')
            ->where('posts.status', ACTIVE)
            ->where('posts.start_date', '<=', date('Y-m-d H:i:s'));
        if($time == null) {
            $data = $data->where('posts.id', '>', $id);
        } else {
            $data = $data->where('posts.id', '<', $id);
        }
        $data = $data->where('post_type_relations.type_id', $typeId)
            ->whereNotIn('post_type_relations.post_id', $ids)
            ->orderBy('posts.id', 'desc')
            ->take(PAGINATE_RELATED);
        return $data;
    }
    // get post by seri field in posts table
    private function getPostBySeriQuery($id, $currentPostId = null)
    {
        $data = DB::table('posts')
            ->select('id', 'name', 'slug', 'name2', 'patterns', 'image', 'summary', 'type', 'kind', 'epchap', 'view')
            ->where('seri', $id)
            ->where('status', ACTIVE)
            ->where('start_date', '<=', date('Y-m-d H:i:s'));
        if($currentPostId != null) {
            $data = $data->where('id', '!=', $currentPostId);
        }
        return $data;
    }
    // element: tag or type / id: id of tag or type
    private function getPostByRelationsQuery($element, $id)
    {
        $data = DB::table('posts')
            ->join('post_'.$element.'_relations', 'posts.id', '=', 'post_'.$element.'_relations.post_id')
            ->select('posts.id', 'posts.name', 'posts.slug',  'posts.name2', 'posts.patterns', 'posts.image', 'posts.summary', 'posts.type', 'posts.kind', 'posts.epchap', 'posts.view')
            ->where('post_'.$element.'_relations.'.$element.'_id', $id)
            ->where('posts.status', ACTIVE)
            ->where('posts.start_date', '<=', date('Y-m-d H:i:s'));
        return $data;
    }
    // element: tag or type / id: id of post
    private function getRelationsByPostQuery($element, $id)
    {
        $data = DB::table('post_'.$element.'s')
            ->join('post_'.$element.'_relations', 'post_'.$element.'s.id', '=', 'post_'.$element.'_relations.'.$element.'_id')
            ->select('post_'.$element.'s.id', 'post_'.$element.'s.name', 'post_'.$element.'s.slug')
            ->where('post_'.$element.'_relations.post_id', $id)
            ->where('post_'.$element.'s.status', ACTIVE)
            ->get();
        return $data;
    }
    // list post_eps moi nhat
    private function getEpchapLatest()
    {
        $data = DB::table('post_eps')
            ->join('posts', 'post_eps.post_id', '=', 'posts.id')
            ->select('posts.id', 'posts.name', 'posts.slug',  'posts.name2', 'posts.image', 'posts.type', 'posts.kind', 'posts.epchap', 'posts.view', 'post_eps.id AS ep_id', 'post_eps.name AS ep_name', 'post_eps.slug AS ep_slug', 'post_eps.volume AS ep_volume', 'post_eps.epchap AS ep_epchap', 'post_eps.start_date AS ep_start_date')
            ->where('post_eps.status', ACTIVE)
            ->where('post_eps.start_date', '<=', date('Y-m-d H:i:s'))
            ->where('posts.status', ACTIVE)
            ->where('posts.start_date', '<=', date('Y-m-d H:i:s'))
            ->orderBy('post_eps.start_date', 'desc');
        return $data;
    }
    // $id: $post_id
    private function getEpchapListByPostId($id, $orderSort = 'desc')
    {
        $data = DB::table('post_eps')
                ->select('id', 'name', 'slug', 'volume', 'epchap')
                ->where('post_id', $id)
                ->where('status', ACTIVE)
                ->where('start_date', '<=', date('Y-m-d H:i:s'))
                ->orderByRaw(DB::raw("position = '0', position ".$orderSort));
        return $data;
    }
    private function countEpchapListByPostId($id)
    {
        $data = DB::table('post_eps')
                ->where('post_id', $id)
                ->where('status', ACTIVE)
                ->where('start_date', '<=', date('Y-m-d H:i:s'))
                ->count();
        return $data;
    }
    // search query
    // to full text search (mysql) working
    // my.ini (my.cnf) add after line [mysqld] before restart sql service: 
    // innodb_ft_min_token_size = 2
    // ft_min_word_len = 2
    // run: mysql> REPAIR TABLE tbl_name QUICK;
    // UNION 2 SELECT with paginate:
    // https://stackoverflow.com/questions/25338456/laravel-union-paginate-at-the-same-time
    private function searchQueryPostTag($s)
    {
        $slug = CommonMethod::convert_string_vi_to_en($s);
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/i', '-', $slug));
        $data = DB::table('posts')
            ->leftJoin('post_tag_relations', 'posts.id', '=', 'post_tag_relations.post_id')
            ->leftJoin('post_tags', 'post_tag_relations.tag_id', '=', 'post_tags.id')
            ->select('posts.id', 'posts.name AS name', 'posts.slug AS slug',  'posts.name2 AS name2', 'posts.patterns', 'posts.image', 'posts.type', 'posts.kind', 'posts.epchap', 'posts.view')
            ->where('posts.status', ACTIVE)
            ->where('posts.start_date', '<=', date('Y-m-d H:i:s'))
            ->whereRaw('MATCH('.env('DB_PREFIX').'posts.slug,'.env('DB_PREFIX').'posts.name,'.env('DB_PREFIX').'posts.name2) AGAINST ("'.$s.'")')
            // ->orWhereRaw('MATCH('.env('DB_PREFIX').'post_tags.slug,'.env('DB_PREFIX').'post_tags.name) AGAINST ("'.$s.'")')
            ->orWhere('post_tags.slug', 'like', '%'.$slug.'%')
            ->orWhere('post_tags.name', 'like', '%'.$s.'%')
            ->groupBy('posts.id');
        return $data;
    }
    /* 
    * contact
    */
    public function contact(Request $request)
    {
        $this->forgetCache('lien-he');
        //
        $now = strtotime(date('Y-m-d H:i:s'));
        $range = 300; //second
        $time = $now - $range;
        $past = date('Y-m-d H:i:s', $time);
        // check ip with time
        $checkIP = DB::table('contacts')->where('ip', $request->ip())->where('created_at', '>', $past)->count();
        if($checkIP > 0) {
            return redirect()->back()->with('warning', 'Hệ thống đang bận. Xin bạn hãy thử lại sau ít phút.');
        }
        //
        trimRequest($request);
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'tel' => 'max:255',
            'msg' => 'max:1000',
        ]);
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        Contact::create([
                'name' => $request->name,
                'email' => $request->email,
                'tel' => $request->tel,
                'msg' => $request->msg,
                'ip' => $request->ip()
            ]);
        return redirect()->back()->with('success', 'Cảm ơn bạn đã gửi thông tin liên hệ cho chúng tôi.');
    }
    public function errorreporting(Request $request)
    {
        $now = strtotime(date('Y-m-d H:i:s'));
        $range = 600; //second
        $time = $now - $range;
        $past = date('Y-m-d H:i:s', $time);
        // check ip with time
        $checkIP = DB::table('contacts')->where('ip', $request->ip())->where('created_at', '>', $past)->count();
        if($checkIP > 0) {
            return 1;
        }
        //
        trimRequest($request);
        Contact::create([
                'name' => 'Báo lỗi chương',
                'msg' => $request->url,
                'ip' => $request->ip()
            ]);
        return 1;
    }
    public function bookpaging(Request $request)
    {
        trimRequest($request);
        // check page
        $page = ($request->page)?$request->page:1;
        $id = ($request->id)?$request->id:1;

        if(CACHE == 1) {
            // cache name
            $cacheName = 'bookpaging_'.$id.'_'.$page;
            if(getDevice2() == MOBILE) {
                $cacheName = $cacheName.'_mobile';
            }
            // get cache
            if(Cache::has($cacheName)) {
                return Cache::get($cacheName);
            }
        }

        // query
        // post
        $post = DB::table('posts')
            ->where('id', $id)
            ->where('status', ACTIVE)
            ->where('start_date', '<=', date('Y-m-d H:i:s'))
            ->first();
        if(isset($post)) {
            $countEps = $this->countEpchapListByPostId($post->id);
            $totalPageEps = ceil($countEps / PAGINATE_BOX);
            $currentPageEps = ($page > 0 && $page <= $totalPageEps)?$page:1;
            $listPageEps = null;
            if($totalPageEps > 0) {
                for($i = 1; $i <= $totalPageEps; $i++) {
                    $listPageEps[$i] = 'Trang ' . $i;
                }
            }
            $post->countEps = $countEps;
            $post->totalPageEps = $totalPageEps;
            $post->currentPageEps = $currentPageEps;
            $post->listPageEps = $listPageEps;
            $post->prevPageEps = ($currentPageEps > 1)?($currentPageEps - 1):null;
            $post->nextPageEps = ($currentPageEps < $totalPageEps)?($currentPageEps + 1):null;

            // offset
            $offset = ($page - 1) * PAGINATE_BOX;

            // epchap list
            $eps = $this->getEpchapListByPostId($post->id, 'asc')->skip($offset)->take(PAGINATE_BOX)->get();
            $post->eps = $eps;

            if(CACHE == 1) {
                // put cache
                $html = view('site.post.booklist', ['post' => $post])->render();
                Cache::forever($cacheName, $html);
            }
            // return view
            return view('site.post.booklist', ['post' => $post]);
        }
        return '<p>Đang cập nhật</p>';
    }
    // remove cache page if exist message validator
    private function forgetCache($slug)
    {
        // delete cache for contact page before redirect to remove message validator
        $cacheName = 'page_'.$slug;
        $cacheNameMobile = 'page_'.$slug.'_mobile';
        Cache::forget($cacheName);
        Cache::forget($cacheNameMobile);
    }

}
