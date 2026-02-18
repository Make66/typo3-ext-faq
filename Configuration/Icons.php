<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
    'ext-faq-icon' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:faq/Resources/Public/Icons/Extension.svg',
    ],
];
