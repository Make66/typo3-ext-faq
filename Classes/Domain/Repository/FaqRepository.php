<?php

declare(strict_types=1);

namespace Taketool\Faq\Domain\Repository;

use Taketool\Faq\Domain\Model\Faq;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class FaqRepository extends Repository
{
    /**
     * Find FAQs from given pages belonging to the selected categories,
     * and return them grouped by category UID.
     *
     * @param int[] $pageIds
     * @param int[] $categoryUids
     * @return array<int, array{category: \TYPO3\CMS\Extbase\Domain\Model\Category, faqs: Faq[]}>
     */
    public function findByPagesAndCategories(array $pageIds, array $categoryUids): array
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $constraints = [];

        if (!empty($pageIds)) {
            $constraints[] = $query->in('pid', $pageIds);
        }

        $constraints[] = $query->in('categories.uid', $categoryUids);

        if (!empty($constraints)) {
            $query->matching($query->logicalAnd(...$constraints));
        }

        $query->setOrderings(['sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING]);

        if (false) {
            $typo3DbQueryParser = GeneralUtility::makeInstance(Typo3DbQueryParser::class);
            $queryBuilder = $typo3DbQueryParser->convertQueryToDoctrineQueryBuilder($query);
            DebuggerUtility::var_dump($queryBuilder->getSQL());
            DebuggerUtility::var_dump($queryBuilder->getParameters());
        }

        $faqs = $query->execute()->toArray();

        $grouped = [];
        /** @var Faq $faq */
        foreach ($faqs as $faq) {
            foreach ($faq->getCategories() as $category) {
                if (in_array($category->getUid(), $categoryUids, true)) {
                    $uid = $category->getUid();
                    if (!isset($grouped[$uid])) {
                        $grouped[$uid] = [
                            'category' => $category,
                            'faqs' => [],
                        ];
                    }
                    $grouped[$uid]['faqs'][] = $faq;
                    break;
                }
            }
        }

        return $grouped;
    }
}
