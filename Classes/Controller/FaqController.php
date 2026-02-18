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
        $parentCategoryUid = (int)($this->settings['parentCategory'] ?? 0);

        $groupedFaqs = [];
        if (!empty($pages) && $parentCategoryUid > 0) {
            $groupedFaqs = $this->faqRepository->findByPagesAndCategory($pages, $parentCategoryUid);
        }

        $this->view->assign('groupedFaqs', $groupedFaqs);

        return $this->htmlResponse();
    }
}
