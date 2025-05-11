<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'AIchemist',
    'description' => 'Extension to do magic AI stuff around TYPO3',
    'category' => 'be',
    'author' => 'Marko Röper-Grewe',
    'author_email' => 'marko.roeper-grewe@udg.de',
    'author_company' => 'MSQ / UDG',
    'state' => 'stable',
    'version' => '13.4.0',
    'iconIdentifier' => 'ext-aichemist-icon',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-13.4.99',
            'deepl-php' => '',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
