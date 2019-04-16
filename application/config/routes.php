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

// Migration
$route['db/migrate']['GET'] = 'MigrationController/latest';
$route['db/rollback/(:num)']['GET'] = 'MigrationController/rollback/$1';
// Migration-End

// Auth
$route['auth/register']['GET'] = 'auth/Register/index';
$route['auth/login']['GET'] = 'auth/Login/index';
// $route['auth/logout']['GET'] = 'AuthController/logout';
// $route['auth/confirm']['GET'] = 'AuthController/confirmAccount';
$route['auth/password/forgot']['GET'] = 'auth/password/Forgot/index';
// $route['auth/password/reset']['GET'] = 'AuthController/resetPassword';
// Auth-End

$route['dash/home']['GET'] = 'admin/DashboardController/index';

// User
$route['dash/users']['GET'] = 'admin/UserController/index'; // list view
$route['dash/users/create']['GET'] = 'admin/UserController/create'; // create view
$route['dash/users/(:num)/edit']['GET'] = 'admin/UserController/edit/$1'; // edit view
$route['dash/users/(:num)']['GET'] = 'admin/UserController/delete/$1'; // delete data from database
$route['dash/users']['POST'] = 'admin/UserController/store'; // store data in database
$route['dash/users/(:num)']['POST'] = 'admin/UserController/update/$1'; // update data in databse
// User-End

// Role
$route['dash/roles']['GET'] = 'admin/RoleController/index'; // list view
$route['dash/roles/create']['GET'] = 'admin/RoleController/create'; // create view
$route['dash/roles/(:num)/edit']['GET'] = 'admin/RoleController/edit/$1'; // edit view
$route['dash/roles/(:num)']['GET'] = 'admin/RoleController/delete/$1'; // delete data from database
$route['dash/roles']['POST'] = 'admin/RoleController/store'; // store data to database
$route['dash/roles/(:num)']['POST'] = 'admin/RoleController/update/$1'; // update data in database
// Role-End

// Permission
$route['dash/permissions']['GET'] = 'admin/PermissionController/index'; // list
$route['dash/permissions/create']['GET'] = 'admin/PermissionController/create'; // create
$route['dash/permissions/(:num)']['GET'] = 'admin/PermissionController/delete/$1'; // delete data from database
$route['dash/permissions']['POST'] = 'admin/PermissionController/store'; // store data to database
// Permission-End

$route['default_controller'] = 'PublicController';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
