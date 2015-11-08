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
 
	function time_elapsed_string($ptime)
	{
	    $etime = time() - $ptime;

	    if ($etime < 1)
	    {
	        return '0 seconds';
	    }

	    $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
	                30 * 24 * 60 * 60       =>  'month',
	                24 * 60 * 60            =>  'day',
	                60 * 60                 =>  'hour',
	                60                      =>  'minute',
	                1                       =>  'second'
	                );

	    foreach ($a as $secs => $str)
	    {
	        $d = $etime / $secs;
	        if ($d >= 1)
	        {
	            $r = round($d);
	            return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
	        }
	    }
	}

	function formatSizeUnits($bytes)
	    {
	        if ($bytes >= 1073741824)
	        {
	            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
	        }
	        elseif ($bytes >= 1048576)
	        {
	            $bytes = number_format($bytes / 1048576, 2) . ' MB';
	        }
	        elseif ($bytes >= 1024)
	        {
	            $bytes = number_format($bytes / 1024, 2) . ' KB';
	        }
	        elseif ($bytes > 1)
	        {
	            $bytes = $bytes . ' bytes';
	        }
	        elseif ($bytes == 1)
	        {
	            $bytes = $bytes . ' byte';
	        }
	        else
	        {
	            $bytes = '0 bytes';
	        }

	        return $bytes;
	}

	if (isset($requestArray[1]) && !empty($requestArray[1]))
	{
		$downloadId = $requestArray[1];
		if (is_numeric($downloadId))
		{
			$download = $db->readQueryMulti('SELECT * FROM `downloads` WHERE `downloadId` <= '. (int)$downloadId .' ORDER BY `downloadId` DESC LIMIT 0,2');
			$previousDownload = (isset($download[1]) ? $download[1] : null);
			$download = $download[0];

			$page->seoTitle = 'Download OpenRCT2 '. $download->version ." build ". $download->downloadId .' - OpenRCT2 project';
			$latest = false;
		}
		elseif ($downloadId === 'latest')
		{
			$extraSql = '';
			if (isset($requestArray[2]) && !empty($requestArray[2]) && strlen($requestArray[2]) < 20) {
				$extraSql = 'AND `gitBranch` = \''. $db->escape($requestArray[2]) .'\' OR `version` = \''. $db->escape($requestArray[2]) .'\'';
			}
			$download = $db->readQueryMulti('SELECT * FROM `downloads` WHERE `buildStatus` = \'success\' '. $extraSql .' ORDER BY `downloadId` DESC LIMIT 0,2');
			
			$downloadTemp = $download[0];
			$downloadId = $downloadTemp->downloadId;

			if (isset($download[1])) {
				$previousDownload = $download[1];
			}
			else {
				$download = $db->readQueryMulti('SELECT * FROM `downloads` WHERE `buildStatus` = \'success\' AND `downloadId` < '. (int)$downloadId .' ORDER BY `downloadId` DESC LIMIT 0,1');
				$previousDownload = $download[0];
			}

			$download = $downloadTemp;

			
			$page->seoTitle = 'Latest Download - OpenRCT2 project';
			$latest = true;
		}

		if (!isset($download) || $download == false || !is_object($download))
		{
			header('Location: /'. $page->identifier);
			exit;
		}


	}

	
	if (isset($download))
	{
		$baseUrl = "http://cdn.limetric.com/games/openrct2/";
		$url = $baseUrl . $download->fileName;
		$urlI = $baseUrl. $download->installerFileName;

		//Check
		$latestDownload = $db->readQuery('SELECT * FROM `downloads` WHERE `downloadId` > '. (int)$downloadId .' AND `gitBranch` = \''. $db->escape($download->gitBranch) .'\' AND `buildStatus` = \'success\' ORDER BY `downloadId` DESC LIMIT 0,1');
		if ($latestDownload == false || !is_object($latestDownload))
			$isNewerDownloadAvailable = false;
		else
			$isNewerDownloadAvailable = true;

		$buildStr = ($latest ? "Latest ". $download->version ." ". $download->gitBranch ." Build" : $download->version ." build ". $download->downloadId);

		$page->description = "Download ". strtolower($buildStr) ." of the OpenRCT2 project. The open-source implementation of RollerCoaster Tycoon 2";

		//Include header
		require(INCLUDES_PATH . DIRECTORY_SEPARATOR . 'header.inc.php');
?>
<section class="page downloadPage">
	<h1><?=$buildStr; ?></h1>
<?php
	if ($isNewerDownloadAvailable == true)
	{
?>
	<div class="downloadWarning">
		<a href="../<?=htmlspecialchars($page->identifier) ."/latest"; ?>">There's a newer download available. Get it while it's hot.</a>
	</div>
<?php
	}
?>
	<div class="downloadDetails">
		<b>Status &amp; Branch</b>: <div class="buildStatus <?=htmlspecialchars($download->buildStatus); ?>"><?=($download->buildStatus === 'success' ? htmlspecialchars($download->gitBranch) : 'Failed'); ?></div><br>
		<b>Based on commit hash</b>: <?=htmlspecialchars($download->gitHash) . (isset($previousDownload) ? ' (<a style="font-weight:bold;" href="https://github.com/IntelOrca/OpenRCT2/compare/'. htmlspecialchars($previousDownload->gitHash) .'...'. htmlspecialchars($download->gitHash) .'" target="_blank">changelog</a>)' : ''); ?><br>
		<b>Available since</b>: <?=strftime("%c %Z%z",$download->addedTime) ." (". time_elapsed_string($download->addedTime) .")"; ?><br>
		<b>Signed:</b> <?=((bool)$download->signed ? 'Yes, by <a href="http://limetric.com" target="_blank">Limetric</a>' : 'No'); ?><br>
		<b>Multiplayer support: </b> <?=($download->downloadId >= 1214 ? 'Yes. <a href="https://github.com/OpenRCT2/OpenRCT2/wiki/Multiplayer" target="_blank" title="More information about Multiplayer support in OpenRCT2">More information</a>.' : 'No'); ?>
	</div>
<?php
	if ($download->buildStatus === 'success') {
?>
	<h2>Download OpenRCT2 <?=htmlspecialchars($download->version) ." build ". htmlspecialchars($download->downloadId); ?></h2>
	<table class="downloadsLinks">
		<thead>
			<tr>
				<td>Platform &amp; Type</td>
				<td>Download URL</td>
				<td>Size</td>
			</tr>
		</thead>
		<tbody>
		<?=(!empty($download->installerFileName) ? "<tr>". PHP_EOL ."<td>Windows 32-bit (installer)</td>". PHP_EOL ."<td><a href=\"". htmlspecialchars($urlI) ."\">". htmlspecialchars(basename($urlI)) ."</a></td><td>". formatSizeUnits($download->installerFileSize) ."</td>". PHP_EOL ."</tr>". PHP_EOL : ""); ?>
		<?=(!empty($download->fileName) ? "<tr>". PHP_EOL ."<td>Windows 32-bit (zip archive)</td>". PHP_EOL ."<td><a href=\"". htmlspecialchars($url) ."\">". htmlspecialchars(basename($url)) ."</a></td><td>". formatSizeUnits($download->fileSize) ."</td>". PHP_EOL ."</tr>". PHP_EOL : ""); ?>
			<tr>
				<td>Windows XP</td>
				<td colspan="2">Currently unsupported. <a href="//github.com/OpenRCT2/OpenRCT2/issues/1184" target="_blank">This is a bug</a>.</td>
			</tr>
			<tr>
				<td>Mac OS X / Linux</td>
				<td colspan="2">No native support yet. Requires Wine. <a href="/wine-info" title="Information regarding playing OpenRCT2 on Linux or Mac OS X using Wine.">More information</a>.</td>
			</tr>
		</tbody>
	</table>
<?php
	}
?>
	<!--<h2>Other platforms (Linux, Mac OS X)</h2>
	<p>OpenRCT2 currently only runs natively on Windows till all code is reverse-engineered. However, players on Mac OS X and Linux can play OpenRCT2 just fine using Wine. Just run 'openrct2.exe' from the Portable ZIP package using Wine.</p>-->
<?php
	if (isset($download->commits) && !empty($download->commits)) {
		$commits = json_decode($download->commits);
?>
	<h2>Changes in this build</h2>
	<table class="changesTable">
		<thead>
			<tr>
				<td class="author">Author</td>
				<!--<td class="age">Age</td>-->
				<td class="message">Message</td>
			</tr>
		</thead>
		<tbody>
<?php
foreach ($commits as $commit)
		{
			if (strpos($commit->commit->message, 'Merge ') !== false)
				continue;
				//$commit->commit->message = strtok($commit->message, "\n");

			if (!isset($commit->author))
				$commit->author = new stdClass();

			if (!isset($commit->author->login) || empty($commit->author->login))
				$commit->author->login = 'Unknown Contributor';
			if (!isset($commit->author->avatar_url))
				$commit->author->avatar_url = 'https://i2.wp.com/assets-cdn.github.com/images/gravatars/gravatar-user-420.png';


			echo "\t\t\t\t<tr class=\"commit\">". PHP_EOL;
			echo "\t\t\t\t\t<td class=\"author\"><img alt=\"". htmlspecialchars($commit->author->login) ."'s avatar\" title=\"". htmlspecialchars($commit->author->login) ."\" src=\"". htmlspecialchars($commit->author->avatar_url) ."\" style=\"width:40px;height:40px;\"></td>". PHP_EOL;
			//echo "\t\t\t\t\t<td class=\"age\"><a href=\"/". htmlspecialchars($page->identifier) ."/". htmlspecialchars($commit->commitId) ."\">". time_elapsed_string($commit->addedTime) ."</a></td>". PHP_EOL;
			echo "\t\t\t\t\t<td class=\"message\">". nl2br(htmlspecialchars($commit->commit->message)) ."</td>". PHP_EOL;
			echo "\t\t\t\t</tr>". PHP_EOL;
		}
?>
		</tbody>
	</table>
<?php
	}
?>
	<h2>Build log</h2>
	<div class="buildLog">
<?=(!empty($download->buildLog) ? nl2br(htmlspecialchars($download->buildLog)) : '<i>No build log available.</i>'); ?>
	</div>
</section>
<?php
	}
	else
	{
		//Include header
		require(INCLUDES_PATH . DIRECTORY_SEPARATOR . 'header.inc.php');

		$downloads = $db->readQueryMulti('SELECT * FROM `downloads` ORDER BY `downloadId` DESC LIMIT 0,20');
		if ($downloads == false)
		{
?>
<section class="page downloadPage">
	<h1>Bad news</h1>
	<p>
		There are no downloads available.
	</p>
</section>
<?php
			//Include footer
			require(INCLUDES_PATH . DIRECTORY_SEPARATOR . 'footer.inc.php');

			exit;
		}
?>
<section class="page downloadPage">
	<h1>Downloads</h1>
	<p>Please bear in mind OpenRCT2 is in early beta stage. Crashes and bugs are common. If a build is not working, try an older one. You can report bugs on <a href="https://github.com/OpenRCT2/OpenRCT2/issues">GitHub</a> or on the <a href="/community/category/4/comments-bugs-feedback">forums</a>.</p>
	<p>An installation of RollerCoaster Tycoon 2 is required in order to play OpenRCT2. RCT2, with expansions, is cheap nowadays and can be bought from <a href="https://www.g2a.com/r/openrct2" target="_blank">G2A</a>, <a href="http://www.gog.com/game/rollercoaster_tycoon_2" target="_blank">GOG</a> and <a href="http://store.steampowered.com/app/285330/" target="_blank">Steam</a>. Alternatively, you can play the full game for free by <a href="/download-demo">installing this demo</a>.</p>
	<div class="fclear downloadButtons">
		<a href="/download/latest/0.0.2" class="fleft stable">
			Download Stable: 0.0.2
		</a>
		<a href="/download/latest/develop" class="fright develop">
			Download Develop: 0.0.3
		</a>
	</div>
	<table class="downloadsWaterfall">
		<thead>
			<tr>
				<td class="status"></td>
				<td class="name">Name</td>
				<td class="age">Age</td>
				<td class="size">Size</td>
			</tr>
		</thead>
		<tbody>

<?php
		$hadFirstSuccess = false;
		foreach ($downloads as $download)
		{
			if (!$hadFirstSuccess && $download->buildStatus === "success")
			{
				$hadFirstSuccess = true;
				$url = 'latest';
			}
			else
				$url = $download->downloadId;

			echo "\t\t\t\t<tr class=\"download\">". PHP_EOL;
			echo "\t\t\t\t\t<td class=\"buildStatus ". htmlspecialchars($download->buildStatus) ."\">". ($download->buildStatus === 'success' ? $download->gitBranch : 'Failed') ."</td>". PHP_EOL;
			echo "\t\t\t\t\t<td class=\"name\"><a href=\"/". htmlspecialchars($page->identifier) ."/". htmlspecialchars($url) ."\">". htmlspecialchars($download->version) ." build ". htmlspecialchars($download->downloadId) ."</a></td>". PHP_EOL;
			echo "\t\t\t\t\t<td class=\"age\">". time_elapsed_string($download->addedTime) ."</td>". PHP_EOL;
			echo "\t\t\t\t\t<td class=\"size\">". formatSizeUnits($download->fileSize) ."</td>". PHP_EOL;
			echo "\t\t\t\t</tr>". PHP_EOL;
		}
?>
		</ul>
	</table>
	<p>These downloads are automated. Use at own risk.</p>
	<p>The latest download can also be reached via <a href="/api/get-latest-build" target="_blank">the API</a>. Please add <a href="https://openrct2.com/download">this</a> link to your service/product.</p>
</section>

<?php
	}

	//Include footer
	require(INCLUDES_PATH . DIRECTORY_SEPARATOR . 'footer.inc.php');
?>