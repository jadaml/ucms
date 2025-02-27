<?php

class RequestLanguage {
    private string $language;
    private string $culture;
    private float $quotient;

    public function getLanguage(): string {
        return $this->language;
    }

    public function getCulture(): string {
        if (strlen($this->culture) == 0) return $this->language;
        else return $this->language . '-' . $this->culture;
    }

    public function getQuotient(): float {
        return $this->quotient;
    }

    public function __construct(string $language) {
        $language = explode(';', $language);
        $this->quotient = count($language) == 2 ? floatval(substr($language[1], 2)) : 1.0;
        $language = explode('-', $language[0]);
        $this->language = $language[0];
        $this->culture = $language[1] ?? '';
    }
}

function get_request_language(string $docRoot, ?string $defaultLanguage): string {
    $languages = array();
    foreach (explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']) as $language) {
        $languages[] = new RequestLanguage($language);
    }

    usort($languages, function(RequestLanguage $a, RequestLanguage $b) {
        return $b->getQuotient() <=> $a->getQuotient();
    });

    foreach (scandir($docRoot) as $dir) {
        if (!is_dir($dir)) continue;
        foreach ($languages as $lang) {
            if ($dir == $lang->getLanguage() || $dir == $lang->getCulture()) {
                return $dir;
            }
        }
    }

    return $defaultLanguage ?? 'en';
}

?>