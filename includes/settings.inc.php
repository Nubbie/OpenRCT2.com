<?php
/*****************************************************************************
 * Copyright (c) 2015 Jarno Veuger / Limetric.com
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *****************************************************************************/

error_reporting(E_ALL);
session_start();

//Toggle debug mode
define('IS_DEBUG',true);
define('IS_DEVELOPMENT',IS_DEBUG);

//MySQL settings
$db_server = "localhost"; //Server (99% chance it's "localhost")
$db_user = ""; //Username
$db_password = ""; //Password
$db_name = ""; //Database name

//Secure connection check
if (isset($_SERVER['HTTPS']))
	define('IS_SECURE',$_SERVER['HTTPS'] == 'on' ? true : false);
else
	define('IS_SECURE',false);

//Remote Host
define('REMOTE_HOST',(isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0'));

//Set timezone and locale
date_default_timezone_set('Europe/Amsterdam');
//setlocale(LC_ALL, 'nl_NL');

//Unix timestamp
define('TIME_START',time());

//Server name
define('LOCAL_SERVERNAME',(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ''));

//GeoIP
define('REMOTE_COUNTRY_CODE',(isset($_SERVER['GEOIP_COUNTRY_CODE']) ? $_SERVER['GEOIP_COUNTRY_CODE'] : ''));
define('REMOTE_COUNTRY_NAME',(isset($_SERVER['GEOIP_COUNTRY_NAME']) ? $_SERVER['GEOIP_COUNTRY_NAME'] : ''));

//Path
define('INCLUDES_PATH',dirname(__FILE__));
define('PATH',dirname(INCLUDES_PATH));
define('CLASSES_PATH',INCLUDES_PATH . DIRECTORY_SEPARATOR . 'classes');
define('LIBS_PATH',INCLUDES_PATH . DIRECTORY_SEPARATOR . 'libs');
define('PAGES_PATH',PATH . DIRECTORY_SEPARATOR . 'pages');
define('MEDIA_PATH',PATH . DIRECTORY_SEPARATOR . 'media');
define('PHOTOS_PATH',MEDIA_PATH . DIRECTORY_SEPARATOR . 'photos');

//Set error reporting
if (IS_DEBUG == false)
	error_reporting(0);
else
	error_reporting(E_ALL);

//Initialize database
require_once(CLASSES_PATH . DIRECTORY_SEPARATOR . 'database.class.php');

//Setup database
$db = new Database($db_server,$db_user,$db_password,$db_name);


//Initialize various general classes
//require_once(CLASSES_PATH . DIRECTORY_SEPARATOR . 'jsonOutput.class.php');
//require_once(CLASSES_PATH . DIRECTORY_SEPARATOR . 'simpleImage.class.php');

//Include separate functions
require_once(INCLUDES_PATH . DIRECTORY_SEPARATOR . 'functions.inc.php');