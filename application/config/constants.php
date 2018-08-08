<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | Display Debug backtrace
  |--------------------------------------------------------------------------
  |
  | If set to TRUE, a backtrace will be displayed along with php errors. If
  | error_reporting is disabled, the backtrace will not display, regardless
  | of this setting
  |
 */
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
defined('FILE_READ_MODE') OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') OR define('DIR_WRITE_MODE', 0755);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */
defined('FOPEN_READ') OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
  |--------------------------------------------------------------------------
  | Exit Status Codes
  |--------------------------------------------------------------------------
  |
  | Used to indicate the conditions under which the script is exit()ing.
  | While there is no universal standard for error codes, there are some
  | broad conventions.  Three such conventions are mentioned below, for
  | those who wish to make use of them.  The CodeIgniter defaults were
  | chosen for the least overlap with these conventions, while still
  | leaving room for others to be defined in future versions and user
  | applications.
  |
  | The three main conventions used for determining exit status codes
  | are as follows:
  |
  |    Standard C/C++ Library (stdlibc):
  |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
  |       (This link also contains other GNU-specific conventions)
  |    BSD sysexits.h:
  |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
  |    Bash scripting:
  |       http://tldp.org/LDP/abs/html/exitcodes.html
  |
 */
defined('EXIT_SUCCESS') OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

$root_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
$root = $root_url . str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
define('SITEURL', $root);
define('SITENAME', 'Offerat');

define('GOOGLE_API_KEY', 'AIzaSyBREMF2gH26r6gNypcVlo_-PSU_qIh2Yu8');

define('site_support_email', 'support@offerat.sale');
define('site_info_email', 'info@offerat.sale');

define('VERIFICATION_RESET_PASSWORD', 'reset_password');
define('VERIFICATION_ACCOUNT', 'account_verification');
define('VERIFICATION_CHANGE_EMAIL', 'email_change_verification');

define('tbl_country', 'country');
define('tbl_category', 'category');
define('tbl_favorite', 'favorite');
define('tbl_mall', 'mall');
define('tbl_offer_announcement', 'offer_announcement');
define('tbl_offer_announcement_image', 'offer_announcement_image');
define('tbl_store', 'store');
define('tbl_store_category', 'store_category');
define('tbl_store_location', 'store_location');
define('tbl_sub_category', 'sub_category');
define('tbl_user', 'user');
define('tbl_verification', 'verification');
define('tbl_place', 'place');
define('tbl_sales_trend', 'sales_trend');
define('tbl_click_store', 'click_store');
define('tbl_sponsored_log', 'sponsored_log');

/* define('country_img_path', 'assets/images/country/');
  define('category_img_path', 'assets/images/category/');
  define('sub_category_img_path', 'assets/images/subcategory/');
  define('store_img_path', 'assets/images/store/'); */

define('default_img_path', 'assets/user/images/');

if ($_SERVER['HTTP_HOST'] == 'localhost') {
    define('country_img_path', '/media/CountryFlag/');
    define('category_img_path', '/media/CategoryIcon/');
    define('sub_category_img_path', '/media/SubCategoryIcon/');
    define('store_img_path', '/media/StoreLogo/');
    define('location_excel_img_path', '/media/LocationsExcels/');
    define('offer_media_path', '/media/OfferMedia/');
    define('offer_media_thumbnail_path', '/media/OfferMediaThumbail/');
    define('offer_img_start_part', $root . 'assets/user/timthumb1.php?src=' . $root_url . '/media/OfferMedia/');
} elseif ($_SERVER['HTTP_HOST'] == 'offerat.sale') {
    define('country_img_path', '/media/CountryFlag/');
    define('category_img_path', '/media/CategoryIcon/');
    define('sub_category_img_path', '/media/SubCategoryIcon/');
    define('store_img_path', '/media/StoreLogo/');
    define('location_excel_img_path', '/media/LocationsExcels/');
    define('offer_media_path', '/media/OfferMedia/');
    define('offer_media_thumbnail_path', '/media/OfferMediaThumbail/');
    define('offer_img_start_part', $root . 'assets/user/timthumb1.php?src=' . $root_url . '/media/OfferMedia/');
} else {
    define('country_img_path', '/PG/TG/Offerat/media/CountryFlag/');
    define('category_img_path', '/PG/TG/Offerat/media/CategoryIcon/');
    define('sub_category_img_path', '/PG/TG/Offerat/media/SubCategoryIcon/');
    define('store_img_path', '/PG/TG/Offerat/media/StoreLogo/');
    define('location_excel_img_path', '/PG/TG/Offerat/media/LocationsExcels/');
    define('offer_media_path', '/PG/TG/Offerat/media/OfferMedia/');
    define('offer_media_thumbnail_path', '/PG/TG/Offerat/media/OfferMediaThumbail/');
    define('offer_img_start_part', $root . 'assets/user/timthumb1.php?src=' . $root_url . '/PG/TG/Offerat/media/OfferMedia/');
}

