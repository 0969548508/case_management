<?php
return [
    'case' => [
        'class' => App\Models\Matter::class,
        'property_path' => 'last_state',
        'states' => [
            [
                'name' => 'not-assigned',
                'metadata' => ['title' => 'Not assigned'],
            ],
            [
                'name' => 'to-do',
                'metadata' => ['title' => 'To do'],
            ],
            [
                'name' => 'in-progress',
                'metadata' => ['title' => 'In progress'],
            ],
            [
                'name' => 'cancelled',
                'metadata' => ['title' => 'Cancelled'],
            ],
            [
                'name' => 'completed',
                'metadata' => ['title' => 'Completed'],
            ],
            [
                'name' => 'on-hold',
                'metadata' => ['title' => 'On hold'],
            ],
            [
                'name' => 'invalid',
                'metadata' => ['title' => 'Invalid'],
            ],
            [
                'name' => 'withdrawn',
                'metadata' => ['title' => 'Withdrawn'],
            ]
        ],
        'transitions' => [
            'not-assigned' => [
                'from' => ['cancelled', 'completed', 'on-hold', 'invalid', 'withdrawn'],
                'to' => 'not-assigned'
            ],
            'to-do' => [
                'from' => ['not-assigned'],
                'to' => 'to-do'
            ],
            'in-progress' => [
                'from' => ['to-do', 'cancelled', 'completed', 'on-hold', 'invalid', 'withdrawn'],
                'to' => 'in-progress'
            ],
            'cancelled' => [
                'from' => ['not-assigned', 'to-do', 'in-progress'],
                'to' => 'cancelled'
            ],
            'completed' => [
                'from' => ['not-assigned', 'to-do', 'in-progress'],
                'to' => 'completed'
            ],
            'on-hold' => [
                'from' => ['not-assigned', 'to-do', 'in-progress'],
                'to' => 'on-hold'
            ],
            'invalid' => [
                'from' => ['not-assigned', 'to-do', 'in-progress'],
                'to' => 'invalid'
            ],
            'withdrawn' => [
                'from' => ['not-assigned', 'to-do', 'in-progress'],
                'to' => 'withdrawn'
            ]
        ]
    ],

    //...
];
