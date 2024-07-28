<?php
namespace MRG\Aichemist\Service;

use DeepL\Translator;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class DeepLService
{
    private $translator;
    private $apiKey;

    public function __construct()
    {
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $this->apiKey = $extensionConfiguration->get('aichemist', 'apiKey');

        // Überprüfen Sie, ob es sich um einen Free API Key handelt
        if (strpos($this->apiKey, 'free:') === 0) {
            $this->apiKey = substr($this->apiKey, 5); // Entfernen Sie das "free:" Präfix
        }

        // Translator nur initialisieren, wenn ein API-Key vorhanden ist
        if (!empty($this->apiKey)) {
            $this->translator = new Translator($this->apiKey);
        }
    }

    public function translate(string $text, string $targetLang): string
    {
        if (empty($this->apiKey)) {
            return 'Übersetzung fehlgeschlagen: Kein API-Key vorhanden';
        }

        try {
            $result = $this->translator->translateText($text, null, $targetLang);
            return $result->text;
        } catch (\Exception $e) {
            // Fehlerbehandlung
            return 'Übersetzung fehlgeschlagen: ' . $e->getMessage();
        }
    }
}
