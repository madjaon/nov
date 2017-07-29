<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostType;
use App\Models\PostTag;
use App\Models\Crawler;
use DB;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Helpers\CommonMethod;
use App\Helpers\CommonQuery;
use Cache;
use Sunra\PhpSimple\HtmlDomParser;

class Crawler2Controller extends Controller
{

    public function __construct()
    {
        if(Auth::guard('admin')->user()->role_id != ADMIN) {
            dd('Permission denied! Please back!');
        }
    }
    
    public function index()
    {
        return view('admin.crawler2.index');
    }

    public function steal(Request $request)
    {
        Cache::flush();
        trimRequest($request);
        if($request->type == CRAW_POST) {
            if(!empty($request->post_links)) {
                $links = explode(',', $request->post_links);
                $result = self::stealPost($request, $links);
            }
        } else if($request->type == CRAW_CATEGORY) {
            if(!empty($request->category_link)) {
                $cats = [$request->category_link];
            } else {
                $cats = array();
            }
            //check paging. neu trang ket thuc > 1 va co link mau trang thi moi lay ds link trang
            if(!empty($request->category_page_link) && !empty($request->category_page_end) && $request->category_page_end > 1) {
                //neu co category_link (trang dau tien) thi trang bat dau phai lon hon 1
                if(!empty($request->category_link)) {
                    $pageStartCheck = 1;
                    $pageStartNeed = 2;
                } else {
                    $pageStartCheck = 0;
                    $pageStartNeed = 1;
                }
                //neu trang bat dau > 0 thi ok neu khong se lay mac dinh tu 2
                if(!empty($request->category_page_start) && $request->category_page_start > $pageStartCheck) {
                    $category_page_start = $request->category_page_start;
                } else {
                    $category_page_start = $pageStartNeed;
                }
                for($i = $category_page_start; $i <= $request->category_page_end; $i++) {
                    $cats[] = str_replace('[page_number]', $i, $request->category_page_link);
                }
            }
            if(count($cats) > 0 && !empty($request->category_post_link_pattern)) {
                foreach($cats as $key => $value) {
                    //get full link if link is slug
                    $value = CommonMethod::getfullurl($value, $request->source, 1);
                    // get all link cat
                    $html = HtmlDomParser::file_get_html($value); // Create DOM from URL or file
                    foreach($html->find($request->category_post_link_pattern) as $element) {
                        $links[$key][] = trim($element->href);
                    }
                    //luon luon lay danh sach anh trong trang category. 
                    //bo phan: && $request->image_check == CRAW_CATEGORY_IMAGE . 
                    //ly do neu trong noi dung k co hinh thi lay avatar o ben ngoai trang danh sach category
                    if(!empty($request->image_check) && !empty($request->image_pattern)) {
                        foreach($html->find($request->image_pattern) as $element) {
                            if($element) {
                                $images[$key][] = $element->src;
                            } else {
                                $images[$key][] = '';
                            }
                        }
                    } else {
                        $images[$key] = [];
                    }
                    if($request->title_type == TITLETYPE1) {
                        if(!empty($request->title_post_check) && $request->title_post_check == CRAW_TITLE_CATEGORY && !empty($request->title_pattern)) {
                            foreach($html->find($request->title_pattern) as $element) {
                                if($element) {
                                    $titleList[$key][] = trim($element->plaintext);
                                } else {
                                    $titleList[$key][] = '';
                                }
                            }
                        } else {
                            $titleList[$key] = [];
                        }
                    }
                    $result = self::stealPost($request, $links[$key], $images[$key], $titleList[$key]);
                }
            }
        }
        if(!empty($request->id)) {
            $data = Crawler::find($request->id);
            if($data) {
                $data->update([
                    'count_get' => $data->count_get+1,
                    'time_get' => date('Y-m-d H:i:s'),
                ]);
            }
        }
        return $result;
    }

