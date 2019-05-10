<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Auth
$route['auth/register']= 'auth/RegisterController/index';
$route['auth/login'] = 'auth/LoginController/index';
$route['auth/logout'] = 'auth/LoginController/logout';
$route['auth/password/forgot'] = 'auth/password/ForgotPasswordController/index';
$route['auth/password/reset/(:any)'] = 'auth/password/ResetPasswordController/index/$1';
// $route['auth/confirm']['GET'] = 'AuthController/confirmAccount';
// Auth-End

$route['dash/home']['GET'] = 'dash/DashboardController/index';
$route['dash/access/root']['GET'] = 'dash/access/RootAccessController/index';
$route['dash/access/vendor']['GET'] = 'dash/access/VendorAccessController/index';
$route['dash/access/admin']['GET'] = 'dash/access/AdminAccessController/index';
$route['dash/access/staff']['GET'] = 'dash/access/StaffAccessController/index';
$route['dash/access/member']['GET'] = 'dash/access/MemberAccessController/index';

// User
$route['dash/users']['GET'] = 'dash/UserController/index'; // list view
$route['dash/users/create']['GET'] = 'dash/UserController/create'; // create view
$route['dash/users/(:num)/edit']['GET'] = 'dash/UserController/edit/$1'; // edit view
$route['dash/users/(:num)']['GET'] = 'dash/UserController/delete/$1'; // delete data from database
$route['dash/users']['POST'] = 'dash/UserController/store'; // store data in database
$route['dash/users/(:num)']['POST'] = 'dash/UserController/update/$1'; // update data in databse
// User-End

// Company
$route['dash/companies']['GET'] = 'dash/CompanyController/index'; // list view
$route['dash/companies/create']['GET'] = 'dash/CompanyController/create'; // create view
$route['dash/companies/(:num)/edit']['GET'] = 'dash/CompanyController/edit/$1'; // edit view
$route['dash/companies/(:num)']['GET'] = 'dash/CompanyController/delete/$1'; // delete data from database
$route['dash/companies']['POST'] = 'dash/CompanyController/store'; // store data to database
$route['dash/companies/(:num)']['POST'] = 'dash/CompanyController/update/$1'; // update data in database
// Company-End

// Role
$route['dash/roles']['GET'] = 'dash/RoleController/index'; // list view
$route['dash/roles/create']['GET'] = 'dash/RoleController/create'; // create view
$route['dash/roles/(:num)/edit']['GET'] = 'dash/RoleController/edit/$1'; // edit view
$route['dash/roles/(:num)']['GET'] = 'dash/RoleController/delete/$1'; // delete data from database
$route['dash/roles']['POST'] = 'dash/RoleController/store'; // store data to database
$route['dash/roles/(:num)']['POST'] = 'dash/RoleController/update/$1'; // update data in database
// Role-End

// Permission
$route['dash/permissions']['GET'] = 'dash/PermissionController/index'; // list
$route['dash/permissions/create']['GET'] = 'dash/PermissionController/create'; // create
$route['dash/permissions/(:num)']['GET'] = 'dash/PermissionController/delete/$1'; // delete data from database
$route['dash/permissions']['POST'] = 'dash/PermissionController/store'; // store data to database
// Permission-End

$route['default_controller'] = 'Welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
