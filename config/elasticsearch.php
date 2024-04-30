<?php

 // config/elasticsearch.php

return [
    'hosts' => [
        [
            'host' => env('ELASTICSEARCH_HOST', 'localhost'),
            'port' => env('ELASTICSEARCH_PORT', 9200),
            'scheme' => env('ELASTICSEARCH_SCHEME', 'http'),
            // Add more configuration options as needed
        ],
    ],
    'retries' => env('ELASTICSEARCH_RETRIES', 3), // Number of retries for Elasticsearch requests
];