    private function stealPost($request, $links=array(), $images=array(), $titleList=array())
    {
        if(count($links) > 0) {
            foreach($links as $key => $link) {
                //get full link if link is slug
                $link = CommonMethod::getfullurl($link, $request->source, 1);
                $html = HtmlDomParser::file_get_html($link); // Create DOM from URL or file
                // Lấy tiêu đề
                if($request->title_type == TITLETYPE2) {
                    if(!empty($request->post_slugs)) {
                        $slugsForTitles = explode(',', $request->post_slugs);
                        $slugTitle = $slugsForTitles[$key];
                        $postName = trim(str_replace('-', ' ', $slugTitle));
                    }
                } else if($request->title_type == TITLETYPE3) {
                    if(!empty($request->post_titles)) {
                        $titles = explode(',', $request->post_titles);
                        $postName = trim($titles[$key]);
                    }
                } else {
                    //postname lay theo tieu de tung bai viet trong trang danh sach
                    if(count($titleList) > 0 && !empty($request->title_post_check) && $request->title_post_check == CRAW_TITLE_CATEGORY) {
                        $postName = $titleList[$key];
                    } 
                    //postname lay theo tieu de trong trang chi tiet
                    else {
                        foreach($html->find($request->title_pattern) as $element) {
                            $postName = trim($element->plaintext); // Chỉ lấy phần text
                        }
                    }
                }
                $postName = html_entity_decode($postName);
                // Lấy noi dung
                $postDescription = '';
                foreach($html->find($request->description_pattern) as $element) {
                    // tim anh truoc khi xoa the chua anh <img> (lay lam avatar neu lua chon)
                    if(!empty($request->image_check) && $request->image_check == CRAW_POST_IMAGE && !empty($request->image_pattern)) {
                        //tim het anh trong noi dung
                        foreach($element->find($request->image_pattern) as $kimg => $eimg) {
                            if($eimg && $kimg == 0) {
                                //nhung chi lay anh dau tien lam avatar. $eimg[0]
                                $images[$key] = $eimg->src;
                                break;
                            }
                        }
                    }
                    // Xóa các mẫu trong miêu tả
                    if(!empty($request->description_pattern_delete)) {
                        $arr = explode(',', $request->description_pattern_delete);
                        for($i=0;$i<count($arr);$i++) {
                            foreach($element->find($arr[$i]) as $e) {
                                $e->outertext='';
                            }
                        }
                    }
                    // loai bo the cu the element_delete
                    // cau truc: element_delete: div,h2
                    // cau truc: element_delete_positions: 0,1,-1|-1
                    if(!empty($request->element_delete)) {
                        $element_delete = explode(',', $request->element_delete);
                        if(count($element_delete) > 0) {
                            if(!empty($request->element_delete_positions)) {
                                $element_delete_positions = explode('|', $request->element_delete_positions);
                                if(count($element_delete_positions) > 0) {
                                   foreach($element_delete as $ked => $ed) {
                                        if(!empty($element_delete_positions[$ked])) {
                                            $element_delete_positions_ked = explode(',', $element_delete_positions[$ked]);
                                            if(count($element_delete_positions_ked) > 0) {
                                                foreach($element_delete_positions_ked as $edp) {
                                                    $element->find($ed, $edp)->outertext='';
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    //neu khong xoa img trong noi dung thi can thay doi duong dan va upload hinh
                    if(empty($request->description_pattern_delete) || (!empty($request->description_pattern_delete) && strpos($request->description_pattern_delete, ',img') === false)) {
                        foreach($element->find('img') as $el) {
                            if($el && !empty($el->src)) {
                                //origin image upload
                                $el_src = CommonMethod::createThumb($el->src, $request->source, $request->image_dir, null, null, null, 1);
                                //thumbnail image upload
                                $el_thumb = CommonMethod::createThumb($el->src, $request->source, $request->image_dir . '/thumb', IMAGE_WIDTH, IMAGE_HEIGHT);
                                //neu up duoc hinh thi thay doi duong dan, neu khong xoa the img nay di luon
                                if(!empty($el_src)) {
                                    $el->src = $el_src;
                                } else {
                                    $el->outertext = '';
                                }
                            }
                        }
                    }
                    $postDescription = trim($element->innertext); // Lấy toàn bộ phần html
                    //loai bo het duong dan trong noi dung
                    if(!empty($postDescription)){
                        $postDescription = preg_replace('/<a href=\"(.*?)\">(.*?)<\/a>/', "\\2", $postDescription);
                    }
                }
                $postDescription = html_entity_decode($postDescription);
                //slug
                if($request->slug_type == SLUGTYPE2) {
                    $slug = CommonMethod::getSlugFromUrl($link);
                } else if($request->slug_type == SLUGTYPE3) {
                    if(!empty($request->post_slugs)) {
                        $slugs = explode(',', $request->post_slugs);
                        $slug = $slugs[$key];
                    } else {
                        $slug = CommonMethod::getSlugFromUrl($link);
                    }
                } else {
                    $slug = CommonMethod::convert_string_vi_to_en($postName);
                    $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/i', '-', $slug));
                }
                //check slug post
                $checkSlug = Post::where('slug', $slug)->first();
                if(count($checkSlug) == 0) {
                    //image avatar upload
                    if(count($images) > 0 && !empty($images[$key]) && !empty($request->image_dir)) {
                        //origin image upload
                        $imageOrigin = CommonMethod::createThumb($images[$key], $request->source, $request->image_dir, null, null, null, 1);
                        //thumbnail image upload
                        $image = CommonMethod::createThumb($images[$key], $request->source, $request->image_dir . '/thumb', IMAGE_WIDTH, IMAGE_HEIGHT);
                    } else {
                        $image = '';
                    }
                    //insert post
                    $data = Post::create([
                        'name' => $postName,
                        'slug' => $slug,
                        'type_main_id' => $request->type_main_id,
                        'description' => $postDescription,
                        'image' => $image,
                        'position' => 1,
                        'start_date' => CommonMethod::datetimeConvert($request->start_date, $request->start_time),
                        'status' => $request->status,
                        'source' => $request->source,
                        'source_url' => $link,
                    ]);
                    if($data) {
                        // insert game type relation
                        $data->posttypes()->attach([$request->type_main_id]);
                    }
                }
                //end post
            }
        }
        return;
    }

    public function truyenfulltacgia()
    {
        $cats = ['http://truyenfull.vn/danh-sach/truyen-hot/'];
        $category_page_link = 'http://truyenfull.vn/danh-sach/truyen-hot/trang-[page_number]/';
        // $category_page_link = '';
        $category_page_start = 2;
        $category_page_end = 445;
        //check paging. neu trang ket thuc > 1 va co link mau trang thi moi lay ds link trang
        if(!empty($category_page_link) && !empty($category_page_end) && $category_page_end > 1) {
            for($i = $category_page_start; $i <= $category_page_end; $i++) {
                $cats[] = str_replace('[page_number]', $i, $category_page_link);
            }
        }
        if(count($cats) > 0) {
            foreach($cats as $key => $value) {
                $htmlString = CommonMethod::get_remote_data($value);
                // get all link cat
                $html = HtmlDomParser::str_get_html($htmlString); // Create DOM from URL or file
                // foreach($html->find('h3.truyen-title a') as $element) {
                //     $links[$key][] = trim($element->href);
                // }
                // foreach($html->find('h3.truyen-title a') as $element) {
                //     $titles[$key][] = trim($element->plaintext);
                // }
                foreach($html->find('span.author') as $element) {
                    $authors[$key][] = trim($element->plaintext);
                }
                if(count($authors[$key]) > 0) {
                    foreach($authors[$key] as $vauthor) {
                        if(strpos($vauthor, ',') === false) {
                            self::insertTag($vauthor);
                        } else {
                            $vauthors = explode(',', $vauthor);
                            foreach($vauthors as $v) {
                                self::insertTag($v);
                            }
                        }
                    }
                }
            }

        }
        return redirect()->route('admin.crawler2.index')->with('success', 'Thêm thành công');
    }

    private function insertTag($value) {
        $value = trim($value);
        $slug = CommonMethod::convert_string_vi_to_en($value);
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/i', '-', $slug));
        $checkSlug = PostTag::where('slug', $slug)->first();
        if(count($checkSlug) == 0) {
            //insert tag
            PostTag::create([
                'name' => $value,
                'slug' => $slug
            ]);
        }
        return $value;
    }

    public function truyenfullpost()
    {
        $cats = ['http://truyenfull.vn/danh-sach/truyen-hot/hoan/'];
        // $category_page_link = 'http://truyenfull.vn/danh-sach/truyen-hot/hoan/trang-[page_number]/';
        $category_page_link = '';
        $category_page_start = 2;
        $category_page_end = 291;
        //check paging. neu trang ket thuc > 1 va co link mau trang thi moi lay ds link trang
        if(!empty($category_page_link) && !empty($category_page_end) && $category_page_end > 1) {
            for($i = $category_page_start; $i <= $category_page_end; $i++) {
                $cats[] = str_replace('[page_number]', $i, $category_page_link);
            }
        }
        if(count($cats) > 0) {
            foreach($cats as $key => $value) {
                $htmlString = CommonMethod::get_remote_data($value);
                // get all link cat
                $html = HtmlDomParser::str_get_html($htmlString); // Create DOM from URL or file
                foreach($html->find('h3.truyen-title a') as $element) {
                    $links[$key][] = trim($element->href);
                }
                foreach($html->find('h3.truyen-title a') as $element) {
                    $titles[$key][] = trim($element->plaintext);
                }
                foreach($html->find('span.author') as $element) {
                    $authors[$key][] = trim($element->plaintext);
                }
                if(count($authors[$key]) > 0) {
                    foreach($authors[$key] as $vauthor) {
                        if(strpos($vauthor, ',') === false) {
                            $aut = PostTag::where('name', $vauthor)->first();
                            if(isset($aut)) {
                                self::insertPost([$aut->id], $links[$key], $titles[$key]);
                            }
                        } else {
                            $vauthors = explode(',', $vauthor);
                            $auts = [];
                            foreach($vauthors as $v) {
                                $aut = PostTag::where('name', $v)->first();
                                if(isset($aut)) {
                                    $auts[] = $aut->id;
                                }
                            }
                            if(!empty($auts)) {
                                self::insertPost($auts, $links[$key], $titles[$key]);
                            }
                        }
                    }
                }
            }
        }
        return redirect()->route('admin.crawler2.index')->with('success', 'Thêm thành công');
    }

    private function insertPost($authorIds, $links, $titles) {
        $author = trim($author);
        $slug = CommonMethod::convert_string_vi_to_en($author);
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/i', '-', $slug));
        $checkSlug = Post::where('slug', $slug)->first();
        if(count($checkSlug) == 0) {
            //insert tag
            $data = Post::create([
                'name' => $value,
                'slug' => $slug
            ]);
            if($data) {
                // insert game type relation
                $data->posttags()->attach([$request->type_main_id]);
            }
        }
        return $value;
    }

}
