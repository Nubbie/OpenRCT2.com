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

	if (isset($requestArray[1]) && !empty($requestArray[1]))
	{
		$newsItemSlug = $requestArray[1];
		if (strlen($newsItemSlug) > 255)
		{
			header('Location: /'. $page->identifier);
			exit;
		}

		$newsItem = $db->readQuery('SELECT * FROM `news` WHERE `slug` = \''. $db->escape($newsItemSlug) .'\' ORDER BY `newsItemId` DESC LIMIT 0,1');
		if ($newsItem == false || !is_object($newsItem))
		{
			header('Location: /'. $page->identifier);
			exit;
		}

		$page->title = $newsItem->title;
	}

	//Include header
	require(INCLUDES_PATH . DIRECTORY_SEPARATOR . 'header.inc.php');

	if (isset($newsItem))
	{
?>
<section class="page newsPage">
	<h1><?=$newsItem->title; ?></h1>
	<p>
		<b><?=ucfirst(strftime('%A %e %B %Y', $newsItem->postedTime)); ?></b>
	</p>
<?=$newsItem->content; ?>
</section>

<?php
	}
	else
	{
		$newsItems = $db->readQueryMulti('SELECT * FROM `news` ORDER BY `postedTime` DESC, `newsItemId` DESC LIMIT 0,50');
		if ($newsItems == false)
		{
?>
<section class="page newsPage">
	<h1>Bad news</h1>
	<p>
		There are no news posts yet.
	</p>
</section>

<?php
			//Include footer
			require(INCLUDES_PATH . DIRECTORY_SEPARATOR . 'footer.inc.php');

			exit;
		}
?>
<section class="page newsPage">
	<h1>News</h1>
	<p>
		<ul>
<?php
		foreach ($newsItems as $newsItem)
		{
			echo "\t\t\t\t<li><a href=\"/". $page->identifier ."/". $newsItem->slug ."\">". $newsItem->title ."</a></li>";
		}
?>
		</ul>
	</p>
</section>

<?php
	}

	//Include footer
	require(INCLUDES_PATH . DIRECTORY_SEPARATOR . 'footer.inc.php');
?>