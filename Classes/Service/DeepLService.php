<?php
namespace MRG\Aichemist\Service;

use DeepL\Translator;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class DeepLService
{
    private $translator;

    public function __construct()
    {
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $apiKey = $extensionConfiguration->get('aichemist', 'apiKey');

        // Überprüfen Sie, ob es sich um einen Free API Key handelt
        if (strpos($apiKey, 'free:') === 0) {
            $apiKey = substr($apiKey, 5); // Entfernen Sie das "free:" Präfix
        }

        $this->translator = new Translator($apiKey);
    }

    public function translate(string $text, string $targetLang): string
    {
        try {
            $result = $this->translator->translateText($text, null, $targetLang);
            return $result->text;
        } catch (\Exception $e) {
            // Fehlerbehandlung
            return 'Übersetzung fehlgeschlagen: ' . $e->getMessage();
        }
    }
}
