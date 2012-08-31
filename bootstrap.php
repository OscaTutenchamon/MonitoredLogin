<?php
/**
 * Part of the MonitoredLogin package for FuelPHP.
 *
 * @package    MonitoredLogin
 * @author     OscaTutenchamon
 * @license    MIT License
 * @copyright  2012 PyOt Mangament System
 * @link       http://pyot-ms.github.com
 */


\Package::loaded('auth') or \Package::load('auth');
\Config::load('monitoredlogin', true);

Autoloader::add_core_namespace('MonitoredLogin');

Autoloader::add_classes(array(
	'MonitoredLogin\\MonitoredLogin' => __DIR__.'/classes/monitoredlogin.php',
	'MonitoredLogin\\MonitoredLoginTooSmallDelay' => __DIR__.'/classes/monitoredlogin.php',
	'MonitoredLogin\\MonitoredLoginIpBlocked' => __DIR__.'/classes/monitoredlogin.php',
	'MonitoredLogin\\MonitoredLoginUserBlocked' => __DIR__.'/classes/monitoredlogin.php',
	'MonitoredLogin\\MonitoredLoginIpLimitHit' => __DIR__.'/classes/monitoredlogin.php',
	'MonitoredLogin\\MonitoredLoginUserLimitHit' => __DIR__.'/classes/monitoredlogin.php',

	'MonitoredLogin\\Browscap' => __DIR__.'/vendor/browscap.php',
	'MonitoredLogin\\Exception' => __DIR__.'/vendor/browscap.php',
));


/* End of file bootstrap.php */