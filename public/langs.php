<?php

class RequestLanguage {
    private string $language;
    private string $culture;
    private float $quality;

    public function getLanguage(): string {
        return $this->language;
    }

    public function getCulture(): string {
        if (strlen($this->culture) == 0) return $this->language;
        else return $this->language . '-' . $this->culture;
    }

    public function getQuality(): float {
        return $this->quality;
    }

    public function __construct(string $language) {
        $language = explode(';', $language);
        $this->quality = count($language) == 2 ? floatval(substr($language[1], 2)) : 1.0;
        $language = explode('-', $language[0]);
        $this->language = $language[0];
        $this->culture = $language[1] ?? '';
    }
}

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

?>