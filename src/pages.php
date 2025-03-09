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
/**
 * @file pages.php
 * Contains the page rendering functions for Micro Content Management System.
 */

require_once __DIR__ . '/../vendor/autoload.php';
use Michelf\Markdown;
use Michelf\SmartyPants;

include_once __DIR__ . '/../config/ucms.php';
include_once __DIR__ . '/utils.php';

$trimmer = "trim";
$replacer = "str_replace";
$dater = "gmdate";
$phpversion = "phpversion";

/**
 * Checks if a string's length is non-zero.
 * @param string $value The string to check.
 * @return bool `true` if the string is non-zero in length, otherwise `false`.
 */
function strlen_not_null(string $value): bool {
    return strlen($value) != 0;
}

/**
 * Produces the content of a special, build-in page.
 * @param string $specialPage The name of the special page.
 * @return string The content of the special page.
 */
function get_special_page(string $specialPage): string {
    global $phpversion;

    switch (strtoupper($specialPage)) {
        case 'ABOUT':
            return <<<ABOUT
            
                    <h1>Welcome to Micro Content Management System</h1>
                    <p>Micro Content Management System (µCMS) is a lightweight CMS software, which allow users to provide the content in Markdown files. The hierarchy of the pages are defined by the file hierarchy the markdown files are placed, and every page is queried with GET requests, thus eliminating the need to maintain a database on the host.</p>
                    <p>Originally I have created µCMS as a practice to learn PHP in the early 2000s, I have released it as a means to quickly create a CMS website, without much knowledge on building one. Although it is severely inadequate compared to other modern solution, this project remains only as an option to those, who wants to deepen their web server management skill, by introducing a tool that does not provide much help in maintaining the site for them, forcing their hands to a more hands-on experience, as technologically it is severely limited. That limitation is the reason it is called micro-CMS.</p>
                    <p>Micro Content Management System Copyright &copy; 2025 Ádám Juhász</p>
                    <p>PHP Markdown Lib Copyright &copy; 2004&ndash;2015 Michel Fortin <a href="https://michelf.ca/">https://michelf.ca/</a> All rights reserved.<br/>
                    Based on Markdown Copyright &copy; 2003&ndash;2005 John Gruber <a href="https://daringfireball.net/">https://daringfireball.net/</a> All rights reserved.</p>
                    <p>PHP SmartyPants Lib, Copyright &copy; 2005&ndash;2016 Michel Fortin <a href="https://michelf.ca/">https://michelf.ca/</a> All rights reserved.<br/>
                    SmartyPants, Copyright &copy; 2003&ndash;2004 John Gruber <a href="https://daringfireball.net/">https://daringfireball.net/</a> All rights reserved.</p>
                
            ABOUT;
        case 'VERSION':
            $mdv = Markdown::MARKDOWNLIB_VERSION;
            $spv = SmartyPants::SMARTYPANTSLIB_VERSION;
            return <<<VERSION

                        <h1>Micro Content Management System</h1>
                        <p>Version 2.0.0</p>
                        <p>Powered by:</p>
                        <ul id="versions">
                            <li>PHP {$phpversion()}</li>
                            <li>PHP Markdown Lib $mdv</li>
                            <li>PHP SmartyPants Lib $spv</li>
                            <script id="hljs-ver-script">
                                if (hljs !== undefined) {
                                    const hljs_text = document.createTextNode("Highlight.js " + hljs.versionString);
                                    const hljs_item = document.createElement("li");
                                    hljs_item.appendChild(hljs_text);
                                    document.getElementById("versions").appendChild(hljs_item);
                                }
                                document.getElementById("hljs-ver-script").remove();
                            </script>
                        </ul>

                VERSION;
        case 'CONFIG':
            global $TITLE, $DEFLANG, $COPYNOTE, $DOCROOT, $NAVPAGE, $ERRPAGE, $ORIGIN, $URL_PATH_BASE, $SITE_IMAGE;
            global $mdParser, $spParser;
            $title = $TITLE ?? '<em>Micro Content Management System</em>';
            $defLang = $DEFLANG ?? '<em>en</em>';
            $docRoot = $DOCROOT ?? '/';
            $navPage = $NAVPAGE ?? '<em>auto populated</em>';
            $errPage = $ERRPAGE ?? '<em>internal</em>';
            $origin = $ORIGIN ?? '<em>http://' . $_SERVER['HTTP_HOST'] . '</em>';
            $urlBase = $URL_PATH_BASE ?? '<em>/index.php?</em>';
            $siteImage = $SITE_IMAGE ?? '/images/ucms.png';
            $result = <<<CONFIG

                  <table>
                    <tr>
                      <th colspan="2">Site configuration</th>
                    </tr>
                    <tr>
                      <th>Setting</th>
                      <th>Value</th>
                    </tr>
                    <tr>
                      <th>Site title</th>
                      <td>$title</td>
                    </tr>
                    <tr>
                      <th>Default language</th>
                      <td>$defLang</td>
                    </tr>
                    <tr title="Before &micro;CMS copyright notice.">
                      <th>Copyright notice</th>
                      <td>$COPYNOTE</td>
                    </tr>
                    <tr title="Relative to index.php.">
                      <th>Document Root</th>
                      <td>$docRoot</td>
                    </tr>
                    <tr>
                      <th>Navigation page</th>
                      <td>$navPage</td>
                    </tr>
                    <tr>
                      <th>Error page</th>
                      <td>$errPage</th>
                    </tr>
                    <tr>
                      <th>Link Origin</th>
                      <td>$origin</td>
                    </tr>
                    <tr>
                      <th>Link Path Base</th>
                      <td>$urlBase</td>
                    </tr>
                    <tr>
                      <th>Site Image</th>
                      <td><img src="$siteImage"/></td>
                    </tr>
                    <tr>
                      <th colspan="2">Markdown parser configuration</th>
                    </tr>
                    <tr>
                      <th>Setting</th>
                      <th>Value</th>
                    </tr>
            
            CONFIG;
            foreach($mdParser as $key => $value) {
                $result .= "        <tr>\n";
                $result .= "          <th>$key</th>\n";
                $result .= "          <td><pre><code class=\"{$mdParser->code_class_prefix}php\" style=\"padding:0; background-color:initial;\">" . var_export($value, true) . "</code></pre></td>\n";
                $result .= "        </tr>\n";
            }
            $result .= <<<CONFIG
                    <tr>
                      <th colspan="2">SmartyPants parser configuration</th>
                    </tr>
                    <tr>
                      <th>Setting</th>
                      <th>Value</th>
                    </tr>
            CONFIG;
            foreach($spParser as $key => $value) {
                $result .= "        <tr>\n";
                $result .= "          <th>$key</th>\n";
                $result .= "          <td><pre><code class=\"{$mdParser->code_class_prefix}php\" style=\"padding:0; background-color:initial;\">" . var_export($value, true) . "</code></pre></td>\n";
                $result .= "        </tr>\n";
            }
            $result .= <<<CONFIG
                  </table>
            CONFIG;
            return $result;
        default:
            return '';
    }
}

