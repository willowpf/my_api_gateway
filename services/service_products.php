<?php
header('Content-Type: application/json');
echo json_encode([
    ['sku' => 'A123', 'productName' => 'Selpon'],
    ['sku' => 'B456', 'productName' => 'Bomber']
]);
