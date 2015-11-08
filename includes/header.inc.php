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

$siteTitle = 'OpenRCT2 project';
?>
<!doctype html>
<html lang="en" prefix="og: http://ogp.me/ns#"> 
	<head>
		<link href="//fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet" type="text/css">
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?=(isset($page->seoTitle) && !empty($page->seoTitle) ? $page->seoTitle : (isset($page->title) && !empty($page->title) ? $page->title .' - '. $siteTitle : $siteTitle)); ?></title>
<!--
Website copyright <?=date("Y"); ?> Limetric.com
-->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<link rel="stylesheet" type="text/css" href="/static/css/content.css?<?=(int)filemtime("./static/css/content.css"); ?>">
<?=(isset($page->description) && !empty($page->description) ? "\t\t<meta name=\"description\" content=\"". htmlspecialchars($page->description) ."\">". PHP_EOL: ''); ?>
		<link rel="shortcut icon" type="image/x-icon" href="/static/img/favicon.ico">
		<meta property="og:title" content="OpenRCT2">
		<meta property="og:description" content="<?=(isset($page->description) && !empty($page->description) ? htmlspecialchars($page->description) : ''); ?>">
		<meta property="og:type" content="website">
		<meta property="og:image" content="https://openrct2.com/static/img/logo_512.png">
		<meta property="og:url" content="https://openrct2.com">
		<meta property="fb:admins" content="1049637151">
		<!--[if lt IE 9]>
			<script type="text/javascript" src="/static/js/html5shiv.js"></script>
		<![endif]-->
	</head>
	<body>
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			ga('create', 'UA-3293028-49', 'openrct2.com');
			ga('require', 'linkid');
			ga('send', 'pageview');
		</script>
		<div id="container">
			<header class="fclear">
				<img src="/static/img/logo.png" width="64" height="64" class="fleft">
				<h1 class="fleft">OpenRCT2 project</h1>
			</header>
			<nav>
				<ul class="fclear">
<?php
	$navItems = $db->readQueryMulti('SELECT * FROM `pages` WHERE `visibility` = \'public\' ORDER BY `navOrder` DESC, `title` ASC');
	if ($navItems != false && is_array($navItems))
	{
		foreach ($navItems as $navItem)
		{
			if ($navItem->identifier === 'index')
				$navItem->identifier = '';

			//Override title with navigation title when needed
			if (isset($navItem->navTitle) && !empty($navItem->navTitle))
				$navItem->title = $navItem->navTitle;

			echo "\t\t\t\t\t\t\t<li>". PHP_EOL ."\t\t\t\t\t\t\t\t<a href=\"/". $navItem->identifier ."\" title=\"". htmlspecialchars($navItem->title) ."\">". htmlspecialchars($navItem->title) ."</a>". PHP_EOL ."\t\t\t\t\t\t\t</li>". PHP_EOL;
		}
	}
?>
				</ul>
			</nav>
