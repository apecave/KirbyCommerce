<?php
// $kirby = new Kirby([
//           'users' => [
//               [
//                   'email' => 'fake@getkirby.com',
//                   'role' => 'admin'
//               ]
//           ],
//           'user' => 'fake@getkirby.com'
//       ]);
Kirby::plugin('starterkit/demo', [
    'blueprints' => [
        'site'          => __DIR__ . '/blueprints/site.yml',
        'pages/products' => __DIR__ . '/blueprints/pages/products.yml',
    ],
    'fieldMethods' => [
        'test' => function () {
            return 'test';
        }
    ],
    'routes' => require_once __DIR__ . '/routes.php',

    'snippets' => [
        'header' => __DIR__ . '/snippets/header.php'
    ],
    'tags' => [
        'image' => Starterkit\Demo\Tags\Image::class
    ],
    'templates' => [
        'home' => __DIR__ . '/templates/home.php'
    ],
    'fields' => [
      'kc-products-sync' => [
        'props' => [
            'message' => function (string $message) {
                return $message;
            },
            'username' => function (string $username = 'The default username') {
                return $username;
            },
        ],
        'computed' => [
            'sentence' => function () {
                return $this->username . ' said ' . $this->message;
            },
            'progress' => function () {
                return 10;
            }
        ]
      ]
    ]
]);


function convert($size)
{
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

// Kirby::plugin(
//     'omz13/whatever',
//     [
//     // 'root'     => dirname(__FILE__, 1),
//     'fields' => [
//       'whatever' => [
//         'props' => [
//           'message' => function (string $message) {
//               return $message;
//           }
//         ]
//       ]
//     ]
//     ]
// );