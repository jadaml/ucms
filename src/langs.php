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
 * @file langs.php
 * Contains the language handling functions for Micro Content Management System.
 */
/** Represents a language from the Accept-Language header. */
class RequestLanguage {
    /** The country code of the language value. */
    private string $language;

    /** The script code of the language value. */
    private string $culture;

    /** The quality value of the language value. */
    private float $quality;

    /**
     * Gets the language code of the language value.
     * @return string The language code of the language value.
     */
    public function getLanguage(): string {
        return $this->language;
    }

    /**
     * Gets the culture code of the language value.
     * @return string The culture code of the language value.
     */
    public function getCulture(): string {
        if (strlen($this->culture) == 0) return $this->language;
        else return $this->language . '-' . $this->culture;
    }

    /**
     * Gets the quality value of the language value.
     * @return float The quality value of the language value.
     */
    public function getQuality(): float {
        return $this->quality;
    }

    /**
     * Initializes a new instance of the RequestLanguage class.
     * @param string $language The language value to parse.
     */
    public function __construct(string $language) {
        $language = explode(';', $language);
        $this->quality = count($language) == 2 ? floatval(substr($language[1], 2)) : 1.0;
        $language = explode('-', $language[0]);
        $this->language = $language[0];
        $this->culture = $language[1] ?? '';
    }
}

/**
 * Gets the best fitting of preferred languages for the request.
 * @param string $docRoot The root directory of the pages to consider.
 * @param string|null $page The requested page to check for.
 * @param string|null $defaultLanguage The fallback language to use.
 * @return string The best fitting of preferred languages of the request.
 */
function get_request_language(string $docRoot, ?string $page, ?string $defaultLanguage): string {
    $languages = array();
    foreach (explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']) as $language) {
        $languages[] = new RequestLanguage($language);
    }

    usort($languages, function(RequestLanguage $a, RequestLanguage $b) {
        return $b->getQuality() <=> $a->getQuality();
    });

    foreach (scandir($docRoot) as $dir) {
        if (!is_dir($dir)) continue;
        foreach ($languages as $lang) {
            if ($page !== null) {
                $pExt = pathinfo($page, PATHINFO_EXTENSION);
                $localPath = $docRoot . '/' . $dir . '/' . $page . ($pExt == 'md' || $pExt == 'markdown' ? '' : '.md');
                if (!is_file($localPath)) {
                    continue;
                }
            }
            if ($dir == $lang->getLanguage() || $dir == $lang->getCulture()) {
                return $dir;
            }
        }
    }

    return $defaultLanguage ?? 'en';
}

$matches = array();
if (preg_match('/^([a-zA-Z]{2}(-[a-zA-Z]{2})?)\//', $QSTR, $matches, PREG_UNMATCHED_AS_NULL) === 1)
{
    $LANG = $matches[1];
} else {
    $LANG = get_request_language($_DOCROOT, $QSTR, $DEFLANG);
}

?>