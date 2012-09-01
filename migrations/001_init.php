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

namespace Fuel\Migrations;

class Init
{

    function up()
    {
        \DBUtil::create_table(\Config::get('monitoredlogin.table.blocked_ips', 'ml_blocked_ip'), array(
            'id' => array('type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true),
            'ip' => array('type' => 'varchar', 'constraint' => 255),
            'blocked_until' => array('type' => 'int', 'constraint' => 11, 'unsigned' => true),
		),
        array('id'), false, 'InnoDB', 'utf8_unicode_ci');
		\DBUtil::create_index(\Config::get('monitoredlogin.table.blocked_ips', 'ml_blocked_ip'), 'ip');

        \DBUtil::create_table(\Config::get('monitoredlogin.table.blocked_users', 'ml_blocked_users'), array(
            'id' => array('type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true),
            'name' => array('type' => 'varchar', 'constraint' => 255),
            'blocked_until' => array('type' => 'int', 'constraint' => 11, 'unsigned' => true),
		),
        array('id'), false, 'InnoDB', 'utf8_unicode_ci');
		\DBUtil::create_index(\Config::get('monitoredlogin.table.blocked_users', 'ml_blocked_users'), 'name');

        \DBUtil::create_table(\Config::get('monitoredlogin.table.login_attempts', 'ml_login_attempt'), array(
            'id' => array('type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true),
            'ip' => array('type' => 'char', 'constraint' => 128),
            'time' => array('type' => 'int', 'constraint' => 11, 'unsigned' => true),
            'login' => array('type' => 'char', 'constraint' => 128),
            'wrong' => array('type' => 'tinyint', 'constraint' => 1, 'unsigned' => true),
            'browser' => array('type' => 'char', 'constraint' => 20),
            'platform' => array('type' => 'char', 'constraint' => 20),
		),
        array('id'), false, 'InnoDB', 'utf8_unicode_ci');
		\DBUtil::create_index(\Config::get('monitoredlogin.table.login_attempts', 'ml_login_attempt'), 'ip');
		\DBUtil::create_index(\Config::get('monitoredlogin.table.login_attempts', 'ml_login_attempt'), 'time');
		\DBUtil::create_index(\Config::get('monitoredlogin.table.login_attempts', 'ml_login_attempt'), 'login');
    }

    function down()
    {
       \DBUtil::drop_table(\Config::get('monitoredlogin.table.blocked_ips', 'ml_blocked_ip'));
       \DBUtil::drop_table(\Config::get('monitoredlogin.table.blocked_users', 'ml_blocked_user'));
       \DBUtil::drop_table(\Config::get('monitoredlogin.table.login_attempts', 'ml_login_attempt'));
    }
}

/* End of file 001_init.php */