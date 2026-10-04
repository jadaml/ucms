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
 * @file navlist.php
 * @brief Contains the navigation list building functions for Micro Content Management System.
 * @author Ádám L. Juhász
 * @copyright GNU General Public License v3
 * @version 2.0
 * @date 2025, 2026
 */

require_once __DIR__ . '/../vendor/autoload.php';
use Michelf\Markdown;
use Michelf\SmartyPants;

include_once __DIR__ . '/../config/ucms.php';
include_once __DIR__ . '/utils.php';

/**
 * Build a navigation list from the markdown files found in the document root.
 * @param Markdown $mdParser The markdown parser to use.
 * @param SmartyPants $spParser The SmartyPants parser to use.
 * @param string $base_dir The base document root directory to search for markdown files.
 * @param string $base_url The base URL to use for links.
 * @param string|null $page The page to display as the navigation list, or null to build the navigation list from the filesystem.
 * @return string The navigation list to display on the site.
 */
function build_nav_list(Markdown $mdParser, SmartyPants $spParser, string $base_dir, string $base_url, ?string $page): string {
    if (isset($page)) {
        $localPath = realpath($base_dir . '/' . get_file_with_markdown_extension($page));
        if ($localPath !== false
         && str_starts_with($localPath, $base_dir . '/')
         && file_exists($localPath)) {
            $markdown = file_get_contents($localPath);
            return $spParser->transform($mdParser->transform($markdown));
        } else {
            error_log('Navigation page not found: ' . $page);
        }
    }
    $path = array();
    $result = '';
    $p404 = isset($ERRPAGE) ? get_file_with_markdown_extension($ERRPAGE) : UCMS_FALLBACK_ERROR_PAGE;

    do {
        if (($nextPath = array_pop($path)) !== null) {
            $subdir = $nextPath;
            $scanPath = $base_dir . '/' . $nextPath;
        } else {
            $subdir = '';
            $scanPath = $base_dir;
        }

        foreach(scandir($scanPath) as $file) {
            if ($file[0] == '.') continue;
            if ($file == $p404) continue;
            $subpath = strlen($subdir) == 0 ? $file : $subdir . '/' . $file;
            $localPath = $base_dir . '/' . $subpath;
            if (is_dir($localPath)) {
                array_push($path, $subpath);
            } elseif (is_file($localPath) && is_markdown_with_extension($localPath)) {
                $mdFile = fopen($localPath, 'r');
                $title = fgets($mdFile);
                fclose($mdFile);
                $result .= '<li><a href="' . $base_url. (strlen($subdir) == 0 ? '' : $subdir . '/') . substr($file, 0, -3) . '">' . trim($title, "\n\r\t\v\0 #") . '</a></li>';
            }
        }
    } while (count($path) > 0);

    return '<ul>' . $result . '</ul>';
}
?>