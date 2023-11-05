<?php
include 'db.php';
include 'globals.php';
include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    try {
        $params = SetUrlParam();
        $offset = ($params['page'] - 1) * $limit;
        $brandQuery = "SELECT id from `discountsupp_brand` WHERE brand_url LIKE '$params[brand]'";
        $brandStmt = $db->prepare($brandQuery);
        $brandStmt->execute();
        $brandId = $brandStmt->fetchColumn();

        $itemsQuery = "SELECT s.name, s.original_price, s.discount_price, s.url, s.image, s.discount, b.brand_name, b.brand_url 
                FROM `discountsupp_supplement` s 
                JOIN `discountsupp_brand` b 
                WHERE s.brand_id = b.id AND s.date = '$date' AND s.active = 1 AND s.brand_id = $brandId
                ORDER BY {$params['orderby']} 
                LIMIT $limit OFFSET $offset";

        $itemsStmt = $db->prepare($itemsQuery);
        $itemsStmt->execute();
        $supplements = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
        $totalItems = GetTotalItems($db, "AND discountsupp_supplement.brand_id = $brandId");
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
?>