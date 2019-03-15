<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | -------------------------------------------------------------------------
  | URI ROUTING
  | -------------------------------------------------------------------------
  | This file lets you re-map URI requests to specific controller functions.
  |
  | Typically there is a one-to-one relationship between a URL string
  | and its corresponding controller class/method. The segments in a
  | URL normally follow this pattern:
  |
  |	example.com/class/method/id/
  |
  | In some instances, however, you may want to remap this relationship
  | so that a different class/function is called than the one
  | corresponding to the URL.
  |
  | Please see the user guide for complete details:
  |
  |	https://codeigniter.com/user_guide/general/routing.html
  |
  | -------------------------------------------------------------------------
  | RESERVED ROUTES
  | -------------------------------------------------------------------------
  |
  | There are three reserved routes:
  |
  |	$route['default_controller'] = 'welcome';
  |
  | This route indicates which controller class should be loaded if the
  | URI contains no data. In the above example, the "welcome" class
  | would be loaded.
  |
  |	$route['404_override'] = 'errors/page_missing';
  |
  | This route will tell the Router which controller/method to use if those
  | provided in the URL cannot be matched to a valid route.
  |
  |	$route['translate_uri_dashes'] = FALSE;
  |
  | This is not exactly a route, but allows you to automatically route
  | controller and method names that contain dashes. '-' isn't a valid
  | class or method name character, so it requires translation.
  | When you set this option to TRUE, it will replace ALL dashes in the
  | controller and method URI segments.
  |
  | Examples:	my-controller/index	-> my_controller/index
  |		my-controller/my-method	-> my_controller/my_method
 */
$route['default_controller'] = 'home';
$route['page_404'] = 'home/page_404';

$route['js_disabled'] = "home/js_disabled";

//reset password
$route['reset-password'] = 'login/reset_password';
//change password
$route['change-password'] = 'login/change_password';

//Super Admin Routes =================================
$route['super-admin'] = 'Superadmin/dashboard';
$route['super-admin/dashboard'] = 'Superadmin/dashboard';
$route['super-admin/change-password'] = 'Common/change_password';
$route['super-admin/change-information'] = 'Common/change_information';

$route['superadmin/country/filter_countries'] = 'Superadmin/Country/filter_countries';
$route['superadmin/category/filter_categories'] = 'Superadmin/Category/filter_categories';
$route['superadmin/subcategory/filter_sub_categories/(:any)'] = 'Superadmin/Subcategory/filter_sub_categories/$1';

//Country
$route['super-admin/country'] = 'Superadmin/country';
$route['super-admin/country/save'] = 'Superadmin/country/save';
$route['super-admin/country/save/(:any)'] = 'Superadmin/country/save/$1';
$route['superadmin/country/delete/(:any)'] = 'Superadmin/country/delete/$1';

//Category
$route['super-admin/category'] = 'Superadmin/category';
$route['super-admin/category/save'] = 'Superadmin/category/save';
$route['super-admin/category/save/(:any)'] = 'Superadmin/category/save/$1';
$route['superadmin/category/delete/(:any)'] = 'Superadmin/category/delete/$1';

//Terms & Condition
$route['super-admin/terms-conditions'] = 'Superadmin/TermsCondition';
$route['super-admin/terms-conditions/edit/(:any)'] = 'Superadmin/TermsCondition/save/$1';
$route['super-admin/terms-conditions/save/(:any)'] = 'Superadmin/TermsCondition/save/$1';
$route['super-admin/terms-conditions/filter_terms_condition'] = 'Superadmin/TermsCondition/filter_terms_condition';

//Sub-category
$route['super-admin/sub-category/(:any)'] = 'Superadmin/subcategory/index/$1';
$route['super-admin/sub-category/save/(:any)'] = 'Superadmin/subcategory/save/$1';
$route['super-admin/sub-category/save/(:any)/(:any)'] = 'Superadmin/subcategory/save/$1/$2';
$route['superadmin/subcategory/delete/(:any)/(:any)'] = 'Superadmin/subcategory/delete/$1/$2';



