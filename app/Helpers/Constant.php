<?php 
// Function global
use Jenssegers\Agent\Agent;

function getDevice2() {
    $agent = new Agent();
    if($agent->isTablet()) {
        return TABLET;
    } else if($agent->isMobile()) {
        return MOBILE;
    } else {
        return PC;
    }
}

function trimRequest($request) {
    $input = $request->all();
    // use a closure here because array_walk_recursive passes
    // two params to the callback, the item + the key. If you were to just
    // call trim directly, you could end up inadvertently trimming things off
    // your array values, and pulling your hair out to figure out why.
    array_walk_recursive($input, static function(&$in) {
        $in = trim($in);
    });
    $request->merge($input);
}
// End function

// device
define('MOBILE', 1);
define('PC', 2);
define('TABLET', 3);
// cache: 1: cache, 2: non cache
define('CACHE', 2);
// lang
define('VI', 'vi');
define('EN', 'en');
// admin role id
define('ADMIN', 1);
define('EDITOR', 2);
// trang thai
define('ACTIVE', 1);
define('INACTIVE', 2);
// loai post
define('POST_LONG', 1);
define('POST_SHORT', 2);
// menu top nam ngang
define('MENUTYPE1', 1);
// menu side
define('MENUTYPE2', 2);
// menu bottom
define('MENUTYPE3', 3);
//  menu mobile
define('MENUTYPE4', 4);
// pagination
define('PAGINATION', 30);
define('PAGINATE', 32);
define('PAGINATE_LIST', 12);
define('PAGINATE_TABLE', 34);
define('PAGINATE_RELATED', 6);
define('PAGINATE_BOX', 36);
// SLIDER
define('SLIDER1', 1); // slider on top home page
define('SLIDER2', 2); // slider on bottom page
define('SLIDER3', 3); //  
// replace string
define('CONTACTFORM', '/%ContactForm%/');
// trang thai crawler
define('CRAW_POST', 1);
define('CRAW_CATEGORY', 2);
// image crawler
define('CRAW_POST_IMAGE', 1);
define('CRAW_CATEGORY_IMAGE', 2);
// crawler lay tieu de tu trang category hay tu trang chi tiet post
define('CRAW_TITLE_POST', 1);
define('CRAW_TITLE_CATEGORY', 2);
// display
define('DISPLAY_1', 1); // Hình ảnh kèm tiêu đề
define('DISPLAY_2', 2); // Chỉ hiển thị tiêu đề
// responsive filemanager
define('AKEY', 'db0ac2431a2e87c54852dbb0e7b9ed3d');
// slug type
define('SLUGTYPE1', 1); // lay slug theo tieu de bai viet lay duoc
define('SLUGTYPE2', 2); // lay slug theo slug cua link nguon bai viet
define('SLUGTYPE3', 3); // lay slug theo danh sach slug tuong ung voi danh sach link nguon bai viet
// title type
define('TITLETYPE1', 1); // Lấy tiêu đề bài tự động theo mẫu thẻ lấy tiêu đề
define('TITLETYPE2', 2); // Lấy tiêu đề tự động theo danh sách slug tương ứng ds link nguồn
define('TITLETYPE3', 3); // Lấy tiêu đề theo danh sách tiêu đề tương ứng ds link nguồn
// thumbnail image size
define('THUMB_DIMENSIONS', '320x420 / 160x210 / 80x80');
define('IMAGE_WIDTH', 160);
define('IMAGE_HEIGHT', 210);
define('IMAGE_WIDTH_2', 80);
define('IMAGE_HEIGHT_2', 80);
define('IMAGE_WIDTH_3', 320);
define('IMAGE_HEIGHT_3', 420);
define('SLIDE_HEADER_DIMENSIONS', '775x380');
define('SLIDE_FOOTER_DIMENSIONS', '200x120');
// SLUG
define('SLUG_NATION_JAPAN', 'nhat-ban');
define('SLUG_NATION_USA', 'au-my');
define('SLUG_NATION_KOREAN', 'han-quoc');
define('SLUG_NATION_CHINA', 'trung-quoc');
define('SLUG_NATION_VIETNAM', 'viet-nam');
define('SLUG_NATION_OTHER', 'nuoc-khac');
define('SLUG_POST_KIND_FULL', 'da-hoan-thanh');
define('SLUG_POST_KIND_UPDATING', 'con-tiep-tuc');
// cookie name
define('COOKIE_NAME', 'clients');
// watermark base64 code
define('WATERMARK_BASE64', 'data:image/jpeg;base64,/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAABQAAD/4QMraHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjMtYzAxMSA2Ni4xNDU2NjEsIDIwMTIvMDIvMDYtMTQ6NTY6MjcgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDUzYgKFdpbmRvd3MpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkFGRDA1MUQzNUUyODExRTdBNEFGQTQ5QUVCNjFBNUFEIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkFGRDA1MUQ0NUUyODExRTdBNEFGQTQ5QUVCNjFBNUFEIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6QUZEMDUxRDE1RTI4MTFFN0E0QUZBNDlBRUI2MUE1QUQiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6QUZEMDUxRDI1RTI4MTFFN0E0QUZBNDlBRUI2MUE1QUQiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7/7gAmQWRvYmUAZMAAAAABAwAVBAMGCg0AAAgLAAAM2gAAERcAABdB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');
