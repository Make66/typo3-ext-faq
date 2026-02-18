<?php

declare(strict_types=1);

namespace Taketool\Faq\Controller;

use Psr\Http\Message\ResponseInterface;
use Taketool\Faq\Domain\Repository\FaqRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class FaqController extends ActionController
{
    public function __construct(
        protected readonly FaqRepository $faqRepository,
    ) {}

    public function listAction(): ResponseInterface
    {
        $pages = GeneralUtility::intExplode(',', $this->settings['pages'] ?? '', true);
        $selectedCategories = GeneralUtility::intExplode(',', $this->settings['selectedCategories'] ?? '', true);

        $groupedFaqs = [];
        if (!empty($pages) && !empty($selectedCategories)) {
            $groupedFaqs = $this->faqRepository->findByPagesAndCategories($pages, $selectedCategories);
        }

        $this->view->assign('groupedFaqs', $groupedFaqs);

        return $this->htmlResponse();
    }
}
