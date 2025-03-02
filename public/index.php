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
include_once __DIR__ . '/../src/navlist.php';
include_once __DIR__ . '/../src/pages.php';
include_once __DIR__ . '/../src/langs.php';

$QSTR = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
$_DOCROOT = isset($DOCROOT) ? __DIR__ . '/' . $DOCROOT : __DIR__;

$matches = array();
if (preg_match('/^([a-zA-Z]{2}(-[a-zA-Z]{2})?)\//', $QSTR, $matches, PREG_UNMATCHED_AS_NULL) === 1)
{
    $LANG = $matches[1];
} else {
    $LANG = get_request_language($_DOCROOT, $QSTR, $DEFLANG);
}
$_DOCROOT .= '/' . $LANG;

$template = file_get_contents(__DIR__ . '/../resources/template.html');

if ($template === false) {
    $template = '<!DOCTYPE html><html lang="%LANG%"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>%TITLE%</title><!--%HEAD%---></head><body><p><strong>ERROR</strong> template.html could not be read.</p><header>%HEADER%</header><nav>%NAV%</nav><article>%BODY%</article><footer>%FOOTER%</footer></body></html>';
}

if (strlen($QSTR) == 0) {
    $QSTR = $MAINPAGE ?? 'main';
}

$mdParser = new MarkdownExtra;
if (isset($TAB_WIDTH) && is_int($TAB_WIDTH)) $mdParser->tab_width = $TAB_WIDTH;
if (isset($HARD_WRAP) && is_bool($HARD_WRAP)) $mdParser->hard_wrap = $HARD_WRAP;
if (isset($NO_MARKUP) && is_bool($NO_MARKUP)) $mdParser->no_markup = $NO_MARKUP;
if (isset($NO_ENTITIES) && is_bool($NO_ENTITIES)) $mdParser->no_entities = $NO_ENTITIES;
if (isset($PREDEF_URLS) && is_array($PREDEF_URLS)) $mdParser->predef_urls = $PREDEF_URLS;
if (isset($PREDEF_TITLES) && is_array($PREDEF_TITLES)) $mdParser->predef_titles = $PREDEF_TITLES;
if (isset($URL_FILTER_FUNC) && is_callable($URL_FILTER_FUNC)) $mdParser->url_filter_func = $URL_FILTER_FUNC;
if (isset($HEADER_ID_FUNC) && is_callable($HEADER_ID_FUNC)) $mdParser->header_id_func = $HEADER_ID_FUNC;
if (isset($CODE_BLOCK_CONTENT_FUNC) && is_callable($CODE_BLOCK_CONTENT_FUNC)) $mdParser->code_block_content_func = $CODE_BLOCK_CONTENT_FUNC;
if (isset($CODE_SPAN_CONTENT_FUNC) && is_callable($CODE_SPAN_CONTENT_FUNC)) $mdParser->code_span_content_func = $CODE_SPAN_CONTENT_FUNC;
if (isset($ENHANCED_ORDERED_LIST) && is_bool($ENHANCED_ORDERED_LIST)) $mdParser->enhanced_ordered_list = $ENHANCED_ORDERED_LIST;
if (isset($FN_LINK_CLASS) && is_string($FN_LINK_CLASS)) $mdParser->fn_link_class = $FN_LINK_CLASS;
if (isset($FN_BACKLINK_CLASS) && is_string($FN_BACKLINK_CLASS)) $mdParser->fn_backlink_class = $FN_BACKLINK_CLASS;
if (isset($FN_BACKLINK_LABEL) && is_string($FN_BACKLINK_LABEL)) $mdParser->fn_backlink_label = $FN_BACKLINK_LABEL;
if (isset($FN_BACKLINK_HTML) && is_string($FN_BACKLINK_HTML)) $mdParser->fn_backlink_html = $FN_BACKLINK_HTML;
if (isset($CODE_CLASS_PREFIX) && is_string($CODE_CLASS_PREFIX)) $mdParser->code_class_prefix = $CODE_CLASS_PREFIX;
if (isset($CODE_ATTR_ON_PRE) && is_bool($CODE_ATTR_ON_PRE)) $mdParser->code_attr_on_pre = $CODE_ATTR_ON_PRE;
if (isset($TABLE_ALIGN_CLASS_TMPL) && is_string($TABLE_ALIGN_CLASS_TMPL)) $mdParser->table_align_class_tmpl = $TABLE_ALIGN_CLASS_TMPL;
if (isset($PREDEF_ABBR) && is_array($PREDEF_ABBR)) $mdParser->predef_abbr = $PREDEF_ABBR;
if (isset($HASHTAG_PROTECTION) && is_bool($HASHTAG_PROTECTION)) $mdParser->hashtag_protection = $HASHTAG_PROTECTION;

