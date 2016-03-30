<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

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
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

define('VERSION', '4.01');

switch ($_SERVER['REMOTE_ADDR']) {
    case '192.168.1.102':
        $puesto = 3;
        $rutaUniversal = '/var/www/fiscal';
        break;
    default:
        $puesto = 4;
        break;
}
switch (ENVIRONMENT) {
    case 'activo':
        $rutaUniversal = '/var/www/fiscal/';
        break;
    default:
        $rutaUniversal = '/home/citrusdesa.autoservsantalucia.com.ar/assets/tmp/fiscal';
        break;
}
define('TMP', BASEPATH . '../assets/tmp/');
define('PUESTO', $puesto);
define('PRREMITO', 'laser03');
define('PRCARTEL', 'laser03');
define('ABSOLUT_PATH', $rutaUniversal);
define('ACTIVO', 1);
define('SUSPENDIDO', 0);
/* End of file constants.php */
/* Location: ./application/config/constants.php */