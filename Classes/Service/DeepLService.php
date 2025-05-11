<?php
namespace MRG\Aichemist\Service;

use DeepL\Translator;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Throwable;

class DeepLService
{
    private Translator $translator;
    private string $apiKey;

    public function __construct()
    {
        // Direkt `ExtensionConfiguration` abrufen
        /** @var ExtensionConfiguration $extensionConfiguration */
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $this->apiKey = $extensionConfiguration->get('aichemist', 'apiKey');

        // Überprüfen, ob es sich um einen Free API Key handelt
        if (strpos($this->apiKey, 'free:') === 0) {
            $this->apiKey = substr($this->apiKey, 5);
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
        } catch (Throwable $e) {
            return 'Übersetzung fehlgeschlagen: ' . $e->getMessage();
        }
    }
}
