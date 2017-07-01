<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostType;
use App\Models\Crawler;
use DB;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Helpers\CommonMethod;
use App\Helpers\CommonQuery;
use Cache;
use Sunra\PhpSimple\HtmlDomParser;
use Image;

class CrawlerController extends Controller
{

    public function __construct()
    {
        if(Auth::guard('admin')->user()->role_id != ADMIN) {
            dd('Permission denied! Please back!');
        }
    }
    
    public function index(Request $request)
    {
        trimRequest($request);
        $postTypeArray = CommonQuery::getArrayWithStatus('post_types');
        if($postTypeArray == null) {
            $postTypeArray = [];
        }
        $crawlers = Crawler::get();
        if(!empty($request->id)) {
            $id = $request->id;
            $data = Crawler::where('id', $id)->first();
        } else {
            $id = '';
            $data = [];
        }
        return view('admin.crawler.index', ['data' => $data, 'crawlers' => $crawlers, 'request' => $request, 'postTypeArray' => $postTypeArray, 'id' => $id]);
    }

    public function save(Request $request)
    {
        trimRequest($request);
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'source' => 'required|max:255',
            'type_main_id' => 'required',
            'type' => 'required',
            'category_post_link_pattern' => 'max:255',
            'image_dir' => 'max:255',
            'image_pattern' => 'max:255',
            'title_pattern' => 'max:255',
            'description_pattern' => 'max:255',
            'element_delete' => 'max:255',
            'element_delete_positions' => 'max:255',
            'time_get' => 'max:255',
        ]);
        if($validator->fails()) {
            return 2;
        }
        if(empty($request->id)) {
            $data = Crawler::create([
                        'name' => $request->name,
                        'source' => $request->source,
                        'slug_type' => $request->slug_type,
                        'post_slugs' => $request->post_slugs,
                        'title_type' => $request->title_type,
                        'post_titles' => $request->post_titles,
                        'post_links' => $request->post_links,
                        'category_link' => $request->category_link,
                        'category_page_link' => $request->category_page_link,
                        'category_page_start' => $request->category_page_start,
                        'category_page_end' => $request->category_page_end,
                        'category_post_link_pattern' => $request->category_post_link_pattern,
                        'type_main_id' => $request->type_main_id,
                        'type' => $request->type,
                        'image_dir' => $request->image_dir,
                        'image_pattern' => $request->image_pattern,
                        'image_check' => $request->image_check,
                        'title_post_check' => $request->title_post_check,
                        'title_pattern' => $request->title_pattern,
                        'description_pattern' => $request->description_pattern,
                        'description_pattern_delete' => $request->description_pattern_delete,
                        'element_delete' => $request->element_delete,
                        'element_delete_positions' => $request->element_delete_positions,
                        'start_date' => CommonMethod::datetimeConvert($request->start_date, $request->start_time),
                        'status' => $request->status,
                    ]);
        } else {
            $data = Crawler::find($request->id);
            if($data) {
                $data->update([
                    'name' => $request->name,
                    'source' => $request->source,
                    'slug_type' => $request->slug_type,
                    'post_slugs' => $request->post_slugs,
                    'title_type' => $request->title_type,
                    'post_titles' => $request->post_titles,
                    'post_links' => $request->post_links,
                    'category_link' => $request->category_link,
                    'category_page_link' => $request->category_page_link,
                    'category_page_start' => $request->category_page_start,
                    'category_page_end' => $request->category_page_end,
                    'category_post_link_pattern' => $request->category_post_link_pattern,
                    'type_main_id' => $request->type_main_id,
                    'type' => $request->type,
                    'image_dir' => $request->image_dir,
                    'image_pattern' => $request->image_pattern,
                    'image_check' => $request->image_check,
                    'title_post_check' => $request->title_post_check,
                    'title_pattern' => $request->title_pattern,
                    'description_pattern' => $request->description_pattern,
                    'description_pattern_delete' => $request->description_pattern_delete,
                    'element_delete' => $request->element_delete,
                    'element_delete_positions' => $request->element_delete_positions,
                    'start_date' => CommonMethod::datetimeConvert($request->start_date, $request->start_time),
                    'status' => $request->status,
                ]);
            } else {
                return 0;
            }
        }
        return 1;
    }

    public function destroy($id)
    {
        // $data = Post::find($id);
        // $data->delete();
        // return redirect()->route('admin.crawler.index')->with('success', 'Xóa thành công');
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
                    $slug = getSlugFromUrl($link);
                } else if($request->slug_type == SLUGTYPE3) {
                    if(!empty($request->post_slugs)) {
                        $slugs = explode(',', $request->post_slugs);
                        $slug = $slugs[$key];
                    } else {
                        $slug = getSlugFromUrl($link);
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

    public function genthumb()
    {
        //get all image in all table (has image field)
        $data = array();
        $images = array();
        $images1 = DB::table('posts')->where('image', '!=', '')->lists('image');
        $images2 = DB::table('post_types')->where('image', '!=', '')->lists('image');
        $images3 = DB::table('post_tags')->where('image', '!=', '')->lists('image');
        $images4 = DB::table('pages')->where('image', '!=', '')->lists('image');
        $images1 = array_merge($images1, $images2);
        $images1 = array_merge($images1, $images3);
        $images = array_merge($images1, $images4);
        //tim domain cua host
        $domainSource = CommonMethod::getDomainSource();
        //vong lap kiem tra anh goc neu co thi moi tao thumbnail
        if(count($images) > 0 && !empty($domainSource)) {
            foreach($images as $key => $value) {
                $filepath = public_path().$value;
                // link anh co the la thumb hoac khong (truoc lay tu dong nen vay).
                // mac dinh anh image luon la anh thumb (co chua /thumb/ - folder thumb/)
                if(strpos($value, '/thumb/') !== false) {
                    // filepath anh trong cac folder thumbnail
                    // $filepath: thumb/
                    // filepath2: thumb2/
                    // filepath3: thumb3/
                    $filepath2 = str_replace('/thumb/', '/thumb2/', $filepath);
                    $filepath3 = str_replace('/thumb/', '/thumb3/', $filepath);
                    if(!file_exists($filepath) || !file_exists($filepath2) || !file_exists($filepath3)) {
                        $dir = dirname($value);
                        $name = basename($value);
                        //bo /images/ phia truoc dir de lay savePath
                        $savePath = substr($dir, 8);
                        //bo /thumb phia sau dir
                        $originDir = substr($dir, 0, -6);
                        $imageUrl = $originDir.'/'.$name;
                        if(file_exists(public_path().$imageUrl)) {
                            $data[] = CommonMethod::createThumb($imageUrl, $domainSource, $savePath, IMAGE_WIDTH, IMAGE_HEIGHT);
                            // check save path la thu-muc/thumb hay thumb
                            if(strpos($savePath, '/thumb') !== false) {
                                $savePath2 = str_replace('/thumb', '/thumb2', $savePath);
                                $savePath3 = str_replace('/thumb', '/thumb3', $savePath);
                            } else {
                                $savePath2 = 'thumb2';
                                $savePath3 = 'thumb3';
                            }
                            CommonMethod::createThumb($imageUrl, $domainSource, $savePath2, IMAGE_WIDTH_2, IMAGE_HEIGHT_2);
                            CommonMethod::createThumb($imageUrl, $domainSource, $savePath3, IMAGE_WIDTH_3, IMAGE_HEIGHT_3);
                        }
                    }
                } else {
                    //if exist image then return result
                    if(file_exists($filepath)) {
                        $imageUrl = $value;
                        //bo /images/ phia truoc value
                        $value = substr($value, 8);
                        $dir = dirname($value);
                        $name = basename($value);
                        // them /thumb phia sau dir de tao savePath
                        $savePath = $dir . '/thumb';
                        $savePath2 = $dir . '/thumb2';
                        $savePath3 = $dir . '/thumb3';
                        $thumb = '/images/'.$savePath.'/'.$name;
                        $thumb2 = '/images/'.$savePath2.'/'.$name;
                        $thumb3 = '/images/'.$savePath3.'/'.$name;
                        if(!file_exists(public_path().$thumb) || !file_exists(public_path().$thumb2) || !file_exists(public_path().$thumb3)) {
                            $data[] = CommonMethod::createThumb($imageUrl, $domainSource, $savePath, IMAGE_WIDTH, IMAGE_HEIGHT);
                            CommonMethod::createThumb($imageUrl, $domainSource, str_replace('/thumb', '/thumb2', $savePath2), IMAGE_WIDTH_2, IMAGE_HEIGHT_2);
                            CommonMethod::createThumb($imageUrl, $domainSource, str_replace('/thumb', '/thumb3', $savePath3), IMAGE_WIDTH_3, IMAGE_HEIGHT_3);
                        }
                    }
                }
            }
        }
        return view('admin.crawler.genthumb', ['data' => $data]);
    }

    // Load trang gen water mark
    public function genwatermark()
    {
        return view('admin.crawler.genwatermark', ['data' => ['total' => null]]);
    }
    // WARTERMARK
    // tao watermark cho tat ca anh trong thu muc (anh goc, khong phai anh trong thu muc thumb/)
    // tim toan bo anh -> sua duong dan anh -> loai bo cac anh trong thu muc /thumb/ -> gen watermark
    public function genwatermarkAction(Request $request)
    {
        //tim domain cua host
        $domainSource = CommonMethod::getDomainSource();
        trimRequest($request);

        $dir = !empty($request->dir)?$request->dir:'images/';
        $code = !empty($request->code)?$request->code:null;
        $position = !empty($request->position)?$request->position:null;
        // get all images to gen watermark
        $lists = self::getimagestogenwatermark($dir);
        // gen watermark
        foreach($lists as $value) {
            //bo /images/ phia truoc dir de lay savePath
            $savePath = substr(dirname($value), 8);
            // return $imageOrigin
            CommonMethod::createWatermark($value, $domainSource, $savePath, $code, $position);
        }
        return redirect('admin/genwatermark')->with('success', 'Đã tạo watermark cho '.count($lists).' ảnh');
    }

    // get all images no inside thumb/ folder
    private function getimagestogenwatermark($dir = 'images/')
    {
        $lists = self::get_filelist_as_array($dir);
        // thay the dau \ thanh dau /
        $lists = str_replace('\\', '/', $lists);
        foreach($lists as $key => $value) {
            // sua duong dan anh
            $lists[$key] = '/'.$dir.$value;
            // xoa bo value co chua /thumb/ (khong watermark thumbnail)
            if(strpos($lists[$key], '/thumb/') !== false) {
                unset($lists[$key]);
            }
        }
        return $lists;
    }

    // list all files as array
    private function get_filelist_as_array($dir = 'images/', $recursive = true, $basedir = '')
    {
        if ($dir == '') {return array();} else {$results = array(); $subresults = array();}
        if (!is_dir($dir)) {$dir = dirname($dir);} // so a files path can be sent
        if ($basedir == '') {$basedir = realpath($dir).DIRECTORY_SEPARATOR;}

        $files = scandir($dir);
        foreach ($files as $key => $value){
            if ( ($value != '.') && ($value != '..') ) {
                $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
                if (is_dir($path)) { // do not combine with the next line or..
                    if ($recursive) { // ..non-recursive list will include subdirs
                        $subdirresults = self::get_filelist_as_array($path,$recursive,$basedir);
                        $results = array_merge($results,$subdirresults);
                    }
                } else { // strip basedir and add to subarray to separate file list
                    $subresults[] = str_replace($basedir,'',$path);
                }
            }
        }
        // merge the subarray to give the list of files then subdirectory files
        if (count($subresults) > 0) {$results = array_merge($subresults,$results);}
        return $results;
    }

    // YET USING
    // list file & folder tree
    private function listFolderFiles($dir = 'images/')
    {
        echo '<ol>';
        foreach (new \DirectoryIterator($dir) as $fileInfo) {
            if (!$fileInfo->isDot()) {
                echo '<li>' . $fileInfo->getFilename();
                if ($fileInfo->isDir()) {
                    self::listFolderFiles($fileInfo->getPathname());
                }
                echo '</li>';
            }
        }
        echo '</ol>';
    }

    // YET USING
    // get all dirs only
    private function getAllDirs($directory = 'images/', $directory_seperator = '/')
    {
        $dirs = array_map(function ($item) use ($directory_seperator) {
            return $item . $directory_seperator;
        }, array_filter(glob($directory . '*'), 'is_dir'));

        foreach ($dirs AS $dir) {
            $dirs = array_merge($dirs, self::getAllDirs($dir, $directory_seperator));
        }
        return $dirs;
    }

    // YET USING
    // get all image *.jpg
    private function getAllImgsJpg($directory = 'images/')
    {
        $resizedFilePath = array();
        foreach ($directory AS $dir) {
            foreach (glob($dir . '*.jpg') as $filename) {
                array_push($resizedFilePath, $filename);
            }
        }
        return $resizedFilePath;
    }

}
