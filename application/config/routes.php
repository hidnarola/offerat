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
$route['super-admin/dashboard'] = 'superadmin/dashboard';
$route['super-admin/change-password'] = 'common/change_password';
$route['super-admin/change-information'] = 'common/change_information';

//Country
$route['super-admin/country'] = 'superadmin/country';
$route['super-admin/country/save'] = 'superadmin/country/save';
$route['super-admin/country/save/(:any)'] = 'superadmin/country/save/$1';

//Category
$route['super-admin/category'] = 'superadmin/category';
$route['super-admin/category/save'] = 'superadmin/category/save';
$route['super-admin/category/save/(:any)'] = 'superadmin/category/save/$1';

//Sub-category
$route['super-admin/sub-category/(:any)'] = 'superadmin/subcategory/index/$1';
$route['super-admin/sub-category/save/(:any)'] = 'superadmin/subcategory/save/$1';
$route['super-admin/sub-category/save/(:any)/(:any)'] = 'superadmin/subcategory/save/$1/$2';


//Country Admin Routes =================================
$route['country-admin/dashboard'] = 'countryadmin/dashboard';
$route['country-admin/verify-store-offers'] = 'countryadmin/verify_offers/stores';
$route['country-admin/filter_store_offers'] = 'countryadmin/verify_offers/filter_store_offers';
$route['country-admin/verify-mall-offers'] = 'countryadmin/verify_offers/malls';
$route['country-admin/filter_mall_offers'] = 'countryadmin/verify_offers/filter_mall_offers';
$route['country-admin/change-password'] = 'common/change_password';
$route['country-admin/change-information'] = 'common/change_information';
$route['country-admin/notifications/get_notification_details/(:any)'] = 'common/notifications/get_notification_details/$1';
$route['country-admin/notifications/(:any)/save'] = 'common/notifications/save/$1';
$route['country-admin/notifications/(:any)/save/(:any)'] = 'common/notifications/save/$1/$2';
$route['country-admin/notifications/(:any)/save/(:any)/(:any)'] = 'common/notifications/save/$1/$2/$3';
$route['country-admin/notifications/(:any)'] = 'common/notifications/index/$1';
$route['country-admin/notifications/(:any)/(:any)'] = 'common/notifications/index/$1/$2';
$route['country-admin/filter_notifications/(:any)'] = 'common/notifications/filter_notifications/$1';
$route['country-admin/filter_notifications/(:any)/(:any)'] = 'common/notifications/filter_notifications/$1/$2';
$route['country-admin/notifications/(:any)/delete/(:any)/(:any)'] = 'common/notifications/delete/$1/$2/$3';

$route['country-admin/stores'] = 'common/stores/index';
$route['country-admin/stores/filter_stores'] = 'common/stores/filter_stores';
$route['country-admin/stores/get_store_details/(:any)'] = 'common/stores/get_store_details/$1';
$route['country-admin/stores/save'] = 'common/stores/save';
$route['country-admin/stores/save/(:any)'] = 'common/stores/save/$1';
$route['country-admin/stores/locations/(:any)'] = 'common/stores/locations/$1';
$route['country-admin/stores/delete_locations/(:any)'] = 'common/stores/delete_locations/$1';
$route['country-admin/stores/delete/(:any)'] = 'common/stores/delete/$1';
$route['country-admin/stores/sponsored/(:any)'] = 'common/stores/sponsored/$1';
$route['country-admin/stores/sponsored/delete/(:any)'] = 'common/stores/delete_sponsored/$1';
$route['country-admin/malls'] = 'common/malls/index';
$route['country-admin/malls/filter_malls'] = 'common/malls/filter_malls';
$route['country-admin/malls/get_mall_details/(:any)'] = 'common/malls/get_mall_details/$1';
$route['country-admin/stores/loacation_excel_download/(:any)'] = 'common/stores/loacation_excel_download/$1';
$route['country-admin/stores/loacation_excel_format_download'] = 'common/stores/loacation_excel_format_download';
$route['country-admin/malls/delete/(:any)'] = 'common/malls/delete/$1';
$route['country-admin/malls/sponsored/(:any)'] = 'common/malls/sponsored/$1';
$route['country-admin/malls/sponsored/delete/(:any)'] = 'common/malls/delete_sponsored/$1';
$route['country-admin/sponsored/stores'] = 'countryadmin/sponsored/stores';
$route['country-admin/sponsored/details/(:any)'] = 'countryadmin/sponsored/get_store_sponseored_details/$1';
$route['country-admin/malls/save'] = 'common/malls/save';
$route['country-admin/malls/save/(:any)'] = 'common/malls/save/$1';
$route['country-admin/report/(:any)/(:any)'] = 'common/report/index/$1/$2';
$route['country-admin/report/push_notifications/(:any)/(:any)'] = 'common/report/filter_notifications/$1/$2';


//Country Mall Store User Routes =================================
$route['mall-store-user/dashboard'] = 'mall_store/dashboard';
$route['mall-store-user/change-password'] = 'common/change_password';
$route['mall-store-user/change-information'] = 'common/change_information';
$route['mall-store-user/notifications/(:any)/save'] = 'common/notifications/save/$1';
$route['mall-store-user/notifications/(:any)'] = 'common/notifications/index/$1';
$route['mall-store-user/notifications/(:any)/(:any)'] = 'common/notifications/index/$1/$2';
$route['mall-store-user/filter_notifications/(:any)'] = 'common/notifications/filter_notifications/$1';
$route['mall-store-user/filter_notifications/(:any)/(:any)'] = 'common/notifications/filter_notifications/$1/$2';
$route['mall-store-user/notifications/(:any)/delete/(:any)/(:any)'] = 'common/notifications/delete/$1/$2/$3';

$route['mall-store-user/stores'] = 'common/stores/index';
$route['mall-store-user/stores/filter_stores'] = 'common/stores/filter_stores';
$route['mall-store-user/stores/get_store_details/(:any)'] = 'common/stores/get_store_details/$1';
$route['mall-store-user/stores/save'] = 'common/stores/save';
$route['mall-store-user/stores/save/(:any)'] = 'common/stores/save/$1';

$route['mall-store-user/malls'] = 'common/malls/index';
$route['mall-store-user/malls/filter_malls'] = 'common/malls/filter_malls';
$route['mall-store-user/malls/get_mall_details/(:any)'] = 'common/malls/get_mall_details/$1';
$route['mall-store-user/malls/save'] = 'common/malls/save';
$route['mall-store-user/malls/save/(:any)'] = 'common/malls/save/$1';
$route['mall-store-user/report/(:any)/(:any)'] = 'common/report/index/$1/$2';
$route['mall-store-user/report/push_notifications/(:any)/(:any)'] = 'common/report/filter_notifications/$1/$2';


//Store Registration
$route['store-registration'] = 'storeregistration/index';
//Store user account verification
$route['account-verification'] = 'storeregistration/verification';

$route['email-change-verify'] = 'login/change_verify';

$route['about-us'] = 'Content_pages/about_us';
$route['contact-us'] = 'Content_pages/contact_us';
$route['terms-and-conditions'] = 'Content_pages/terms_conditions';


$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