/**
 * Renders the requested page from the similarly named markdown file.
 * @param Markdown $mdParser The markdown parser object.
 * @param SmartyPants $spParser The SmartyPants parser object.
 * @param string $docRoot The root directory of the markdown files.
 * @param string $page The name of the requested page.
 * @param string $lang The preferred language of the page.
 * @param string $head Additional content for the HTML head tag, like Open Graph protocol metadata.
 * @param string|null $siteTitle The title of the site.
 * @return string The content of the requested page.
 */
function get_local_page(Markdown $mdParser, SmartyPants $spParser, string $docRoot, string $page, string $lang, string &$head, ?string $siteTitle): string {
    global $trimmer, $replacer, $dater, $ORIGIN, $SITE_IMAGE, $URL_PATH_BASE;

    $localFilePath = $docRoot . '/' . get_file_with_markdown_extension($page);
    if (file_exists($localFilePath)) {
        $fileStat = stat($localFilePath);
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $fileStat['mtime']) . ' GMT');
        $markdown = file_get_contents($localFilePath);
        $mdLines = explode("\n", $markdown);
        $description = '';
        $searchDesc = '';
        $html = $spParser->transform($mdParser->transform($markdown));
        $img = null;
        $match = array();
        if (preg_match_all("/<p>(.*?)<\/p>/is", $html, $match) != false) {
            $paras = array_values(array_filter($match[1], 'strip_tags'));
            $searchDesc = strip_tags(array_filter($paras, 'strlen_not_null')[0]);
            $text = join(" ", $paras);
            if (preg_match('/<img src="(.*?)".*?>/i', $text, $match) != false) {
                $img = $match[1];
                if ($img[0] != '/') {
                    $img = dirname($_SERVER['REQUEST_URI']) . $img;
                }
            }
            $text = strip_tags($text);
            if (preg_match_all('/[.!?](\W|$)/s', $text, $match, PREG_OFFSET_CAPTURE) != false) {
                if (count($match[1]) == 1) {
                  $description = substr($text, 0, $match[1][0][1]);
                } else {
                  $description = substr($text, 0, $match[1][1][1]);
                }
            }
        }
        $origin = $ORIGIN ?? "http://" . $_SERVER['HTTP_HOST'];
        $siteImg = $img ?? $SITE_IMAGE ?? '/images/ucms.png';
        if (!str_starts_with($siteImg, 'http')) $siteImg = $origin . $siteImg;
        $urlPathBase = $URL_PATH_BASE ?? '/index.php?';
        $head = "<meta property=\"description\" content=\"$searchDesc\">";
        $head .= "\n    <meta property=\"og:type\" content=\"article\">";
        $head .= "\n    <meta property=\"og:image\" content=\"$siteImg\">";
        $head .= "\n    <meta property=\"og:title\" content=\"{$trimmer($mdLines[0], '\n\r\t\v\0 #')}\">";
        $head .= "\n    <meta property=\"og:url\" content=\"$origin$urlPathBase$page\">";
        $head .= "\n    <meta property=\"og:description\" content=\"{$trimmer($description)}\">";
        $head .= "\n    <meta property=\"og:locale\" content=\"{$replacer('-', '_', $lang)}\">";
        $head .= "\n    <meta property=\"og:site_name\" content=\"{$siteTitle}\">";
        $head .= "\n    <meta property=\"article:published_time\" content=\"{$dater('c', $fileStat['ctime'])}\">";
        $head .= "\n    <meta property=\"article:modified_time\" content=\"{$dater('c', $fileStat['mtime'])}\">";
        return $html;
    } else {
        error_log("Page not found: $localFilePath");
        $head = '<meta property="og:type" content="website">';
        $head .= "\n    <meta property=\"og:locale\" content=\"{$replacer('-', '_', $lang)}\">";
        $head .= "\n    <meta property=\"og:site_name\" content=\"{$siteTitle}\">";
        http_response_code(404);
        if (isset($ERRPAGE)) {
            $localErrorPath = $docRoot . '/' . get_file_with_markdown_extension($ERRPAGE);
        } else {
            $localErrorPath = $docRoot . '/' . '/err.md';
        }
        if (file_exists($localErrorPath)) {
            $markdown = file_get_contents($localErrorPath);
            return $spParser->transform($mdParser->transform($markdown));
        } else {
            error_log("Error page not found: $localErrorPath");
            return <<<BODY
                
                        <h1>The page was not found.</h1>
                        <p>The requested page could not be found on the server. Please check the URL and try again.</p>
                        <p>If you are the owner of this website, please check the file system for the requested page, or create a new page with the name of the requested page.</p>
                    
                BODY;
        }
    }
}

/**
 * Renders the requested special, or user page.
 * @param Markdown $mdParser The markdown parser object.
 * @param SmartyPants $spParser The SmartyPants parser object.
 * @param string $docRoot The root directory of the markdown files.
 * @param string $page The name of the requested page.
 * @param string $lang The preferred language of the page.
 * @param string $head Additional content for the HTML head tag, like Open Graph protocol metadata.
 * @param string|null $siteTitle The title of the site.
 * @return string The content of the requested page.
 */
function get_page(Markdown $mdParser, SmartyPants $spParser, string $docRoot, string $page, string $lang, string &$head, ?string $siteTitle): string {
    global $replacer;

    if (substr($page, 0, 1) == '*') {
        $head = '<meta property="og:type" content="website">';
        $head .= "\n    <meta property=\"og:locale\" content=\"{$replacer('-', '_', $lang)}\">";
        $head .= "\n    <meta property=\"og:site_name\" content=\"{$siteTitle}\">";
        return get_special_page(substr($page, 1));
    } else {
        return get_local_page($mdParser, $spParser, $docRoot, $page, $lang, $head, $siteTitle);
    }
}

?>