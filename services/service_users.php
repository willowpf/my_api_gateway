<?php
header('Content-Type: application/json');
echo json_encode([
    ['id' => 1, 'name' => 'franz'],
    ['id' => 2, 'name' => 'Bob']
]);
