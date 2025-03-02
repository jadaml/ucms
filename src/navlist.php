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
 * Contains the navigation list building functions for Micro Content Management System.
 */

require_once __DIR__ . '/../vendor/autoload.php';
use Michelf\Markdown;
use Michelf\SmartyPants;

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
        $pExt = pathinfo($page, PATHINFO_EXTENSION);
        $localPath = $base_dir . '/' . $page . ($pExt == 'md' || $pExt == 'markdown' ? '' : '.md');
        $markdown = file_get_contents($localPath);
        return $spParser->transform($mdParser->transform($markdown));
    } else{
        $path = array();
        $result = '';

        do {
            if (count($path) > 0) {
                $subdir = $path[0];
                $scanPath = $base_dir . '/' . $path[0];
                $path = array_splice($path, 0, 1);
            } else {
                $subdir = '';
                $scanPath = $base_dir;
            }

            foreach(scandir($scanPath) as $file) {
                if ($file[0] == '.') continue;
                $subpath = strlen($subdir) == 0 ? $file : $subdir . '/' . $file;
                $localPath = $base_dir . '/' . $subpath;
                if (is_dir($localPath)) {
                    array_push($path, $subpath);
                } elseif (is_file($localPath) && substr($file, -3) == '.md') {
                    $mdFile = fopen($localPath, 'r');
                    $title = fgets($mdFile);
                    fclose($mdFile);
                    $result .= '<li><a href="' . $base_url. (strlen($subdir) == 0 ? '' : $subdir . '/') . substr($file, 0, -3) . '">' . trim($title, "\n\r\t\v\0 #") . '</a></li>';
                }
            }
        } while (count($path) > 0);

        return '<ul>' . $result . '</ul>';
    }
}
?>