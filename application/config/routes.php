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
| example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
| https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
| $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
| $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
| $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples: my-controller/index -> my_controller/index
|   my-controller/my-method -> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

/*
$route['([a-z_]+)']['get'] = '$1/index';
$route['([a-z_]+)/currpag/(:num)/perpag/(:num)']['get'] = '$1/index/$2$3';
$route['([a-z_]+)/(:num)']['get'] = '$1/find/$2';
$route['([a-z_]+)']['post'] = '$1/index';
$route['([a-z_]+)/(:num)']['put'] = '$1/index/$2';
$route['([a-z_]+)/(:num)']['delete'] = '$1/index/$2';*/




//rutas sesion
$route['sesion']['post'] = 'sesion/login'; 
//$route['sesion']['get'] = 'sesion/expToken'; 

//rutas pdf
$route['pdf/vista/(:num)']['get'] = 'pdf/vista/$1'; 
$route['pdf/informe/(:num)']['get'] = 'pdf/pdf/$1'; 
$route['pdf/correo/(:num)']['get'] = 'pdf/correo/$1'; 
$route['pdf/ver/(:num)']['get'] = 'pdf/verInforme/$1'; 
$route['pdf/descargar/(:num)']['get'] = 'pdf/descargarInforme/$1'; 

//rutas usuario
$route['usuarios']['get'] = 'usuario/index'; 
$route['usuarios/(:num)']['get']= 'usuario/find/$1'; 
//$route['usuarios']['post'] = 'usuario/index'; 
//$route['usuarios/(:num)']['put'] = 'usuario/index/$1'; 
//$route['usuarios/(:num)']['delete'] = 'usuario/index/$1'; 

//rutas admin
$route['admins']['get'] = 'admin/index'; 
$route['admins/(:num)']['get']= 'admin/find/$1'; 
$route['admins']['post'] = 'admin/index'; 
$route['admins/logo']['put'] = 'admin/logo';
$route['admins/(:num)']['put'] = 'admin/index/$1'; 
$route['admins/(:num)']['delete'] = 'admin/index/$1'; //cambio estado, en usuario

//rutas campo
$route['campos']['get'] = 'campo/index'; 
$route['campos/(:num)']['get']= 'campo/find/$1';
$route['campos/categorias/(:num)']['get']= 'campo/findCategoria/$1';
$route['campos']['post'] = 'campo/index'; 
$route['campos/completo']['post'] = 'campo/full';
$route['campos/(:num)']['put'] = 'campo/index/$1'; 
$route['campos/(:num)']['delete'] = 'campo/index/$1'; 

//rutas categoria
$route['categorias']['get'] = 'categoria/index'; 
$route['categorias/(:num)']['get']= 'categoria/find/$1';
$route['categorias/(:num)/campos']['get']= 'campo/findCategoria/$1';
$route['categorias']['post'] = 'categoria/index'; 
$route['categorias/(:num)']['put'] = 'categoria/index/$1'; 
$route['categorias/(:num)']['delete'] = 'categoria/index/$1'; 

//rutas cliente
$route['clientes']['get'] = 'cliente/index'; 
$route['clientes/(:num)']['get']= 'cliente/find/$1'; 
$route['clientes']['post'] = 'cliente/index'; 
$route['clientes/(:num)']['put'] = 'cliente/index/$1'; 
//$route['clientes/(:num)']['delete'] = 'cliente/index/$1'; 

//rutas comuna
$route['comunas']['get'] = 'comuna/index'; 
$route['comunas/(:num)']['get']= 'comuna/find/$1'; 
$route['comunas']['post'] = 'comuna/index'; 
$route['comunas/(:num)']['put'] = 'comuna/index/$1'; 
//$route['comunas/(:num)']['delete'] = 'comuna/index/$1'; 

//rutas empresa
$route['empresas']['get'] = 'empresa/index'; 
$route['empresas/(:num)']['get']= 'empresa/find/$1'; 
$route['empresas']['post'] = 'empresa/index'; 
$route['empresas/(:num)']['put'] = 'empresa/index/$1'; 
//$route['empresas/(:num)']['delete'] = 'empresa/index/$1'; 

//rutas fotos_auto
$route['fotos']['get'] = 'fotos_auto/index'; 
$route['fotos/(:num)']['get']= 'fotos_auto/find/$1'; 
$route['fotos/inspeccion/(:num)']['get']= 'fotos_auto/inspeccion/$1'; 
$route['fotos']['post'] = 'fotos_auto/index'; 
//$route['fotos/(:num)']['put'] = 'fotos_auto/index/$1'; 
//$route['fotos/(:num)']['delete'] = 'fotos_auto/index/$1'; 

//rutas inspeccion
$route['inspecciones']['get'] = 'inspeccion/index'; 
$route['inspecciones/(:num)']['get']= 'inspeccion/find/$1'; 
$route['inspecciones/(:num)/valores']['get']= 'inspeccion/findDetalle/$1'; 
$route['inspecciones/(:num)/album']['get']= 'inspeccion/findAlbum/$1';
$route['inspecciones/(:num)/foto/(:num)']['get']= 'inspeccion/findFoto/$1/$2';
$route['inspecciones/(:num)/detalle']['get']= 'inspeccion/findFull/$1'; 
$route['inspecciones/(:num)/log']['get']= 'inspeccion/findLog/$1'; 
$route['inspecciones']['post'] = 'inspeccion/index'; 
$route['inspecciones/valores']['post'] = 'inspeccion/detalle'; 
$route['inspecciones/foto']['post'] = 'inspeccion/foto'; 
$route['inspecciones/album']['post'] = 'inspeccion/album';
$route['inspecciones/terminar']['post'] = 'inspeccion/terminar';
$route['inspecciones/reenviar']['post'] = 'inspeccion/reenviar';
$route['inspecciones/(:num)']['put'] = 'inspeccion/index/$1'; 
//$route['inspecciones/(:num)']['delete'] = 'inspeccion/index/$1'; 

