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

require("includes/settings.inc.php");

//Retrieve request
$request = $_SERVER["REQUEST_URI"];

//Check if request ends with trailing slash
if(endsWith(parse_url($request,PHP_URL_PATH),"/") && strlen($request) >= 2)
{ //Yes it does	
	//We'd want to maintain GET parameters if has GET parameters
	$requestGetVars = explode("?",$request);
	
	if(is_array($requestGetVars) && count($requestGetVars) > 1)
		//Re-route including GET parameters
		//Stick it together
		header("Location: ". substr_replace($requestGetVars[0] ,"",-1) . "?" . $requestGetVars[1]);
	else
		//Just re-route
		header("Location: ". substr_replace($request ,"",-1));

	die;
}

if (strlen(LOCAL_SERVERNAME) > 30)
	die('Oops. HTTP Host is too long...');

//Remove get var from url and reset it to $_GET
$querystring = $_SERVER["QUERY_STRING"];
$request = str_replace($querystring, '', $request);
parse_str($querystring, $_GET);

$request = str_replace("?", "", $request);
$requestArray = preg_split("[/]", $request, 0,PREG_SPLIT_NO_EMPTY);//[\\/]

//Check for pages based on request
if(isset($requestArray[0]) && strlen($requestArray[0]) < 100)
	$pageIdentifier = $requestArray[0];
else
	$pageIdentifier = 'index';

//Query page
$page = $db->readQuery("SELECT * FROM `pages` WHERE `identifier` = '". $db->escape($pageIdentifier) ."' LIMIT 0,1");

//Check page validity
if ($page === false || !is_object($page) || (isset($page->includeFile) && !empty($page->includeFile) && !file_exists(PAGES_PATH . DIRECTORY_SEPARATOR . $page->includeFile)))
{
	//Set to page 404 (Not Found)
	$pageIdentifier = '404';

	$page = $db->readQuery("SELECT * FROM `pages` WHERE `identifier` = '". $db->escape($pageIdentifier) ."' LIMIT 0,1");
	if ($page === false || !is_object($page))
		trigger_error('Fatal error: Unable to find 404 (Not Found) page.');
}
/*elseif ($page->isSignInRequired == true && $user->isSignedIn() === false)
{
	//Set to page 403 (Forbidden)
	$pageIdentifier = '403';

	$page = $db->readQuery("SELECT * FROM `pages` WHERE `identifier` = '". $db->escape($pageIdentifier) ."' LIMIT 0,1");
	if ($page === false || !is_object($page))
		trigger_error('Fatal error: Unable to find 403 (Forbidden) page.');	
}*/

include(PAGES_PATH . DIRECTORY_SEPARATOR . $page->includeFile);