<?php defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'home';
$route['404_override'] = 'login/error_404';
$route['translate_uri_dashes'] = TRUE;

$route['varieties'] = 'home/varieties';
$route['shop/(:num)'] = 'home/shop/$1';
$route['print/(:num)/(:num)'] = 'home/print/$1/$2';
$route['scan/(:num)/(:num)'] = 'home/scan/$1/$2';
$route['summary/(:num)/(:num)'] = 'home/summary/$1/$2';
$route['thankyou'] = 'home/thankyou';
$route['filling/(:num)/(:num)'] = 'home/filling/$1/$2';
$route['page-errors/(:any)'] = 'errors/index';
$route['sync-report'] = 'home/sync_report';