<?php
include 'db.php';
include 'globals.php';
include 'functions.php';

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $params = SetUrlParam();
        $offset = ($params['page'] - 1) * $limit;
        $query = "SELECT s.name, s.original_price, s.discount_price, s.url, s.image, s.discount, b.brand_name, b.brand_url 
                FROM `discountsupp_supplement` s 
                JOIN `discountsupp_brand` b 
                WHERE s.brand_id = b.id AND s.date = '$date' AND s.active = 1
                ORDER BY {$params['orderby']} 
                LIMIT $limit OFFSET $offset";    
        $stmt = $db->prepare($query);
        $stmt->execute();
        $supplements = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $totalItems = GetTotalItems($db, '');
        $totalPages = ceil($totalItems / $limit);
        $data = FormatData($supplements);
        $result = [
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'items' => $data,
        ];
        header('Content-Type: application/json');
        http_response_code(200);
        print_r(json_encode($result));
    } catch(Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
