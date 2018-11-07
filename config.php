<?php
Kirby::plugin('starterkit/demo', [
    'blueprints' => [
        'pages/products' => __DIR__ . '/blueprints/pages/products.yml',
    ],
    'routes' => require_once __DIR__ . '/routes.php',
    'fields' => [
      'kc-products-sync' => [
        'props' => [
            'message' => function (string $message = null) {
                return $message;
            },
            'disabled' => function (bool $disabled = false) {
                return $disabled;
            },
        ],
        'computed' => [
            'progress' => function () {
                return 10;
            }
        ]
      ]
    ]
]);
