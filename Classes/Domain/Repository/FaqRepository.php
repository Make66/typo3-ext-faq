<?php

declare(strict_types=1);

namespace Taketool\Faq\Domain\Repository;

use Taketool\Faq\Domain\Model\Faq;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\Repository;

class FaqRepository extends Repository
{
    /**
     * Find FAQs from given pages whose categories are children of the parent category,
     * and return them grouped by category UID.
     *
     * @param int[] $pageIds
     * @param int $parentCategoryUid
     * @return array<int, array{category: \TYPO3\CMS\Extbase\Domain\Model\Category, faqs: Faq[]}>
     */
    public function findByPagesAndCategory(array $pageIds, int $parentCategoryUid): array
    {
        // Find child category UIDs of the parent category
        $childCategoryUids = $this->getChildCategoryUids($parentCategoryUid);
        if (empty($childCategoryUids)) {
            return [];
        }

        $query = $this->createQuery();

        $querySettings = $query->getQuerySettings();
        $querySettings->setRespectStoragePage(false);

        $constraints = [];

        // Restrict to given pages
        if (!empty($pageIds)) {
            $constraints[] = $query->in('pid', $pageIds);
        }

        // Restrict to FAQs that have at least one of the child categories
        $constraints[] = $query->in('categories.uid', $childCategoryUids);

        if (!empty($constraints)) {
            $query->matching($query->logicalAnd(...$constraints));
        }

        $query->setOrderings(['sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING]);

        $faqs = $query->execute()->toArray();

        // Group by category
        $grouped = [];
        /** @var Faq $faq */
        foreach ($faqs as $faq) {
            foreach ($faq->getCategories() as $category) {
                if (in_array($category->getUid(), $childCategoryUids, true)) {
                    $uid = $category->getUid();
                    if (!isset($grouped[$uid])) {
                        $grouped[$uid] = [
                            'category' => $category,
                            'faqs' => [],
                        ];
                    }
                    $grouped[$uid]['faqs'][] = $faq;
                    break; // Only assign to the first matching child category
                }
            }
        }

        return $grouped;
    }

    /**
     * @return int[]
     */
    protected function getChildCategoryUids(int $parentUid): array
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('sys_category');

        $result = $connection->select(
            ['uid'],
            'sys_category',
            ['parent' => $parentUid]
        );

        $uids = [];
        foreach ($result->fetchAllAssociative() as $row) {
            $uids[] = (int)$row['uid'];
        }

        return $uids;
    }
}