$spParser = new SmartyPantsTypographer;
if(isset($TAGS_TO_SKIP) && is_string($TAGS_TO_SKIP)) $spParser->tags_to_skip = $TAGS_TO_SKIP;
if(isset($DO_NOTHING) && is_bool($DO_NOTHING)) $spParser->do_nothing = $DO_NOTHING;
if(isset($DO_QUOTES) && is_int($DO_QUOTES)) $spParser->do_quotes = $DO_QUOTES;
if(isset($DO_BACKTICKS) && is_int($DO_BACKTICKS)) $spParser->do_backticks = $DO_BACKTICKS;
if(isset($DO_DASHES) && is_int($DO_DASHES)) $spParser->do_dashes = $DO_DASHES;
if(isset($DO_ELLIPSES) && is_int($DO_ELLIPSES)) $spParser->do_ellipses = $DO_ELLIPSES;
if(isset($CONVERT_QUOT) && is_int($CONVERT_QUOT)) $spParser->convert_quot = $CONVERT_QUOT;
if(isset($SMART_DOUBLEQUOTE_OPEN) && is_string($SMART_DOUBLEQUOTE_OPEN)) $spParser->smart_doublequote_open = $SMART_DOUBLEQUOTE_OPEN;
if(isset($SMART_DOUBLEQUOTE_CLOSE) && is_string($SMART_DOUBLEQUOTE_CLOSE)) $spParser->smart_doublequote_close = $SMART_DOUBLEQUOTE_CLOSE;
if(isset($SMART_SINGLEQUOTE_OPEN) && is_string($SMART_SINGLEQUOTE_OPEN)) $spParser->smart_singlequote_open = $SMART_SINGLEQUOTE_OPEN;
if(isset($SMART_SINGLEQUOTE_CLOSE) && is_string($SMART_SINGLEQUOTE_CLOSE)) $spParser->smart_singlequote_close = $SMART_SINGLEQUOTE_CLOSE;
if(isset($BACKTICK_DOUBLEQUOTE_OPEN) && is_string($BACKTICK_DOUBLEQUOTE_OPEN)) $spParser->backtick_doublequote_open = $BACKTICK_DOUBLEQUOTE_OPEN;
if(isset($BACKTICK_DOUBLEQUOTE_CLOSE) && is_string($BACKTICK_DOUBLEQUOTE_CLOSE)) $spParser->backtick_doublequote_close = $BACKTICK_DOUBLEQUOTE_CLOSE;
if(isset($BACKTICK_SINGLEQUOTE_OPEN) && is_string($BACKTICK_SINGLEQUOTE_OPEN)) $spParser->backtick_singlequote_open = $BACKTICK_SINGLEQUOTE_OPEN;
if(isset($BACKTICK_SINGLEQUOTE_CLOSE) && is_string($BACKTICK_SINGLEQUOTE_CLOSE)) $spParser->backtick_singlequote_close = $BACKTICK_SINGLEQUOTE_CLOSE;
if(isset($EM_DASH) && is_string($EM_DASH)) $spParser->em_dash = $EM_DASH;
if(isset($EN_DASH) && is_string($EN_DASH)) $spParser->en_dash = $EN_DASH;
if(isset($ELLIPSIS) && is_string($ELLIPSIS)) $spParser->ellipsis = $ELLIPSIS;
if(isset($DO_COMMA_QUOTES) && is_int($DO_COMMA_QUOTES)) $spParser->do_comma_quotes = $DO_COMMA_QUOTES;
if(isset($DO_GUILLEMETS) && is_int($DO_GUILLEMETS)) $spParser->do_guillemets = $DO_GUILLEMETS;
if(isset($DO_GERESH_GERSHAYIM) && is_int($DO_GERESH_GERSHAYIM)) $spParser->do_geresh_gershayim = $DO_GERESH_GERSHAYIM;
if(isset($DO_SPACE_COLON) && is_int($DO_SPACE_COLON)) $spParser->do_space_colon = $DO_SPACE_COLON;
if(isset($DO_SPACE_SEMICOLON) && is_int($DO_SPACE_SEMICOLON)) $spParser->do_space_semicolon = $DO_SPACE_SEMICOLON;
if(isset($DO_SPACE_MARKS) && is_int($DO_SPACE_MARKS)) $spParser->do_space_marks = $DO_SPACE_MARKS;
if(isset($DO_SPACE_EMDASH) && is_int($DO_SPACE_EMDASH)) $spParser->do_space_emdash = $DO_SPACE_EMDASH;
if(isset($DO_SPACE_ENDASH) && is_int($DO_SPACE_ENDASH)) $spParser->do_space_endash = $DO_SPACE_ENDASH;
if(isset($DO_SPACE_FRENCHQUOTE) && is_int($DO_SPACE_FRENCHQUOTE)) $spParser->do_space_frenchquote = $DO_SPACE_FRENCHQUOTE;
if(isset($DO_SPACE_THOUSANDS) && is_int($DO_SPACE_THOUSANDS)) $spParser->do_space_thousand = $DO_SPACE_THOUSANDS;
if(isset($DO_SPACE_UNIT) && is_int($DO_SPACE_UNIT)) $spParser->do_space_unit = $DO_SPACE_UNIT;
if(isset($DOUBLEQUOTE_LOW) && is_string($DOUBLEQUOTE_LOW)) $spParser->doublequote_low = $DOUBLEQUOTE_LOW;
if(isset($GUILLEMET_LEFTPOINTING) && is_string($GUILLEMET_LEFTPOINTING)) $spParser->guillemet_leftpointing = $GUILLEMET_LEFTPOINTING;
if(isset($GUILLEMET_RIGHTPOINTING) && is_string($GUILLEMET_RIGHTPOINTING)) $spParser->guillemet_rightpointing = $GUILLEMET_RIGHTPOINTING;
if(isset($GERESH) && is_string($GERESH)) $spParser->geresh = $GERESH;
if(isset($GERSHAYIM) && is_string($GERSHAYIM)) $spParser->gershayim = $GERSHAYIM;
if(isset($SPACE_EMDASH) && is_string($SPACE_EMDASH)) $spParser->space_emdash = $SPACE_EMDASH;
if(isset($SPACE_ENDASH) && is_string($SPACE_ENDASH)) $spParser->space_endash = $SPACE_ENDASH;
if(isset($SPACE_COLON) && is_string($SPACE_COLON)) $spParser->space_colon = $SPACE_COLON;
if(isset($SPACE_SEMICOLON) && is_string($SPACE_SEMICOLON)) $spParser->space_semicolon = $SPACE_SEMICOLON;
if(isset($SPACE_MARKS) && is_string($SPACE_MARKS)) $spParser->space_marks = $SPACE_MARKS;
if(isset($SPACE_FRENCHQUOTE) && is_string($SPACE_FRENCHQUOTE)) $spParser->space_frenchquote = $SPACE_FRENCHQUOTE;
if(isset($SPACE_THOUSANDS) && is_string($SPACE_THOUSANDS)) $spParser->space_thousand = $SPACE_THOUSANDS;
if(isset($SPACE_UNIT) && is_int($SPACE_UNIT)) $spParser->space_unit = $SPACE_UNIT;
if(isset($SPACE) && is_int($SPACE)) $spParser->space = $SPACE;

$_HEAD = '';
$BODY = get_page($mdParser, $spParser, $_DOCROOT, $QSTR, $LANG, $_HEAD, $TITLE);
$_COPYNOTE = isset($COPYNOTE) && strlen($COPYNOTE) > 0 ? '<p>' . $COPYNOTE . '</p>' : '';
$_COPYNOTE .= '<p>Powered by µCMS &copy; 2025 Ádám Juhász</p>';
$_TITLE = $TITLE ?? 'Micro Content Management System?';
$NAV = build_nav_list($mdParser, $spParser, $_DOCROOT, "index.php?", $NAVPAGE ?? null, $LANG);

$needles = array('%LANG%', '<!--%HEAD%-->', '%TITLE%', '%HEADER%', '%NAV%', '%BODY%', '%FOOTER%');
$values = array($LANG, $_HEAD, $_TITLE, '<p>' . $_TITLE . '</p>', $NAV, $BODY, $_COPYNOTE);

echo str_replace($needles, $values, $template);
exit;
?>