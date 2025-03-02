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

include_once __DIR__ . '/utils.php';

global $trimmer, $replacer, $dater;

$trimmer = "trim";
$replacer = "str_replace";
$dater = "gmdate";

/**
 * Produces the content of a special, build-in page.
 * @param string $specialPage The name of the special page.
 * @return string The content of the special page.
 */
function get_special_page(string $specialPage): string {
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
            break;
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
    $localFilePath = $docRoot . '/' . get_file_with_markdown_extension($page);
    if (file_exists($localFilePath)) {
        $fileStat = stat($localFilePath);
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $fileStat['mtime']) . ' GMT');
        $markdown = file_get_contents($localFilePath);
        $mdLines = explode("\n", $markdown);
        $description = '';
        foreach ($mdLines as $line) {
            if (strlen($line) == 0 && strlen($description) == 0 || substr($line, 0, 1) == '#') continue;
            elseif (strlen($line) == 0) break;
            $description .= ' ' . $line;
        }
        $pPos = strpos($description, '.');
        if ($pPos !== false) {
            $pPos = strpos($description, '.', $pPos + 1);
            if ($pPos !== false) {
                $description = substr($description, 0, $pPos + 1);
            }
        }
        $head = '<meta property="og:type" content="article">';
        $head .= "\n    <meta property=\"og:title\" content=\"{$GLOBALS["trimmer"]($mdLines[0], '\n\r\t\v\0 #')}\">";
        $head .= "\n    <meta property=\"og:description\" content=\"{$GLOBALS["trimmer"]($description)}\">";
        $head .= "\n    <meta property=\"og:locale\" content=\"{$GLOBALS["replacer"]('-', '_', $lang)}\">";
        $head .= "\n    <meta property=\"og:site_name\" content=\"{$siteTitle}\">";
        $head .= "\n    <meta property=\"article:published_time\" content=\"{$GLOBALS["dater"]('c', $fileStat['ctime'])}\">";
        $head .= "\n    <meta property=\"article:modified_time\" content=\"{$GLOBALS["dater"]('c', $fileStat['mtime'])}\">";
        return $spParser->transform($mdParser->transform($markdown));
    } else {
        error_log("Page not found: $localFilePath");
        $head = '<meta property="og:type" content="website">';
        $head .= "\n    <meta property=\"og:locale\" content=\"{$GLOBALS["replacer"]('-', '_', $lang)}\">";
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
    if (substr($page, 0, 1) == '*') {
        $head = '<meta property="og:type" content="website">';
        $head .= "\n    <meta property=\"og:locale\" content=\"{$GLOBALS["replacer"]('-', '_', $lang)}\">";
        $head .= "\n    <meta property=\"og:site_name\" content=\"{$siteTitle}\">";
        return get_special_page(substr($page, 1));
    } else {
        return get_local_page($mdParser, $spParser, $docRoot, $page, $lang, $head, $siteTitle);
    }
}

?>