//rutas marca
$route['marcas']['get'] = 'marca/index'; 
$route['marcas/(:num)']['get']= 'marca/find/$1'; 
$route['marcas']['post'] = 'marca/index'; 
$route['marcas/(:num)']['put'] = 'marca/index/$1'; 
//$route['marcas/(:num)']['delete'] = 'marca/index/$1'; 

//rutas mecanico
$route['mecanicos']['get'] = 'mecanico/index'; 
$route['mecanicos/(:num)']['get']= 'mecanico/find/$1'; 
$route['mecanicos']['post'] = 'mecanico/index'; 
$route['mecanicos/(:num)']['put'] = 'mecanico/index/$1'; 
$route['mecanicos/(:num)/activar']['put'] = 'mecanico/estado/$1';
$route['mecanicos/(:num)/pagado']['put'] = 'mecanico/pagado/$1'; 
$route['mecanicos/(:num)']['delete'] = 'mecanico/index/$1'; //cambio de estado, en usuario esta el estado

//rutas region
$route['regiones']['get'] = 'region/index'; 
$route['regiones/(:num)']['get']= 'region/find/$1'; 
$route['regiones']['post'] = 'region/index'; 
$route['regiones/(:num)']['put'] = 'region/index/$1'; 
//$route['regiones/(:num)']['delete'] = 'region/index/$1'; 

//rutas solicitud
$route['solicitudes']['get'] = 'solicitud/index'; 
$route['solicitudes/(:num)']['get']= 'solicitud/find/$1'; 
//$route['solicitudes']['post'] = 'solicitud/index'; 
//$route['solicitudes/(:num)']['put'] = 'solicitud/index/$1'; 
//$route['solicitudes/(:num)']['delete'] = 'solicitud/index/$1'; 

//rutas solicitud_programada
$route['solicitudesprog']['get'] = 'solicitud_programada/index'; 
$route['solicitudesprog/(:num)']['get']= 'solicitud_programada/find/$1'; 
$route['solicitudesprog']['post'] = 'solicitud_programada/index'; 
$route['solicitudesprog/(:num)']['put'] = 'solicitud_programada/index/$1'; 
//$route['solicitudesProg/(:num)']['delete'] = 'solicitud_programada/index/$1'; 

//rutas solicitud_vip
$route['solicitudesvip']['get'] = 'solicitud_vip/index'; 
$route['solicitudesvip/(:num)']['get']= 'solicitud_vip/find/$1'; 
$route['solicitudesvip']['post'] = 'solicitud_vip/index'; 
$route['solicitudesvip/(:num)']['put'] = 'solicitud_vip/index/$1'; 
//$route['solicitudesVIP/(:num)']['delete'] = 'solicitud_vip/index/$1'; 

//rutas solicitud_express
$route['solicitudesexpress']['get'] = 'solicitud_express/index'; 
$route['solicitudesexpress/(:num)']['get']= 'solicitud_express/find/$1'; 
$route['solicitudesexpress']['post'] = 'solicitud_express/index'; 
$route['solicitudesexpress/(:num)']['put'] = 'solicitud_express/index/$1'; 
//$route['solicitudesExpress/(:num)']['delete'] = 'solicitud_express/index/$1'; 

//rutas solicitud_tipo
$route['solicitudestipo']['get'] = 'solicitud_tipo/index'; 
$route['solicitudestipo/(:num)']['get']= 'solicitud_tipo/find/$1'; 
$route['solicitudestipo']['post'] = 'solicitud_tipo/index'; 
$route['solicitudestipo/(:num)']['put'] = 'solicitud_tipo/index/$1'; 
//$route['solicitudTipos/(:num)']['delete'] = 'solicitud_tipo/index/$1'; 

//rutas valor_campo
$route['valores']['get'] = 'valor_campo/index'; 
$route['valores/(:num)']['get']= 'valor_campo/find/$1'; 
$route['valores/inspeccion/(:num)']['get']= 'valor_campo/inspeccion/$1'; 
$route['valores']['post'] = 'valor_campo/index';
$route['valores/detalle']['post'] = 'valor_campo/detalle';
//$route['valores/(:num)']['put'] = 'valor_campo/index/$1'; 
//$route['valores/(:num)']['delete'] = 'valor_campo/index/$1'; 

//rutas vendedor
$route['vendedores']['get'] = 'vendedor/index'; 
$route['vendedores/(:num)']['get']= 'vendedor/find/$1'; 
$route['vendedores']['post'] = 'vendedor/index'; 
$route['vendedores/(:num)']['put'] = 'vendedor/index/$1'; 
$route['vendedores/(:num)']['delete'] = 'vendedor/index/$1'; 

$route['vendedores']['options'] = 'vendedor/index'; 



/*
| -------------------------------------------------------------------------
| Sample REST API Routes
| -------------------------------------------------------------------------
*/
$route['api/example/users/(:num)'] = 'api/example/users/id/$1'; // Example 4
$route['api/example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/example/users/id/$1/format/$3$4'; // Example 8
