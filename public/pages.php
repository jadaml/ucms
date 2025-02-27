<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Michelf\Markdown;
use Michelf\SmartyPants;

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
    }
}

function get_local_page(Markdown $mdParser, SmartyPants $spParser, string $docRoot, string $page, string $lang, string &$head): string {
    $qExt = pathinfo($page, PATHINFO_EXTENSION);
    $localFilePath = $docRoot . '/' . $page . ($qExt == 'md' || $qExt == 'markdown' ? '' : '.md');
    if (file_exists($localFilePath)) {
        $fileStat = stat($localFilePath);
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $fileStat['mtime']) . ' GMT');
        $markdown = file_get_contents($localFilePath);
        // TODO: Populate $head with Open Graph Protocol meta tags
        $head = '<!-- Open Graph Protocol meta tags -->';
        return $spParser->transform($mdParser->transform($markdown));
    } else {
        $head = '';
        http_response_code(404);
        $localErrorPath = $docRoot . '/' . $lang . '/404.md';
        if (file_exists($localErrorPath)) {
            $markdown = file_get_contents($localErrorPath);
            return $spParser->transform($mdParser->transform($markdown));
        } else {
            return <<<BODY
                
                        <h1>Error: The page was not found.</h1>
                        <p>The requested page could not be found on the server. Please check the URL and try again.</p>
                        <p>If you are the owner of this website, please check the file system for the requested page, or create a new page with the name of the requested page.</p>
                    
                BODY;
        }
    }
}

function get_page(Markdown $mdParser, SmartyPants $spParser, string $docRoot, string $page, string $lang, string &$head): string {
    if (substr($page, 0, 1) == '*') {
        return get_special_page(substr($page, 1));
    } else {
        return get_local_page($mdParser, $spParser, $docRoot, $page, $lang, $head);
    }
}

?>