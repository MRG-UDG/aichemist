<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'AIchemist',
    'description' => 'Extension to do magic AI stuff around TYPO3',
    'category' => 'be',
    'author' => 'Marko Röper-Grewe',
    'author_email' => 'marko.roeper-grewe@udg.de',
    'author_company' => 'PIA / UDG',
    'state' => 'stable',
    'version' => '2.0.0',
    'iconIdentifier' => 'ext-aichemist-icon',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-12.4.99',
            'deepl-php' => '',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
