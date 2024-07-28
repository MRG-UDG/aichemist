<?php
namespace MRG\Aichemist\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Log\LogManager;
use MRG\Aichemist\Service\DeepLService;

class TranslationController
{
    private $deepLService;
    private $logger;

    public function __construct(DeepLService $deepLService, LogManager $logManager)
    {
        $this->deepLService = $deepLService;
        $this->logger = $logManager->getLogger(__CLASS__);
    }


    public function translateAction(): ResponseInterface
    {
        try {
            $text = html_entity_decode($_POST['text'] ?? '', ENT_QUOTES, 'UTF-8');
            $targetLang = $_POST['targetLang'] ?? '';

            // Log the received data
            $this->logger->info('Received data for translation', [
                'text' => $text,
                'targetLang' => $targetLang
            ]);

            if (empty($text) || empty($targetLang)) {
                throw new \InvalidArgumentException('Text and target language are required.');
            }

            $translatedText = $this->deepLService->translate($text, $targetLang);

            // Log the translated text
            $this->logger->info('Translated text', [
                'translatedText' => $translatedText
            ]);

            return new JsonResponse(['translatedText' => $translatedText]);
        } catch (\Exception $e) {
            // Log the error
            $this->logger->error('Translation error', [
                'exception' => $e->getMessage()
            ]);

            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }
}
