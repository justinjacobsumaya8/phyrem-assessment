<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
|	https://codeigniter.com/userguide3/general/routing.html
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

$route['default_controller'] = 'LoginController/index';
$route['login']['POST'] = 'LoginController/login';
$route['logout']['POST'] = 'LoginController/logout';

$route['admin/home']['GET'] = 'HomeController/index';

$route['admin/employees']['GET'] = 'EmployeeController/index';
$route['admin/employees']['POST'] = 'EmployeeController/store';
$route['admin/employees/list']['POST'] = 'EmployeeController/list';
$route['admin/employees/update/(:any)']['POST'] = 'EmployeeController/update/$1';
$route['admin/employees/delete/(:any)'] = 'EmployeeController/destroy/$1';

$route['admin/time-records']['GET'] = 'TimeRecordController/index';
$route['admin/time-records/list']['POST'] = 'TimeRecordController/list';
$route['admin/time-records/scan-qr']['GET'] = 'TimeRecordController/scanQR';
$route['admin/time-records/scan-qr']['POST'] = 'TimeRecordController/processQR';

$route['admin/users']['GET'] = 'UserController/index';
$route['admin/users/list']['POST'] = 'UserController/list';
$route['admin/users/create']['GET'] = 'UserController/create';
$route['admin/users/create']['POST'] = 'UserController/store';
$route['admin/users/edit/(:any)']['GET'] = 'UserController/edit/$1';
$route['admin/users/edit/(:any)']['POST'] = 'UserController/update/$1';
$route['admin/users/change-password/(:any)']['GET'] = 'UserController/changePassword/$1';
$route['admin/users/change-password/(:any)']['POST'] = 'UserController/savePassword/$1';
$route['admin/users/delete/(:any)'] = 'UserController/destroy/$1';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
