<?php 
namespace App\Helpers;

class CommonUrl
{
	static function getUrl($slug, $withDomain = null)
    {
        if($withDomain != null) {
            return '/'.$slug;    
        }
        return url($slug);
    }
    static function getUrl2($slug1, $slug2, $withDomain = null)
    {
    	if($withDomain != null) {
    	   return '/'.$slug1.'/'.$slug2;	
    	}
        return url($slug1.'/'.$slug2);
    }
    static function getUrlPostTag($slug, $withDomain = null)
    {
    	if($withDomain != null) {
    	   return '/tac-gia/'.$slug;	
    	}
        return url('tac-gia/'.$slug);
    }
    static function getUrlPostType($slug, $withDomain = null)
    {
        if($withDomain != null) {
           return '/the-loai/'.$slug;    
        }
        return url('the-loai/'.$slug);
    }
    static function getUrlPostSeri($slug, $withDomain = null)
    {
        if($withDomain != null) {
           return '/seri/'.$slug;    
        }
        return url('seri/'.$slug);
    }
    static function getUrlPostNation($slug, $withDomain = null)
    {
        if($withDomain != null) {
           return '/doc-truyen-'.$slug;    
        }
        return url('doc-truyen-'.$slug);
    }
    static function getUrlPostKind($slug, $withDomain = null)
    {
        if($withDomain != null) {
           return '/danh-sach-truyen-'.$slug;    
        }
        return url('danh-sach-truyen-'.$slug);
    }

}