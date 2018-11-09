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
    'pageMethods' => [
        'draftsIndex' => function () {
            //get first layer of drafts
            $drafts = $this->index()->drafts()->merge($this->drafts());

           
            $drafts = recursive($drafts);
           
            return $drafts ;

        },
        'trueIndex' => function () {
            $trueIndex = $this->draftsIndex()->merge($this->index());
            $trueIndex->sortBy('uri', 'asc');
            return $trueIndex->sortBy('uri', 'asc');
        },
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
                unset($content['children']); //just need the parent each time
                $kirby = new Kirby();



                $categoriesPage = $kirby->site()->find('categories');
                $allChildren = $categoriesPage->trueIndex();
                // dump($allChildren); 
                $parent = $allChildren->findBy('category_id',$content['parent_id']) ?? $categoriesPage;
                // dump($parent);
                // dump($content['parent_id']);
                // dump($content);
                $page = null;
                try {

                    if ( $page = $allChildren->findBy('category_id',$content['category_id']) ) {
                        echo 'update';
                        $page->update($content);
                        $newSlug = $content['slug'];
                        $oldSlug = $page->slug();

                        if($newSlug != $oldSlug) {
                            $page->changeSlug($newSlug);
                        }

                    } else {
                        echo 'create';
                        
                        // dump($content);
                        $page = $parent->createChild([
                          'content'  => $content,
                          'slug' => $content['slug'],
                          'isDraft'  => false,
                          'template' => 'categories'
                        ]);

                    }

                    // //now that we have a page set the visibility to match bigcommerce
                    $newStatus = (bool) $content['is_visible'] ? 'listed': 'draft' ;
                    $oldStatus = $page->status(); 
                    if($newStatus != $oldStatus) {
                        $page->changeStatus($newStatus);
                    }

                }   catch(Exception $e) {
                    echo $e->getMessage();
                    $ravenClient->captureException($e);
                  
                }  
        }
    ],

]);

 //only have to get drafts recursively
            function recursive($drafts) {
                
                foreach($drafts as $key => $page) {

                        $newItems = $page->index();
                        $newItems = $newItems->merge($page->drafts());
                        $newItems = recursive($newItems); 

                        $drafts = $drafts->merge($page->drafts());
                        $drafts = $drafts->merge($page->index());

                        $drafts = $drafts->merge($newItems );
      
                }
                return $drafts;
            }
