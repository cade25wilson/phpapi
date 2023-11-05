<?php

function SetUrlParam(){
    try {
        $page = isset($_GET['page']);
        $page = filter_var($page, FILTER_VALIDATE_INT) ? $page : 1;
        $orderby = isset($_GET['orderby']) ? htmlspecialchars($_GET['orderby']) : '-discount';
        $brand = isset($_GET['brand']) ? htmlspecialchars($_GET['brand']) : null;
        $search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : null;
        return array(
            'page' => $page,
            'orderby' => $orderby,
            'brand' => strtolower($brand),
            'search' => $search
        );
    } catch(Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

function FormatData($supplements){
    try{
        $data = [];
        foreach($supplements as $supplement){
            $data[] = [
                'supplement' => [
                    'name' => $supplement['name'],
                    'originalPrice' => $supplement['original_price'],
                    'discountPrice' => $supplement['discount_price'],
                    'url' => $supplement['url'],
                    'image' => $supplement['image'],
                    'discount' => $supplement['discount'],
                ],
                'brand' => [
                    'brandName' => $supplement['brand_name'],
                    'brandUrl' => $supplement['brand_url'],
                ]
            ];
        }
        return $data;
    } catch(Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

function GetTotalItems($db, $params){
    try {
        $query = "SELECT COUNT(*) FROM `discountsupp_supplement` WHERE discountsupp_supplement.active = 1 AND discountsupp_supplement.date = '2023-10-22' $params";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $totalItems = $stmt->fetchColumn();
        return $totalItems;
    } catch(Exception $e) {
        echo "Error: " . $e->getMessage();
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>