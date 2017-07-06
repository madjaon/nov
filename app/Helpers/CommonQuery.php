<?php 
namespace App\Helpers;

use DB;
use Request;

class CommonQuery
{
    static function getAllWithStatus($table, $status = ACTIVE, $orderByPosition = null)
    {
        $data = DB::table($table)->where('status', $status);
        if($orderByPosition != null) {
            $data = $data->orderByRaw(DB::raw("position = '0', position"))->get();
        } else {
            $data = $data->get();
        }
        if(count($data) > 0) {
            return $data;
        }
        return null;
    }
    static function getArrayWithStatus($table, $status = ACTIVE)
    {
        $data = DB::table($table)->where('status', $status);
        $data = $data->pluck('name', 'id');
        if(count($data) > 0) {
            return $data;
        }
        return null;
    }
    static function getFieldById($table, $id, $field, $fieldIsNumber = null)
    {
        $data = DB::table($table)->where('id', $id);
        $data = $data->first();
        if($data) {
            return $data->$field;
        }
        if(isset($fieldIsNumber)) {
            return 0;
        }
        return '';
    }
    static function getAdByPosition($position=null)
    {
        if($position == null) {
            return '';
        }
        $data = DB::table('ads')
            ->where('position', $position)
            ->where('status', ACTIVE)
            ->first();
        if($data) {
            return '<div class="my-3"><div class="container"><div class="text-center">'.$data->code.'</div></div></div>';
        }
        return '';
    }
    static function getArrayParentZero($table, $currentId=0)
    {
        $data = DB::table($table)
            ->select('id', 'name', 'parent_id')
            ->where('status', ACTIVE)
            ->where('parent_id', 0)
            ->where('id', '!=', $currentId);
        $data = $data->pluck('name', 'id');
        $firstValue = ($currentId!=0)?0:'';
        return array_add($data, $firstValue, '-- Chọn');
    }
    static function getArrayWithParent($table, $currentId=0)
    {
        $data = DB::table($table)
            ->select('id', 'name', 'parent_id')
            ->where('status', ACTIVE)
            ->where('id', '!=', $currentId);
        $data = $data->get();
        $firstValue = ($currentId!=0)?0:'';
        $output = self::_visit($data);
        return array_add($output, $firstValue, '-- Chọn');
    }
    static function _visit($data, $parentId=0, $prefix='')
    {
        $output = [];
        $current = self::_current($data, $parentId);
        $sub = self::_sub($data, $parentId);
        if(isset($current)) {
            $output[$current->id] = $prefix . $current->name;
            $prefix .= '-- ';
        }
        if(count($sub) > 0) {
            foreach($sub as $value) {
                $o = self::_visit($data, $value->id, $prefix);
                foreach($o as $k => $v) {
                    $output[$k] = $v;
                }
            }
        }
        return $output;
    }
    private static function _current($data, $parentId)
    {
        if(isset($data)) {
            foreach($data as $value) {
                if ($value->id == $parentId) {return $value;}
            }
        }
        return null;
    }
    private static function _sub($data, $parentId)
    {
        $sub = array();
        if(isset($data)) {
            foreach($data as $key => $value) {
                if ($value->parent_id == $parentId) {$sub[$key] = $value;}
            }
        }
        return $sub;
    }
    /**
    // check current menu
    // check menu parent, children, post
    **/
    static function checkCurrent($url, $home=null)
    {
        $currentUrl = Request::url();
        //check currentUrl post or type. follow menu table
        //1. get slug from currentUrl
        $uri = substr(strrchr($url, "/"), 1);
        $slug = substr(strrchr($currentUrl, "/"), 1);
        $slugs = array();
        $urls = array();
        //2. find all slug
        $type = DB::table('post_types')->select('parent_id')->where('slug', $slug)->where('status', ACTIVE)->first();
        if(isset($type) && isset($type->parent_id)) {
            $slugs[] = $slug;
            $parent_id = $type->parent_id;
            while($parent_id <> 0) {
                $ps = self::findType($parent_id);
                if(!empty($ps)) {
                    $slugs[] = $ps[1];
                }
                $parent_id = $ps[0];
            }
        } else {
            $post = DB::table('posts')->select('type_main_id')->where('slug', $slug)->where('status', ACTIVE)->first();
            if(isset($post) && isset($post->type_main_id)) {
                $slugs[] = $slug;
                $parent_id = $post->type_main_id;
                while($parent_id <> 0) {
                    $ps = self::findType($parent_id);
                    if(!empty($ps)) {
                        $slugs[] = $ps[1];
                        $parent_id = $ps[0];
                    } else {
                        break;
                    }
                }
            }
        }
        //3. find menu - url = / + slug
        $slugs = array_unique($slugs);
        if(count($slugs) > 0) {
            foreach($slugs as $key => $value) {
                $menu = DB::table('menus')->select('parent_id', 'url')->where('url', '/'.$value)->where('status', ACTIVE)->first();
                if(isset($menu) && isset($menu->parent_id)) {
                    $urls[] = $menu->url;
                    $parent_id = $menu->parent_id;
                    while($parent_id <> 0) {
                        $pu = self::findMenu($parent_id);
                        if(!empty($pu)) {
                            $urls[] = $pu[1];
                            $parent_id = $pu[0];
                        } else {
                            break;
                        }
                    }
                }
            }
        }
        $urls = array_unique($urls);
        if(in_array('/'.$uri, $urls)) {
            return 'current';
        }
        return;
    }
    static function findType($parent_id)
    {
        $type = DB::table('post_types')->select('parent_id', 'slug')->where('id', $parent_id)->where('status', ACTIVE)->first();
        if(isset($type) && isset($type->parent_id)) {
            return [$type->parent_id, $type->slug];
        }
        return;
    }
    static function findMenu($parent_id)
    {
        $menu = DB::table('menus')->select('parent_id', 'url')->where('id', $parent_id)->where('status', ACTIVE)->first();
        if(isset($menu) && isset($menu->parent_id)) {
            return [$menu->parent_id, $menu->url];
        }
        return;
    }
    // get 1 field array
    static function getArrayField($table, $field)
    {
        $data = DB::table($table)->select($field)->groupBy($field)->lists($field, $field);
        if(count($data) > 0) {
            return $data;
        }
        return null;
    }
    // CONTACT: thong bao lien he chua doc trong trang admin
    static function contactUnRead()
    {
        $data = DB::table('contacts')->where('status', INACTIVE)->count();
        if($data > 0) {
            return $data;
        }
        return '';
    }
    // HISTORY READING
    static function historyFromCookie()
    {
        $cookie = request()->cookie(COOKIE_NAME);
        if(!empty($cookie)) {
            // nemo-earum-quidem-earum-quaerat-sit-sit-_chuong-31
            $cookieArray = explode('_', $cookie);
            if(!empty($cookieArray)) {
                $slug1 = $cookieArray[0];
                $slug2 = $cookieArray[1];
                if(CACHE == 1) {
                    // cache name
                    $cacheName = 'history_'.$slug1.'_'.$slug2;
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
                    ->select('id', 'name', 'slug')
                    ->where('slug', $slug1)
                    ->where('status', ACTIVE)
                    ->where('start_date', '<=', date('Y-m-d H:i:s'))
                    ->first();
                if(isset($post)) {
                    // current epchap
                    $data = DB::table('post_eps')
                        ->select('id', 'name', 'slug', 'volume', 'epchap')
                        ->where('slug', $slug2)
                        ->where('post_id', $post->id)
                        ->where('status', ACTIVE)
                        ->where('start_date', '<=', date('Y-m-d H:i:s'))
                        ->first();
                    if(isset($data)) {
                        $data->postName = $post->name;
                        $data->url = CommonUrl::getUrl2($post->slug, $data->slug);
                        if($data->volume > 0) {
                            $data->epchapName = 'Quyển ' . $data->volume . ' chương ' . $data->epchap;
                        } else {
                            $data->epchapName = 'Chương ' . $data->epchap;
                        }
                        if(CACHE == 1) {
                            // put cache
                            $html = view('site.common.history', ['data' => $data])->render();
                            Cache::forever($cacheName, $html);
                        }
                        // return view
                        return view('site.common.history', ['data' => $data]);
                    }
                }
            }
        }
        return '';
    }

}