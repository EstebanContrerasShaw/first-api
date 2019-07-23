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

$route['([a-z_]+)']['options'] = 'sesion/index'; 

//rutas pdf
$route['pdf/vista/(:num)']['get'] = 'pdf/vista/$1'; 
$route['pdf/informe/(:num)']['get'] = 'pdf/pdf/$1'; 
$route['pdf/ver/(:num)']['get'] = 'pdf/verInforme/$1'; 
$route['pdf/descargar/(:num)']['get'] = 'pdf/descargarInforme/$1'; 

//rutas usuario
$route['usuarios']['get'] = 'api/usuario/index'; 
$route['usuarios/(:num)']['get']= 'api/usuario/find/$1'; 

//rutas admin
$route['admins']['get'] = 'api/admin/index'; 
$route['admins/(:num)']['get']= 'api/admin/find/$1'; 
$route['admins']['post'] = 'api/admin/index'; 
$route['admins/logo']['put'] = 'api/admin/logo';
$route['admins/(:num)']['put'] = 'api/admin/index/$1'; 
$route['admins/(:num)']['delete'] = 'api/admin/index/$1'; //cambio estado, en usuario

//rutas campo
$route['campos/(:num)']['get'] = 'api/campo/index/$1'; 
//$route['campos/(:num)']['get']= 'api/campo/find/$1';
//$route['campos/categorias/(:num)']['get']= 'api/campo/findCategoria/$1';
//$route['campos']['post'] = 'api/campo/index'; 
//$route['campos/(:num)']['put'] = 'api/campo/index/$1'; 
//$route['campos/(:num)']['delete'] = 'api/campo/index/$1'; 

//rutas categoria
$route['categorias/(:num)']['get'] = 'api/categoria/index/$1'; 
//$route['categorias/formulario']['get']= 'api/categoria/formulario';
//$route['categorias']['post'] = 'api/categoria/index';
$route['categorias/completo/(:num)']['post'] = 'api/categoria/full/$1'; 
//$route['categorias/(:num)']['put'] = 'api/categoria/index/$1'; 
//$route['categorias/(:num)']['delete'] = 'api/categoria/index/$1'; 


//rutas comuna
$route['comunas']['get'] = 'api/comuna/index'; 
$route['comunas/(:num)']['get']= 'api/comuna/find/$1'; 
$route['comunas']['post'] = 'api/comuna/index'; 
$route['comunas/(:num)']['put'] = 'api/comuna/index/$1'; 

//rutas empresa
$route['empresas']['get'] = 'api/empresa/index'; 
$route['empresas/(:num)']['get']= 'api/empresa/find/$1'; 
$route['empresas']['post'] = 'api/empresa/index'; 
$route['empresas/(:num)']['put'] = 'api/empresa/index/$1'; 

//rutas fotos_auto
$route['fotos']['get'] = 'api/fotos_auto/index'; 
$route['fotos/(:num)']['get']= 'api/fotos_auto/find/$1'; 
$route['fotos/inspeccion/(:num)']['get']= 'api/fotos_auto/inspeccion/$1'; 
$route['fotos']['post'] = 'api/fotos_auto/index'; 

//rutas inspeccion
$route['inspecciones']['get'] = 'api/inspeccion/index'; 
$route['inspecciones/(:num)']['get']= 'api/inspeccion/find/$1'; 
$route['inspecciones/(:num)/valores']['get']= 'api/inspeccion/findDetalle/$1'; 
$route['inspecciones/(:num)/album']['get']= 'api/inspeccion/findAlbum/$1';
$route['inspecciones/(:num)/foto/(:num)']['get']= 'api/inspeccion/findFoto/$1/$2';
$route['inspecciones/(:num)/detalle']['get']= 'api/inspeccion/findFull/$1'; 
$route['inspecciones']['post'] = 'api/inspeccion/index'; 
$route['inspecciones/valores']['post'] = 'api/inspeccion/detalle'; 
$route['inspecciones/foto']['post'] = 'api/inspeccion/foto'; 
$route['inspecciones/album']['post'] = 'api/inspeccion/album';
$route['inspecciones/terminar']['post'] = 'api/inspeccion/terminar';
$route['inspecciones/reenviar']['post'] = 'api/inspeccion/reenviar';
$route['inspecciones/(:num)']['put'] = 'api/inspeccion/index/$1'; 

//rutas marca
$route['marcas']['get'] = 'api/marca/index'; 
$route['marcas/(:num)']['get']= 'api/marca/find/$1'; 
$route['marcas']['post'] = 'api/marca/index'; 
$route['marcas/(:num)']['put'] = 'api/marca/index/$1'; 

//rutas mecanico
$route['mecanicos']['get'] = 'api/mecanico/index'; 
$route['mecanicos/(:num)']['get']= 'api/mecanico/find/$1'; 
$route['mecanicos/empresa/(:num)']['get'] = 'api/mecanico/empresa/$1'; 
$route['mecanicos']['post'] = 'api/mecanico/index'; 
$route['mecanicos/(:num)']['put'] = 'api/mecanico/index/$1'; 
$route['mecanicos/setestados']['post'] = 'api/mecanico/estado';
$route['mecanicos/setpagados']['post'] = 'api/mecanico/pagado'; 
$route['mecanicos/(:num)']['delete'] = 'api/mecanico/index/$1'; //cambio de estado, en la tabla usuario esta el estado
$route['mecanicos/(:num)/activar']['put'] = 'api/mecanico/activar/$1'; 

//rutas region
$route['regiones']['get'] = 'api/region/index'; 
$route['regiones/(:num)']['get']= 'api/region/find/$1'; 
$route['regiones/(:num)/comunas']['get'] = 'api/region/comunas/$1'; 
$route['regiones']['post'] = 'api/region/index'; 
$route['regiones/(:num)']['put'] = 'api/region/index/$1'; 

//rutas solicitud
$route['solicitudes']['get'] = 'api/solicitud/index'; 
$route['solicitudes/(:num)']['get']= 'api/solicitud/find/$1'; 
$route['solicitudes']['post'] = 'api/solicitud/index'; 
//$route['solicitudes/(:num)']['put'] = 'api/solicitud/index/$1'; 
//$route['solicitudes/(:num)']['delete'] = 'api/solicitud/index/$1'; 

//rutas valor_campo
$route['valores']['get'] = 'api/valor_campo/index'; 
$route['valores/(:num)']['get']= 'api/valor_campo/find/$1'; 
$route['valores/inspeccion/(:num)']['get']= 'api/valor_campo/inspeccion/$1'; 
$route['valores']['post'] = 'api/valor_campo/index';
$route['valores/detalle']['post'] = 'api/valor_campo/detalle';





/*
| -------------------------------------------------------------------------
| Sample REST API Routes
| -------------------------------------------------------------------------
*/
//$route['api/example/users/(:num)'] = 'api/example/users/id/$1'; // Example 4
//$route['api/example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/example/users/id/$1/format/$3$4'; // Example 8
