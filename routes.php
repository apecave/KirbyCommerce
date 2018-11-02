<?php

return [
  [
        'pattern' => 'api/products',
        'action'  => function () {
            header( 'Transfer-Encoding: chunked; Content-type: text/html; charset=utf-8' );
            echo "<style>html {white-space:pre}</style>\r\n";
            emitFlush('5%: Initializing');


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

            emitFlush($initalPercent.'%: Initializing Complete');
            sleep(1);

            for($i = 0; $i < $total; $i++){
                $pageData = $array[$i];
                $productNum = $i + 1;
                $progress =  round($productNum/$weightedTotal * 100 + $initalPercent);

                try {
                    kirby()->page('products')->createChild([
                    'content'  => $pageData,
                    'slug' => $pageData['custom_url']['url'].uniqid(),
                    'isDraft'  => false,
                    'template' => 'article'
                    ]);
                }   catch(Exception $e) {

                  echo $e->getMessage();

                }                

                emitFlush($progress.'%: Saving Products');
                usleep(100000);
            }

            emitFlush('100%: Done');
            
            return;
            
        }
    ]
];

function emitFlush($message = null){
    if($message) {
        echo $message."\r\n";
    }

    flush();
    ob_flush();
};