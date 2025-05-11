<?php
namespace MRG\Aichemist\Controller;

use MRG\Aichemist\Service\DeepLService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Extbase\Annotation\Inject;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Throwable;

class TranslationController extends ActionController
{
    protected DeepLService $deepLService;

    public function __construct(DeepLService $deepLService)
    {
        $this->deepLService = $deepLService;
    }

    public function translateAction(): ResponseInterface
    {
        try {
            $text = html_entity_decode($_POST['text'] ?? '', ENT_QUOTES, 'UTF-8');
            $targetLang = $_POST['targetLang'] ?? '';

            if (empty($text) || empty($targetLang)) {
                throw new \InvalidArgumentException('Text und Zielsprache sind erforderlich.');
            }

            $translatedText = $this->deepLService->translate($text, $targetLang);

            return (new JsonResponse(['translatedText' => $translatedText]))->withHeader('Content-Type', 'application/json');
        } catch (Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }
}