define('offer_img_end_part', '&zc=0&w=150&h=150&q=70');

define('SUPER_ADMIN_USER_TYPE', '1');
define('COUNTRY_ADMIN_USER_TYPE', '2');
define('STORE_OR_MALL_ADMIN_USER_TYPE', '3');
define('NORMAL_USER_TYPE', '4');

define('ACTIVE_STATUS', 0);
define('IN_ACTIVE_STATUS', 1);
define('NOT_VERIFIED_STATUS', -1);

define('MALL_LOCATION_TYPE', 0);
define('STORE_LOCATION_TYPE', 1);

define('IS_NOT_DELETED_STATUS', 0);
define('IS_DELETED_STATUS', 1);

define('OFFER_OFFER_TYPE', 0);
define('ANNOUNCEMENT_OFFER_TYPE', 1);

define('UNFAVORITE_TYPE', 0);
define('FAVORITE_TYPE', 1);

define('SPONSORED_TYPE', 1);
define('UNSPONSORED_TYPE', 0);

define('NOTIFICATION_DISABLED', 0);
define('NOTIFICATION_ENABLED', 1);

define('IMAGE_OFFER_CONTENT_TYPE', 0);
define('VIDEO_OFFER_CONTENT_TYPE', 1);
define('TEXT_OFFER_CONTENT_TYPE', 2);

define('MEDIA_THUMB_IMAGE_WIDTH', '30');
define('MEDIA_THUMB_IMAGE_HEIGHT', '30');

define('IOS_DEVICE_TYPE', 0);
define('ANDROID_DEVICE_TYPE', 1);

//define('FFMPEG_PATH', 'C:/FFMPEG/bin/ffmpeg.exe');
define('FFMPEG_PATH', '/usr/bin/ffmpeg');

define('SUCCESS_CHANGE_EMAIL', 'Verification Email Sent. Please click on link in Email to complete the verification, Email change will take effect after verification.');

if ($_SERVER['HTTP_HOST'] == 'localhost')
    define('GOOGLE_PUSH_NOTIFICATION_API_KEY', 'AAAA6XFXelw:APA91bERf913DmHsnc34eri6k9_5D7qhK7rAgW1-KLz4pP_2ZaPR1RaOIbdxXl0lRDJ0r2Ap_-crQ1qUkGMIbM3QQ3q9TUDj4zIqhZB5j0KHldNVq0RakkbVS-hF_JFtCctQ4x4jdvQEpYayzUhgLHOnBak_k8w-Qg');
else
    define('GOOGLE_PUSH_NOTIFICATION_API_KEY', 'AAAAmzBgjPI:APA91bFR80_TrE10EEd8VWI3MZSr6Mrl1bBlgMQ6kD6JQpKu0XsYPd2Za_IT92OAiXqO3dkFilsUA49s7IXQXODRZEJR3YelzVTKSizb1Jds-UwefnuuuwZHd_3qkbQBa4D5Sx3lBlAcNlkUhCPyQ5iXpqRuxzybGg');

define('MAX_OFFER_IMAGE_UPLOAD', 50);
