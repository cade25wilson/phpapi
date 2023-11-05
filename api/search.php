<?php
include 'db.php';
include 'globals.php';
include 'functions.php';

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    try{
        $params = SetUrlParam();
        $offset = ($params['page'] - 1) * $limit;
        $itemsQuery = "SELECT s.name, s.original_price, s.discount_price, s.url, s.image, s.discount, b.brand_name, b.brand_url 
                FROM `discountsupp_supplement` s 
                JOIN `discountsupp_brand` b 
                WHERE s.brand_id = b.id AND s.date = '$date' AND s.active = 1 AND s.name LIKE '%$params[search]%'
                ORDER BY {$params['orderby']} 
                LIMIT $limit OFFSET $offset";
        $itemsStmt = $db->prepare($itemsQuery);
        $itemsStmt->execute();
        $supplements = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
        $totalItems = GetTotalItems($db, "AND discountsupp_supplement.name LIKE '%$params[search]%'");
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
        exit();
    } catch(Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}