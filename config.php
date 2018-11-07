<?php

$ravenClient = new Raven_Client('https://726f46c80e29410bb6158646cf21f96d@sentry.io/1317386');

Kirby::plugin('apecave/kirbycommerce', [
    'blueprints' => [
        'pages/products' => __DIR__ . '/blueprints/pages/products.yml',
    ],
    'routes' => require_once __DIR__ . '/routes.php',
    'fields' => [
      'products-sync' => [
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
    ],
    'siteMethods' => [
        'syncProduct' => function ($content = null) use($ravenClient) {

                $productsPage = $this->find('products');
                $allChildren = $productsPage->children()->merge($productsPage->drafts());
                $page = null;

                try {

                    if ( $page = $allChildren->findBy('product_id',$content['product_id']) ) {
                        $page->update($content);
                        $newSlug = Str::slug($content['custom_url']['url']);
                        $oldSlug = $page->slug();

                        if($newSlug != $oldSlug) {
                            $page->changeSlug($newSlug);
                        }
                    } else {
                        $page = $productsPage->createChild([
                          'content'  => $content,
                          'slug' => $content['custom_url']['url'],
                          'isDraft'  => false,
                          'template' => 'product'
                        ]);
                    }

                    //now that we have a page set the visibility to match bigcommerce
                    $newStatus = (bool) $content['is_visible'] ? 'listed': 'draft' ;
                    $oldStatus = $page->status(); 
                    if($newStatus != $oldStatus) {
                        $page->changeStatus($newStatus);
                    }

                }   catch(Exception $e) {

                    $ravenClient->captureException($e);
                  
                }  
        },
        'syncCategory' => function ($content = null) use($ravenClient) {

                $categoriesPage = $this->find('categories');
                $allChildren = $categoriesPage->children()->merge($categoriesPage->drafts());
                $page = null;

                try {

                    if ( $page = $allChildren->findBy('category_id',$content['category_id']) ) {
                        $page->update($content);
                        $newSlug = Str::slug($content['custom_url']['url']);
                        $oldSlug = $page->slug();

                        if($newSlug != $oldSlug) {
                            $page->changeSlug($newSlug);
                        }
                    } else {
                        $page = $categoriesPage->createChild([
                          'content'  => $content,
                          'slug' => $content['custom_url']['url'],
                          'isDraft'  => false,
                          'template' => 'category'
                        ]);
                    }

                    //now that we have a page set the visibility to match bigcommerce
                    $newStatus = (bool) $content['is_visible'] ? 'listed': 'draft' ;
                    $oldStatus = $page->status(); 
                    if($newStatus != $oldStatus) {
                        $page->changeStatus($newStatus);
                    }

                }   catch(Exception $e) {

                    $ravenClient->captureException($e);
                  
                }  
        }
    ],

]);


