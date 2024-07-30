<?php
namespace MRG\Aichemist\Controller;

use MRG\Aichemist\Service\DeepLService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class TranslationController extends ActionController
{
    private $deepLService;
    private $logger;

    public function __construct(DeepLService $deepLService, LogManager $logManager)
    {
        $this->deepLService = $deepLService;
        $this->logger = $logManager->getLogger(__CLASS__);
    }

    public function translateAction(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $postData = $request->getParsedBody();
            $text = html_entity_decode($postData['text'] ?? '', ENT_QUOTES, 'UTF-8');
            $targetLang = $postData['targetLang'] ?? '';

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
