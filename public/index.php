<?php
/*
 * This file is part of Micro Content Management System.
 * 
 * Micro Content Management System is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * 
 * Micro Content Management System is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along with Micro Content Management System. If not, see <https://www.gnu.org/licenses/>. 
 */
/**@file index.php
 * @brief This file is the main entry point of the application.
 * @author Ádám Juhász
 * @copyright GNU General Public License v3
 * @version 2.0
 * @date 2025
 */

require_once __DIR__ . '/../vendor/autoload.php';
use Michelf\MarkdownExtra;
use Michelf\SmartyPantsTypographer;

include_once __DIR__ . '/../config/umcs.php';
include_once __DIR__ . '/../src/langs.php';
include_once __DIR__ . '/../src/md_sp.php';
include_once __DIR__ . '/../src/navlist.php';
include_once __DIR__ . '/../src/pages.php';

$QSTR = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
$_DOCROOT = (isset($DOCROOT) && strlen($DOCROOT) > 0) ? __DIR__ . '/' . $DOCROOT : __DIR__ . '/' . $LANG;

$template = file_get_contents(__DIR__ . '/../resources/template.html');

if ($template === false) {
    $template = '<!DOCTYPE html><html lang="%LANG%"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>%TITLE%</title><!--%HEAD%---></head><body><p><strong>ERROR</strong> template.html could not be read.</p><header>%HEADER%</header><nav>%NAV%</nav><article>%BODY%</article><footer>%FOOTER%</footer></body></html>';
}

if (strlen($QSTR) == 0) {
    $QSTR = $MAINPAGE ?? 'main';
}

$_HEAD = '';
$_TITLE = $TITLE ?? 'Micro Content Management System?';
$BODY = get_page($mdParser, $spParser, $_DOCROOT, $QSTR, $LANG, $_HEAD, $_TITLE);
$_COPYNOTE = isset($COPYNOTE) && strlen($COPYNOTE) > 0 ? '<p>' . $COPYNOTE . '</p>' : '';
$_COPYNOTE .= '<p>Powered by µCMS &copy; 2025 Ádám Juhász</p>';
$NAV = build_nav_list($mdParser, $spParser, $_DOCROOT, "index.php?", $NAVPAGE ?? null, $LANG);

$needles = array('%LANG%', '<!--%HEAD%-->', '%TITLE%', '%HEADER%', '%NAV%', '%BODY%', '%FOOTER%');
$values = array($LANG, $_HEAD, $_TITLE, '<p>' . $_TITLE . '</p>', $NAV, $BODY, $_COPYNOTE);

echo str_replace($needles, $values, $template);
exit;
?>