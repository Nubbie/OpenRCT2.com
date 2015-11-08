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

	/*if (isset($requestArray[1]) && !empty($requestArray[1]))
	{


		if (!isset($download) || $download == false || !is_object($download))
		{
			header('Location: /'. $page->identifier);
			exit;
		}
	}*/

	//Include header
	require(INCLUDES_PATH . DIRECTORY_SEPARATOR . 'header.inc.php');

	if (isset($download))
	{
?>
<?php
	}
	else
	{
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.twitch.tv/kraken/search/streams?q=RollerCoaster"); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $output = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($output);
?>
<section class="page downloadPage">
	<h1>Streams</h1>
	<p>RollerCoaster Tycoon 2 is still a popular game. People regurarly stream their gameplay. Here you can see all the live channels.</p>
	<div class="fclear">
<?php
	if (!isset($data->streams) || !isset($data->_total) || (int)$data->_total === 0) {
		echo "<p><em>Unfortunately no one is streaming RollerCoaster Tycoon at this moment.</em></p>";
	} else {
		foreach ($data->streams as $key => $stream) {
			echo "\t\t<div class=\"fleft\" style=\"margin: 4px;\">". PHP_EOL;
			echo "\t\t\t<a href=\"". htmlspecialchars($stream->channel->url) ."\" target=\"_blank\"><img style=\"cursor: pointer;\" width=\"310\" src=\"". htmlspecialchars(str_replace('http:', 'https:', $stream->preview->medium)) ."\"></a>". PHP_EOL;
			echo "\t\t\t<div class=\"info\">". (int)$stream->viewers ." viewers. Click preview to watch.</div>". PHP_EOL;
			echo "\t\t</div>". PHP_EOL;
		}
	}
?>
	</div>	
</section>

<?php
	}

	//Include footer
	require(INCLUDES_PATH . DIRECTORY_SEPARATOR . 'footer.inc.php');
?>