//Country Admin Routes =================================
$route['country-admin'] = 'Countryadmin/dashboard';
$route['country-admin/dashboard'] = 'Countryadmin/dashboard';
$route['country-admin/verify-store-offers'] = 'Countryadmin/verify_offers/stores';
$route['country-admin/filter_store_offers'] = 'Countryadmin/verify_offers/filter_store_offers';
$route['country-admin/verify-mall-offers'] = 'Countryadmin/verify_offers/malls';
$route['country-admin/filter_mall_offers'] = 'Countryadmin/verify_offers/filter_mall_offers';
$route['country-admin/change-password'] = 'Common/change_password';
$route['country-admin/change-information'] = 'Common/change_information';
$route['country-admin/notifications/remove_image_uploaded'] = 'Common/notifications/remove_image_uploaded';
$route['country-admin/notifications/get_notification_details/(:any)'] = 'Common/notifications/get_notification_details/$1';
$route['country-admin/notifications/images/(:any)'] = 'Common/notifications/images/$1';
$route['country-admin/notifications/delete_images/(:any)'] = 'Common/notifications/delete_images/$1';
$route['country-admin/notifications/update_images_sort_order/(:any)'] = 'Common/notifications/update_images_sort_order/$1';
$route['country-admin/notifications/(:any)/save'] = 'Common/notifications/save/$1';
$route['country-admin/notifications/(:any)/save/(:any)'] = 'Common/notifications/save/$1/$2';

$route['country-admin/notifications/(:any)/save/(:any)/(:any)'] = 'Common/notifications/save/$1/$2/$3';
$route['country-admin/notifications/(:any)'] = 'Common/notifications/index/$1';
$route['country-admin/notifications/(:any)/(:any)'] = 'Common/notifications/index/$1/$2';
$route['country-admin/filter_notifications/(:any)'] = 'Common/notifications/filter_notifications/$1';
$route['country-admin/filter_notifications/(:any)/(:any)'] = 'Common/notifications/filter_notifications/$1/$2';
$route['country-admin/dashboard/filter_notifications'] = 'Countryadmin/dashboard/filter_notifications';
$route['country-admin/notifications/(:any)/delete/(:any)/(:any)'] = 'Common/notifications/delete/$1/$2/$3';
$route['country-admin/notifications/(:any)/delete/(:any)'] = 'Common/notifications/delete/$1/$2';
$route['country-admin/notifications/store/category/get'] = 'Common/notifications/get_categories';
$route['country-admin/notifications/store/posted_date/get'] = 'Common/notifications/last_posted_date';
$route['country-admin/notifications/store/offer/category/save'] = 'Common/notifications/save_non_store_category_to_offers';
$route['country-admin/notifications/store/offer/last-post-date/get'] = 'Common/notifications/get_store_offer_last_posted_date';

$route['country-admin/stores'] = 'Common/stores/index';
$route['country-admin/stores/filter_stores'] = 'Common/stores/filter_stores';
$route['country-admin/dashboard/filter_stores'] = 'Countryadmin/dashboard/filter_stores';
$route['country-admin/stores/get_store_details/(:any)'] = 'Common/stores/get_store_details/$1';
$route['country-admin/stores/save'] = 'Common/stores/save';
$route['country-admin/stores/save/(:any)'] = 'Common/stores/save/$1';
$route['country-admin/stores/locations/(:any)'] = 'Common/stores/locations/$1';
$route['country-admin/stores/delete_locations/(:any)'] = 'Common/stores/delete_locations/$1';
$route['country-admin/stores/delete/(:any)'] = 'Common/stores/delete/$1';
$route['country-admin/stores/sponsored/(:any)'] = 'Common/stores/sponsored/$1';
$route['country-admin/stores/sponsored/delete/(:any)'] = 'Common/stores/delete_sponsored/$1';
$route['country-admin/malls'] = 'Common/malls/index';
$route['country-admin/malls/filter_malls'] = 'Common/malls/filter_malls';
$route['country-admin/malls/get_mall_details/(:any)'] = 'Common/malls/get_mall_details/$1';
$route['country-admin/stores/loacation_excel_download/(:any)'] = 'Common/stores/loacation_excel_download/$1';
$route['country-admin/stores/loacation_excel_format_download'] = 'Common/stores/loacation_excel_format_download';
$route['country-admin/stores/mall_excel_format_download'] = 'Common/stores/mall_excel_format_download';
$route['country-admin/malls/delete/(:any)'] = 'Common/malls/delete/$1';
$route['country-admin/malls/sponsored/(:any)'] = 'Common/malls/sponsored/$1';
$route['country-admin/malls/sponsored/delete/(:any)'] = 'Common/malls/delete_sponsored/$1';
$route['country-admin/sponsored/stores'] = 'Countryadmin/sponsored/stores';
$route['country-admin/sponsored/malls'] = 'Countryadmin/sponsored/malls';
$route['country-admin/sponsored/details/(:any)'] = 'Countryadmin/sponsored/get_store_sponseored_details/$1';
$route['country-admin/sponsored/mall_details/(:any)'] = 'Countryadmin/sponsored/get_mall_sponseored_details/$1';
$route['country-admin/malls/save'] = 'Common/malls/save';
$route['country-admin/malls/save/(:any)'] = 'Common/malls/save/$1';
$route['country-admin/malls/store/edit/(:any)'] = 'Common/malls/edit_store/$1';
$route['country-admin/malls/update_store_floor_number'] = 'Common/malls/update_store_floor_number';
$route['country-admin/malls/store/location/edit/(:any)'] = 'Common/malls/edit_store_location/$1';
$route['country-admin/malls/check_file_exists'] = 'Common/malls/check_file_exists';
$route['country-admin/malls/update_store_locations'] = 'Common/malls/update_store_locations';
$route['country-admin/report/(:any)/(:any)'] = 'Common/report/index/$1/$2';
$route['country-admin/report/push_notifications/(:any)/(:any)'] = 'Common/report/filter_notifications/$1/$2';
$route['country-admin/upload/index'] = 'Common/Uploads/index';
$route['country-admin/stores/get_ajax_store_location_data'] = 'Common/stores/get_ajax_store_location_data';
$route['country-admin/stores/location/update'] = 'Common/stores/update_store_location';

