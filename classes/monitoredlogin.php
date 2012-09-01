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

namespace MonitoredLogin;


class MonitoredLoginTooSmallDelay extends \FuelException {}
class MonitoredLoginIpBlocked extends \FuelException {}
class MonitoredLoginUserBlocked extends \FuelException {}
class MonitoredLoginIpLimitHit extends MonitoredLoginIpBlocked {}
class MonitoredLoginUserLimitHit extends MonitoredLoginUserBlocked {}

/**
 * MonitoredLogin
 *
 * @package     MonitoredLogin
 */
class MonitoredLogin extends \Auth
{

	public static function _init()
	{
		//init Auth class if it wasn't called yet
		if (is_null(parent::$_instance))
		{
			parent::_init();
		}
	}

	/**
	 * Login user
	 *
	 * @param   string
	 * @param   string
	 * @throws  MonitoredLoginTooSmallDelay  when too small delay between logins
	 * @throws  MonitoredLoginIpBlocked  when ip is blocked
	 * @throws  MonitoredLoginUserBlocked  when user is blocked
	 * @throws  MonitoredLoginIpLimitHit  when ip limit has been reached
	 * @throws  MonitoredLoginUserLimitHit  when user limit has been reached
	 * @return  bool
	 */
	public static function login($username_or_email = '', $password = '')
	{
		$ip  = \Input::real_ip();
		$now = \Date::time('UTC')->get_timestamp();
		$ip_limit   = false;
		$user_limit = false;

		//Check for required delay if enabled
		if (is_int(\Config::get('monitoredlogin.attempts_delay', false)))
		{
			$recent_attempts = \DB::select()
						->from(\Config::get('monitoredlogin.table.login_attempts', 'ml_login_attempt'))
						->where_open()
							->where('login', trim($username_or_email))
							->or_where('ip', $ip)
						->where_close()
						->and_where('time', '>', $now - \Config::get('monitoredlogin.attempts_delay', 10))
						->execute(\Config::get('monitoredlogin.connection', null))->count();
			if ($recent_attempts > 0)
			{
				throw new \MonitoredLoginTooSmallDelay(\Config::get('monitoredlogin.attempts_delay', 10));
			}
		}

		if (is_int(\Config::get('monitoredlogin.ip_limit', false)))
		{
			//Is this ip already blocked?
			$ip_blocked = \DB::select()
						->from(\Config::get('monitoredlogin.table.blocked_ips', 'ml_blocked_ip'))
						->where('ip', $ip)
						->and_where('blocked_until', '>', $now)
						->execute(\Config::get('monitoredlogin.connection', null))->current();
			if ( ! empty($ip_blocked))
			{
				throw new \MonitoredLoginIpBlocked(ceil(($ip_blocked['blocked_until'] - $now) / 60));
			}
		}

		if (is_int(\Config::get('monitoredlogin.user_limit', false)))
		{
			//Is this user already blocked?
			$user_blocked = \DB::select()
						->from(\Config::get('monitoredlogin.table.blocked_users', 'ml_blocked_user'))
						->where('name', trim($username_or_email))
						->and_where('blocked_until', '>', $now)
						->execute(\Config::get('monitoredlogin.connection', null))->current();
			if ( ! empty($user_blocked))
			{
				throw new \MonitoredLoginUserBlocked(ceil(($user_blocked['blocked_until'] - $now) / 60));
			}
		}

		$login = parent::login($username_or_email, $password);

		//Create login attempt
		$browscap = new Browscap(APPPATH.'cache/browscap');
		$b = $browscap->getBrowser();
		\DB::insert(\Config::get('monitoredlogin.table.login_attempts', 'ml_login_attempt'))
				->columns(array('ip', 'login', 'time', 'wrong', 'browser', 'platform'))
				->values(array($ip, trim($username_or_email), $now, $login ? 0 : 1, $b->browser, $b->platform))
				->execute(\Config::get('monitoredlogin.connection', null));

		if ( ! $login and is_int(\Config::get('monitoredlogin.ip_limit', false)))
		{
			//Any not expired login attempts from ip?
			$ip_blocked = \DB::select()
						->from(\Config::get('monitoredlogin.table.login_attempts', 'ml_login_attempt'))
						->where('ip', $ip)
						->and_where('wrong', 1)
						->and_where('time', '>', $now - 60 * \Config::get('monitoredlogin.ip_expiration', 60))
						->execute(\Config::get('monitoredlogin.connection', null))->count();
			if ($ip_blocked >= \Config::get('monitoredlogin.ip_limit', 15))
			{
				$ip_limit = true;
				\DB::insert(\Config::get('monitoredlogin.table.blocked_ips', 'ml_blocked_ip'))
						->columns(array('ip', 'blocked_until'))
						->values(array($ip, $now + 60 * \Config::get('monitoredlogin.ip_blockade_len', 60)))
						->execute(\Config::get('monitoredlogin.connection', null));
			}
		}

		if ( ! $login and is_int(\Config::get('monitoredlogin.user_limit', false)))
		{
			//Any not expired login attempts from user?
			$user_blocked = \DB::select()
						->from(\Config::get('monitoredlogin.table.login_attempts', 'ml_login_attempt'))
						->where('login', trim($username_or_email))
						->and_where('wrong', 1)
						->and_where('time', '>', $now - 60 * \Config::get('monitoredlogin.user_expiration', 15))
						->execute(\Config::get('monitoredlogin.connection', null))->count();
			if ($user_blocked >= \Config::get('monitoredlogin.user_limit', 5))
			{
				$user_limit = true;
				\DB::insert(\Config::get('monitoredlogin.table.blocked_users', 'ml_blocked_user'))
						->columns(array('name', 'blocked_until'))
						->values(array(trim($username_or_email), $now + 60 * \Config::get('monitoredlogin.user_blockade_len', 60)))
						->execute(\Config::get('monitoredlogin.connection', null));
			}
		}

		if ($ip_limit)
		{
			throw new \MonitoredLoginIpLimitHit(\Config::get('monitoredlogin.ip_blockade_len', 60));
		}
		if ($user_limit)
		{
			throw new \MonitoredLoginUserLimitHit(\Config::get('monitoredlogin.user_blockade_len', 15));
		}

		return $login;
	}
}

/* End of file monitoredlogin.php */