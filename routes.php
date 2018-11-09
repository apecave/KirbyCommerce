<?php
use ApeCave\KirbyCommerce\Products;
use ApeCave\KirbyCommerce\Categories;

return [
    [
        'pattern' => 'api/deepindex',
        'action'  => function () {
            // $trueIndex = page('categories/accessories')->drafts()->drafts()->index();
            $trueIndex = page('categories')->trueIndex();

            dump($trueIndex);
            
        }
    ],
    [
        'pattern' => 'api/products',
        'action'  => function () {
            header( 'Transfer-Encoding: chunked; Content-type: text/html; charset=utf-8' );
            echo "<style>html {white-space:pre}</style>\r\n";
            emitFlush('5%: Initializing');

            //get our products from bigcommerce
            $products = Products::get(); 

            //setup some progress weights
            $total = count($products);
            $initialWeight = $total / 2;
            $weightedTotal = $total + $initialWeight; 
            $initalPercent = round($initialWeight/$weightedTotal * 100);

            emitFlush($initalPercent.'%: Initializing Complete');
            sleep(1);

            //sync each product
            $site = site();
            for($i = 0; $i < $total; $i++){
                $content = $products[$i];
                $productNum = $i + 1;
                $progress =  round($productNum/$weightedTotal * 100 + $initalPercent);

                $site->syncProduct($content);

                emitFlush($progress.'%: Saving Products');
                // usleep(100000);
            }

            emitFlush('100%: Done');
            
            return;
            
        }
    ],
    [
        'pattern' => 'api/categories',
        'action'  => function () {
            header( 'Transfer-Encoding: chunked; Content-type: text/html; charset=utf-8' );
            echo "<style>html {white-space:pre}</style>\r\n";
            emitFlush('5%: Initializing');

            //get our products from bigcommerce
            $categories = Categories::get(); 
            
            
            // dump($tree); die;
            //setup some progress weights
            $total = count($categories);
            $initialWeight = $total / 2;
            $weightedTotal = $total + $initialWeight; 
            $initalPercent = round($initialWeight/$weightedTotal * 100);

            emitFlush($initalPercent.'%: Initializing Complete');
            sleep(1);

            //sync each category
            $site = site();
            $collection = new Collection($categories);
            $sorted = $collection->sortBy('parent_id','asc');
           
            foreach ($sorted as $key => $category) {
                // dump($category);     
                $catNum = $key + 1;
                $progress =  round($catNum/$weightedTotal * 100 + $initalPercent);

                // if($catNum < 5) {
                                    $site->syncCategory($category);

                // }

                emitFlush($progress.'%: Saving Categories');
                // usleep(100000);
            }
     

            emitFlush('100%: Done');
            
            return;
            
        }
    ]
];


function buildTree($items) {
    $children = array();
    foreach($items as &$item) $children[$item['parent_id']][] = &$item;
    unset($item);
    foreach($items as &$item) if (isset($children[$item['category_id']]))
            $item['children'] = $children[$item['category_id']];
    return $children[0];
}
function putTree($num) {
 
  // (do the required processing...)
    emitFlush($num.'%: Saving Products');
    //If the number is less or equal to 50.
    if($num < 50){
        //Call the function again. Increment number by one.
        return putTree($num + 1);
    }
 

}