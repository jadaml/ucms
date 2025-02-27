<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Michelf\Markdown;
use Michelf\SmartyPants;

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
                    $result .= '<li><a href="' . $base_url. (strlen($subdir) == 0 ? '' : $subdir . '/') . substr($file, 0, -3) . '">' . trim($title, "\n\r\t\v\0#") . '</a></li>';
                }
            }
        } while (count($path) > 0);

        return '<ul>' . $result . '</ul>';
    }
}
?>