//Country Mall Store User Routes =================================
$route['mall-store-user'] = 'Mall_store/dashboard';
$route['mall-store-user/dashboard'] = 'Mall_store/dashboard';
$route['mall-store-user/change-password'] = 'Common/change_password';
$route['mall-store-user/change-information'] = 'Common/change_information';
$route['mall-store-user/notifications/remove_image_uploaded'] = 'Common/notifications/remove_image_uploaded';
$route['mall-store-user/notifications/get_notification_details/(:any)'] = 'Common/notifications/get_notification_details/$1';
$route['mall-store-user/notifications/images/(:any)'] = 'Common/notifications/images/$1';
$route['mall-store-user/notifications/update_images_sort_order/(:any)'] = 'Common/notifications/update_images_sort_order/$1';
$route['mall-store-user/notifications/(:any)/save'] = 'Common/notifications/save/$1';
$route['mall-store-user/notifications/(:any)'] = 'Common/notifications/index/$1';
$route['mall-store-user/notifications/(:any)/(:any)'] = 'Common/notifications/index/$1/$2';
$route['mall-store-user/filter_notifications/(:any)'] = 'Common/notifications/filter_notifications/$1';
$route['mall-store-user/filter_notifications/(:any)/(:any)'] = 'Common/notifications/filter_notifications/$1/$2';
$route['mall-store-user/notifications/(:any)/delete/(:any)/(:any)'] = 'Common/notifications/delete/$1/$2/$3';
$route['mall-store-user/notifications/(:any)/delete/(:any)'] = 'Common/notifications/delete/$1/$2';
$route['mall-store-user/notifications/(:any)/save/(:any)'] = 'Common/notifications/save/$1/$2';

$route['mall-store-user/stores'] = 'Common/stores/index';
$route['mall-store-user/stores/filter_stores'] = 'Common/stores/filter_stores';
$route['mall-store-user/stores/get_store_details/(:any)'] = 'Common/stores/get_store_details/$1';
$route['mall-store-user/stores/save'] = 'Common/stores/save';
$route['mall-store-user/stores/save/(:any)'] = 'Common/stores/save/$1';

$route['mall-store-user/malls'] = 'Common/malls/index';
$route['mall-store-user/malls/filter_malls'] = 'Common/malls/filter_malls';
$route['mall-store-user/malls/get_mall_details/(:any)'] = 'Common/malls/get_mall_details/$1';
$route['mall-store-user/malls/save'] = 'Common/malls/save';
$route['mall-store-user/malls/save/(:any)'] = 'Common/malls/save/$1';
$route['mall-store-user/report/(:any)/(:any)'] = 'Common/report/index/$1/$2';
$route['mall-store-user/report/push_notifications/(:any)/(:any)'] = 'Common/report/filter_notifications/$1/$2';
$route['mall-store-user/upload/index'] = 'Common/Uploads/index';
$route['mall-store-user/stores/refresh'] = 'Common/stores/refresh_captcha';

$route['mall-store-user/notifications/store/posted_date/get'] = 'Common/notifications/last_posted_date';
$route['mall-store-user/notifications/store/category/get'] = 'Common/notifications/get_categories';
$route['mall-store-user/notifications/store/offer/last-post-date/get'] = 'Common/notifications/get_store_offer_last_posted_date';
$route['mall-store-user/filter_notifications/(:any)/(:any)'] = 'Common/notifications/filter_notifications/$1/$2';

//Store Registration
$route['store-registration'] = 'storeregistration/index';
//Store user account verification
$route['account-verification'] = 'storeregistration/verification';

$route['email-change-verify'] = 'login/change_verify';

$route['about-us'] = 'Content_pages/about_us';
$route['contact-us'] = 'Content_pages/contact_us';
$route['tc'] = 'Content_pages/terms_conditions';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
