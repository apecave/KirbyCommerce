<?php

return [
  [
        'pattern' => 'api/products',
        'action'  => function () {
            header( 'Transfer-Encoding: chunked; Content-type: text/html; charset=utf-8' );
            echo "<style>html {white-space:pre}</style>\r\n";
            echo "5%: Initializing\r\n";
            flush();
            ob_flush();

            $bcconfig = option('bcconfig');
            $client = new GuzzleHttp\Client([
                'base_uri' => 'http://api.bigcommerce.com/stores/'.$bcconfig['store_hash'].'/v3/',
                'timeout'  => 2.0,
                'headers' => [
                    'X-store_hash' => $bcconfig['store_hash'],
                    'X-Auth-Client' => $bcconfig['client_id'],
                    'X-Auth-Token' => $bcconfig['auth_token']

                ]
            ]);
            $response = $client->request('GET', 'catalog/products',['query' => ['include' => 'images']]);
            $json = $response->getBody()->getContents();
            $resource = Kirby\Data\Json::decode( $json );
            $array = $resource['data'];
            $total = count($array);
            $initialWeight = $total / 2;
            $weightedTotal = $total + $initialWeight; 
            $initalPercent = round($initialWeight/$weightedTotal * 100);

            echo "{$initalPercent}%: Initializing Complete\r\n";
            flush();
            ob_flush();
            sleep(1);

            for($i = 0; $i < $total; $i++){
                $pageData = $array[$i];
                $productNum = $i + 1;
                $string =  round($productNum/$weightedTotal * 100 + $initalPercent);

                try {
                    kirby()->page('products')->createChild([
                    'content'  => $pageData,
                    'slug' => $pageData['custom_url']['url'].uniqid(),
                    'isDraft'  => false,
                    'template' => 'article'
                    ]);
                }   catch(DuplicateException $e) {

                  echo $e->getMessage();

                }                
                echo "{$string}%: Saving Products\r\n";
                flush();
                ob_flush();
                usleep(100000);
            }

            echo "100%: Done\r\n";
            flush();
            ob_flush();
            
            return;
            
        }
    ],
    [
        'pattern' => 'api/chunked',
        'action'  => function () {
            
            header( 'Transfer-Encoding: chunked; Content-type: text/html; charset=utf-8' );
            echo "<style>html {white-space:pre}</style>\r\n";
            echo "0%: Initializing\r\n";
            flush();
            ob_flush();

            $resource = KirbyCommerce\Products::get();
            $total = count($resource);
            $initialWeight = $total / 2;
            $weightedTotal = $total + $initialWeight; 
            $array = KirbyCommerce\Products::toArray($resource);
            $initalPercent = round($initialWeight/$weightedTotal * 100);
            $kirby = kirby();
            
            echo "{$initalPercent}%: Initializing Complete\r\n";
            flush();
            ob_flush();
            sleep(1);

            for($i = 0; $i < $total; $i++){
                
                $memory = convert(memory_get_usage());
                $productNum = $i + 1;
                $string =  round($productNum/$weightedTotal * 100 + $initalPercent);

                KirbyCommerce\Product::save($array[$i], $kirby);
                
                // echo "Memory usage is {$memory}\r\n";
                echo "{$string}%: Saving Product #{$productNum} \r\n";
                flush();
                ob_flush();
                usleep(100000);
            }
            
        }
    ]
];