<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'AIchemist',
    'description' => 'Extension to do magic AI stuff around TYPO3',
    'category' => 'be',
    'author' => 'Marko Röper-Grewe',
    'author_email' => 'marko.roeper-grewe@udg.de',
    'author_company' => 'PIA / UDG',
    'state' => 'stable',
    'clearCacheOnLoad' => 1,
    'version' => '1.4.0',
    'iconIdentifier' => 'ext-aichemist-icon',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-11.5.99',
            'deepl-php' => '',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
