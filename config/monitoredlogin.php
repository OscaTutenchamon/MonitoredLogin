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

/**
 * NOTICE:
 *
 * If you need to make modifications to the default configuration, copy
 * this file to your app/config folder, and make them in there.
 *
 * This will allow you to upgrade fuel without losing your custom config.
 */

return array(

	/*
	 * Connection to use
	 * Defined in database configs
	 */
	'connection' => null,

	/*
	 * Table Names
	 */
	'table' => array(
		'blocked_users'  => 'ml_blocked_user',
		'blocked_ips'    => 'ml_blocked_ip',
		'login_attempts' => 'ml_login_attempt',
	),

	/*
	 * Seconds needed to pass between login attempts
	 * Set false to disable
	 */
	'attempts_delay' => 10,

	/*
	 * Wrong login attempts to block IP
	 * Set false to disable
	 */
	'ip_limit' => 15,

	/*
	 * How much time must pass for ip address not to count it anymore
	 */
	'ip_expiration' => 60,

	/*
	 * IP blockade length (minutes)
	 */
	'ip_blockade_len' => 60,

	/*
	 * Wrong login attempts to block user
	 * Set false to disable
	 */
	'user_limit' => 5,

	/*
	 * How much time must pass for user not to count it anymore
	 */
	'user_expiration' => 60,

	/*
	 * User blockade length (minutes)
	 */
	'user_blockade_len' => 15,

);
