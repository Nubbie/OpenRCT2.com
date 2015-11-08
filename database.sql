-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Serverversion: 5.6.19
-- PHP-version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `downloads`
--

CREATE TABLE `downloads` (
  `downloadId` int(11) NOT NULL,
  `version` varchar(100) NOT NULL DEFAULT '0.0.2',
  `buildStatus` enum('unknown','success','failed') NOT NULL DEFAULT 'unknown',
  `buildLog` text NOT NULL,
  `addedTime` int(11) NOT NULL DEFAULT '0',
  `fileName` varchar(250) NOT NULL DEFAULT '',
  `installerFileName` varchar(250) NOT NULL DEFAULT '',
  `fileSize` int(11) NOT NULL DEFAULT '0',
  `installerFileSize` int(11) NOT NULL DEFAULT '0',
  `gitHash` varchar(250) NOT NULL DEFAULT '',
  `gitBranch` enum('unknown','master','develop','pre-release') NOT NULL DEFAULT 'unknown',
  `timeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `signed` tinyint(1) NOT NULL DEFAULT '1',
  `commits` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pages`
--

CREATE TABLE `pages` (
  `pageId` int(11) NOT NULL,
  `identifier` varchar(250) NOT NULL DEFAULT '',
  `includeFile` varchar(250) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `seoTitle` varchar(250) NOT NULL DEFAULT '',
  `navTitle` varchar(250) NOT NULL DEFAULT '',
  `visibility` enum('hidden','public') NOT NULL DEFAULT 'hidden',
  `navOrder` int(11) NOT NULL DEFAULT '0',
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `pages`
--

INSERT INTO `pages` (`pageId`, `identifier`, `includeFile`, `title`, `seoTitle`, `navTitle`, `visibility`, `navOrder`, `description`) VALUES
(1, '404', 'error.404.inc.php', 'Page not found', '', '', 'hidden', 0, ''),
(2, '403', 'error.403.inc.php', 'No permission', '', '', 'hidden', 0, ''),
(3, 'index', 'content.inc.php', 'Home', 'OpenRCT2 project', 'Home', 'public', 100, 'OpenRCT2 is the open-source adaption of the classic hit RollerCoaster Tycoon 2. The goal is to replace all game code with open-source code and extend the game with new features.'),
(4, 'download', 'download.inc.php', 'Downloads', '', '', 'public', 1, 'Downloads for the open-source adaption of RollerCoaster Tycoon 2. Free to download.'),
(5, 'api', 'api.inc.php', 'API', '', '', 'hidden', 0, ''),
(6, 'project', 'content.inc.php', 'Project Information', '', 'Project', 'hidden', 0, 'All information regarding the OpenRCT2 project. Feel free to support the development!'),
(7, 'changes', 'commits.inc.php', 'Recent Changes', '', '', 'hidden', 0, 'An overview of the recent changes of the OpenRCT2 project.'),
(8, 'chat', 'content.inc.php', 'Chat', '', '', 'hidden', 0, 'Chat with OpenRCT2 developers and other fans.'),
(9, 'features', 'content.inc.php', 'Features', '', '', 'public', 0, 'OpenRCT2 project features and changes.'),
(10, 'streams', 'streams.inc.php', 'Streams', '', '', 'public', 0, 'Watch people play OpenRCT2 on Twitch.'),
(11, 'community', 'content.inc.php', 'Community', '', '', 'public', 0, 'Discuss everything RollerCoaster Tycoon on the OpenRCT2 Community.'),
(12, 'wine-info', 'content.inc.php', 'Wine Information', '', '', 'hidden', 0, 'Information on how to play OpenRCT2 on Mac OS X and Linux using Wine.'),
(13, 'download-minigame', 'content.inc.php', 'Download RollerCoaster Tycoon 2 MiniGame', '', '', 'hidden', 0, 'Download the RollerCoaster Tycoon 2 MiniGame for free.'),
(14, 'download-demo', 'content.inc.php', 'Download RollerCoaster Tycoon 2 Demo', '', '', 'hidden', 0, 'Download the RollerCoaster Tycoon 2 TTP Demo for free.');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `pagesContent`
--

CREATE TABLE `pagesContent` (
  `parentPageId` int(11) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `pagesContent`
--

INSERT INTO `pagesContent` (`parentPageId`, `content`) VALUES
(3, '<img src="/media/4.png" class="homeBanner">\r\n<p>OpenRCT2 recreates RollerCoaster Tycoon 2 into opensource code. This allows us to add <a href="/features">new features</a>, fix original bugs and raise game limits. There''s even multiplayer co-op!</p>\r\n<p>Development started on April 2nd 2014 by <a href="http://dev.intelorca.co.uk/" target="_blank">Ted ''IntelOrca'' John</a>. Thanks to numerous contributions from others the OpenRCT2 project is making great progress and already has new features.</p>\r\n<p>An installation of RollerCoaster Tycoon 2 is required in order to play. Alternatively, there are also ways to play <a href="/download-demo">OpenRCT2 free of charge</a>.\r\n<h2>Download</h2>\r\n<p>OpenRCT2.com provides automated builds that makes it easy to get started. <a href="/download/latest">Get the latest download</a>.</p>\r\n<h2>Related Links</h2>\r\n<ul class="fclear table">\r\n	<li class="fleft"><a href="//github.com/OpenRCT2/OpenRCT2" target="_blank">Project on GitHub</a></li>\r\n	<li class="fleft"><a href="//github.com/OpenRCT2/OpenRCT2/issues" target="_blank">Official issue tracker</a></li>\r\n	<li class="fleft"><a href="/community/" target="_blank" style="font-weight: 600;">Sign up for the Community</a></li>\r\n	<li class="fleft"><a href="//www.reddit.com/r/OpenRCT2" target="_blank">Subreddit</a></li>\r\n	<li class="fleft"><a href="//gitter.im/OpenRCT2/OpenRCT2" target="_blank">Gitter Chat</a></li>\r\n	<li class="fleft"><a href="//gitter.im/OpenRCT2/OpenRCT2/non-dev" target="_blank">Gitter Non-Dev Chat</a></li>\r\n</ul>\r\n<h2>Website</h2>\r\n<p>This website and the automated downloads are provided by <a href="mailto:mail@openrct2.com">Jarno Veuger</a>.</p>'),
(6, '<h1>Project Information</h1>\r\n<p>OpenRCT2 is an open-source project, which means anyone interested can contribute. Development started on April 2nd 2014 and there are currently 22+ contributors.</p>\r\n<h2>Related Links</h2>\r\n<p>- <a href="https://github.com/IntelOrca/OpenRCT2/commits/master" target="_blank">Recent Changes</a><br />\r\n- <a href="//github.com/IntelOrca/OpenRCT2/wiki/Changes-to-original-game" target="_blank">Changes to original game</a><br />\r\n- <a href="//github.com/IntelOrca/OpenRCT2" target="_blank">Project on GitHub</a><br /></p>'),
(8, '<h1>Discuss & Chat</h1>\r\n<p>You can chat with the developers and other enthousiasts on several locations.</p>\r\n<ul><li><a href="https://gitter.im/IntelOrca/OpenRCT2" target="_blank">Chat via gitter</a></li>\r\n<li><a href="/community/">OpenRCT2.com Forums</a></li>\r\n<li><a href="http://www.reddit.com/r/openrct2" target="_blank">OpenRCT2 subreddit</a></li>\r\n</ul>'),
(9, '<img src="/media/3.png" class="homeBanner">\r\n<h1>Features</h1>\r\n<h2>0.0.2-master (2015-06-21)</h2>\r\n<ul>\r\n<li>Feature: Intro sequence does not show by default.</li>\r\n<li>Feature: New title screen logo.</li>\r\n<li>Feature: New title sequence (RCT2 version also still available).</li>\r\n<li>Feature: Title sequence music can now be disabled or changed to the RollerCoaster Tycoon 1 theme music.</li>\r\n<li>Feature: In-game console.</li>\r\n<li>Feature: Improved settings window with tab interface.</li>\r\n<li>Feature: Ability to change language while in game.</li>\r\n<li>Feature: Text input is now an in-game window.</li>\r\n<li>Feature: Toggle between software and hardware video mode.</li>\r\n<li>Feature: Toggle between resizeable window and fullscreen.</li>\r\n<li>Feature: Windows now snap to the borders of other windows when dragging (snap radius configurable).</li>\r\n<li>Feature: Interface colour themes. (Presets for RCT1 and RCT2 are included by default).</li>\r\n<li>Feature: Re-introduce traffic lights for close / test / open as a theme option.</li>\r\n<li>Feature: Show day as well as the month and year.</li>\r\n<li>Feature: Show month before day (e.g. March 14th, year 15)</li>\r\n<li>Feature: Exit OpenRCT2 to desktop.</li>\r\n<li>Feature: Game configuration, cache, scores and screenshots now saved in user documents directory under OpenRCT2.</li>\r\n<li>Feature: Auto-saving with frequency option.</li>\r\n<li>Feature: Ability to change game speed via toolbar or (+ and - keys).</li>\r\n<li>Feature: Finance window accessible from toolbar (enabled in settings).</li>\r\n<li>Feature: Research and development / research funding now accessible as a stand alone window without the requirement of the finances window.</li>\r\n<li>Feature: Extra viewport windows.</li>\r\n<li>Feature: Park window viewport is resizable.</li>\r\n<li>Feature: Land, water and ownership tool sizes can now be increased to 64x64.</li>\r\n<li>Feature: Mountain tool available in play mode.</li>\r\n<li>Feature: Buy land and construction rights land tool window. (Currently only in-game).</li>\r\n<li>Feature: Place scenery as a random cluster available in play mode.</li>\r\n<li>Feature: Increased limits for maximum of circuits per roller coaster to 20 and people on mazes to 64</li>\r\n<li>Feature: Allow both types of powered launch (with and without passing the station) for every roller coaster that supported one type in RCT2.</li>\r\n<li>Feature: Allow testing of incomplete tracks.</li>\r\n<li>Feature: Cheats window (Ctrl-Alt-C) or by toolbar button (configurable).</li>\r\n<li>Feature: Cheats for almost every guest aspect (happiness, hunger, nausea tolerance, etc.)</li>\r\n<li>Feature: Cheat to allow maximum operating settings and lift hill speeds (410 km/h).</li>\r\n<li>Feature: Cheat to disable all breakdowns.</li>\r\n<li>Feature: Cheat to disable brakes failure.</li>\r\n<li>Feature: Cheat to fix all rides.</li>\r\n<li>Feature: Change available objects in-game (only available from console).</li>\r\n<li>Feature: Change research settings in-game (only available from console).</li>\r\n<li>Feature: (Random) map generator available in scenario editor, accessible via the view menu.</li>\r\n<li>Feature: RollerCoaster Tycoon 1 scenarios can now be opened in the scenario editor or by using the ''edit'' command line action.</li>\r\n<li>Feature: The "have fun" objective can now be selected in the scenario editor.</li>\r\n<li>Feature: Twitch integration</li>\r\n<li>Fix: Litter bins now get full and require emptying by handymen.</li>\r\n</ul>'),
(11, '<h1>Community</h1>\r\n<p>Oh boy! The OpenRCT2 community (forums) are temporarily unavailable. Give us a few moments to fix this.</p>'),
(12, '<h1>Play OpenRCT2 on Mac OS X and Linux</h1>\r\n<p>At this moment OpenRCT2 does not run natively on Mac OS X or Linux yet, because it still depends on the original RollerCoaster Tycoon 2.</p>\r\n<p>However, you can play OpenRCT2 using Wine.</p>\r\n<ul><li>Download the ZIP archive/Portable ZIP Windows package.</li>\r\n<li>Get <a href="https://www.winehq.org/" target="_blank">Wine</a> for your platform.</li>\r\n<li>Run ''openrct2.exe'' using Wine.</li>\r\n</ul>'),
(13, '<h1>RollerCoaster Tycoon 2 MiniGame</h1>\r\n<p>The free RollerCoaster Tycoon 2 MiniGame Demo allows you to play a limited version of OpenRCT2.</p>\r\n<p>Instructions on how to use this demo can be found <a href="https://github.com/OpenRCT2/OpenRCT2/wiki/Required-RCT2-files#mini-game" target="_blank">here</a>.</p>\r\n\r\n<h2>Download links</h2>\r\n<ul>\r\n<li><a href="http://cdn.limetric.com/games/openrct2/misc/RCT2_Demo.exe">Direct download</a></li>\r\n<li><a href="http://www.download-free-games.com/dl/rollercoaster_tycoon2" rel="nofollow" target="_blank">Download Free Games mirror</a></li></ul>'),
(14, '<img src="/media/2.png" class="homeBanner">\r\n<h1>RollerCoaster Tycoon 2 TTP Demo</h1>\r\n<p>The free RollerCoaster Tycoon 2 TTP Demo allows you to play the full game for 1 hour. However, OpenRCT2 removes this 1 hour time limit. By removing this limit you can enjoy the full RollerCoaster Tycoon 2 experience (via OpenRCT2) without buying the game. We still recommend you to <a href="https://www.g2a.com/r/openrct2">buy the game</a> in order to support Chris Sawyer. It''s not that expensive.</p>\r\n<h2>Instructions</h2>\r\n<ul>\r\n<li>Download and install the <a href="/download">latest OpenRCT2 build</a>. Don''t run the game yet.</li>\r\n<li>Remember where you installed OpenRCT2 to. The default location is <i>C:\\Program Files (x86)\\OpenRCT2</i>.</li>\r\n<li>Download the RollerCoaster Tycoon 2 TTP Demo as an <a href="http://cdn.limetric.com/games/openrct2/misc/RollerCoasterTycoon2TTP_EN.exe">EXE-download</a> (412.61 MB) or as a <a href="http://cdn.limetric.com/games/openrct2/misc/RollerCoasterTycoon2TTP_EN.zip">ZIP-download</a> (532.28 MB).</li>\r\n<li>Extract/copy the demo files to your OpenRCT2 installation directory.</li>\r\n<li>Now run <i>openrct2.exe</i> to play the full game.</li>\r\n</ul>');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `downloads`
--
ALTER TABLE `downloads`
  ADD PRIMARY KEY (`downloadId`);

--
-- Indexen voor tabel `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`pageId`),
  ADD KEY `identifier` (`identifier`,`navOrder`);

--
-- Indexen voor tabel `pagesContent`
--
ALTER TABLE `pagesContent`
  ADD PRIMARY KEY (`parentPageId`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `downloads`
--
ALTER TABLE `downloads`
  MODIFY `downloadId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1431;
--
-- AUTO_INCREMENT voor een tabel `pages`
--
ALTER TABLE `pages`
  MODIFY `pageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT voor een tabel `pagesContent`
--
ALTER TABLE `pagesContent`
  MODIFY `parentPageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
