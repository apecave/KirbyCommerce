<?php
use ApeCave\KirbyCommerce\Products;

return [
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
                usleep(100000);
            }

            emitFlush('100%: Done');
            
            return;
            
        }
    ]
];

