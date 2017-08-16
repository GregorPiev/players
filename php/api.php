<?php header('Access-Control-Allow-Origin: *'); ?>
<?php header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); ?>
<?php header("Access-Control-Allow-Headers: *"); ?>
<?php

require_once './Database.php';
$obj = Database::getConnection();
$data = file_get_contents('php://input');
$json = json_decode($data);

$op = $json->{'op'};
if (isset($op)) {
    switch ($op) {
        case "firstinsert":
            $obj->delete('plyers', '');
            foreach ($json->{'data'} as $key => $val) {
                $playerId = addslashes($val->playerId);
                $day = addslashes($val->day);
                $market = addslashes($val->market);
                $affiliateId = addslashes($val->affiliateId);

                $campaign = addslashes($val->campaign);
                $acquisitionNumber = addslashes($val->acquisitionNumber);
                $brand = addslashes($val->brand);
                $device = addslashes($val->device);

                $currency = addslashes($val->currency);
                $firstPlayed = addslashes($val->firstPlayed);
                $lastPlayed = addslashes($val->lastPlayed);
                $numberOfLifetimeDeposits = addslashes($val->numberOfLifetimeDeposits);

                $lifetimeDeposits = addslashes($val->lifetimeDeposits);
                $firstDepositAmount = addslashes($val->firstDepositAmount);
                $isPlayerLocked = addslashes($val->isPlayerLocked);
                $fraudLocked = addslashes($val->fraudLocked);

                $negativeBrand = addslashes($val->negativeBrand);
                $highRollerAdjusted = addslashes($val->highRollerAdjusted);
                $netRevenue = addslashes($val->netRevenue);
                $earnings = addslashes($val->earnings);
                $media = addslashes($val->media);
                $withdrawals = addslashes($val->withdrawals);
                $code = $obj->insertData(['playerId', 'day', 'market', 'affiliateId', 'campaign', 'acquisitionNumber', 'brand', 'device', 'currency', 'firstPlayed', 'lastPlayed', 'numberOfLifetimeDeposits', 'lifetimeDeposits', 'firstDepositAmount', 'isPlayerLocked', 'fraudLocked', 'negativeBrand', 'highRollerAdjusted', 'netRevenue', 'earnings', 'media', 'withdrawals'], [$playerId, $day, $market, $affiliateId, $campaign, $acquisitionNumber, $brand, $device, $currency, $firstPlayed, $lastPlayed, $numberOfLifetimeDeposits, $lifetimeDeposits, $firstDepositAmount, $isPlayerLocked, $fraudLocked, $negativeBrand, $highRollerAdjusted, $netRevenue, $earnings, $media, $withdrawals], 'plyers', null);
            }
            $resp = array('code' => $code, 'msg' => $obj->getMsg());
            header('Content-type: application/json');
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET,POST,DELETE,OPTION');
            header('Access-Control-Allow-Headers: *');
            echo json_encode($resp);
            break;
        case 'initbrand';
            $table = 'plyers';
            $result = $obj->selectListDistinct(['brand'], $table, "");
            $resp = array('data' => $result, 'msg' => $obj->getMsg());
            header('Content-type: application/json');
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET,POST,DELETE,OPTION');
            header('Access-Control-Allow-Headers: *');
            echo json_encode($resp);
            break;
        case 'newbrand';
            $table = 'plyers';
            $brand = $json->{'data'};
            $result = $obj->selectList(['playerId', 'day', 'market', 'affiliateId', 'campaign', 'acquisitionNumber', 'brand', 'device', 'currency', 'firstPlayed', 'lastPlayed', 'numberOfLifetimeDeposits', 'lifetimeDeposits', 'firstDepositAmount', 'isPlayerLocked', 'fraudLocked', 'negativeBrand', 'highRollerAdjusted', 'netRevenue', 'earnings', 'media', 'withdrawals'], $table, "WHERE `brand`like '{$brand}'");
            $resp = array('data' => $result, 'msg' => $obj->getMsg());
            header('Content-type: application/json');
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET,POST,DELETE,OPTION');
            header('Access-Control-Allow-Headers: *');
            echo json_encode($resp);
            break;
        case 'newplayer';
            $table = 'plyers';
            $playerId = $json->{'data'};
            $result = $obj->selectList(['playerId', 'day', 'market', 'affiliateId', 'campaign', 'acquisitionNumber', 'brand', 'device', 'currency', 'firstPlayed', 'lastPlayed', 'numberOfLifetimeDeposits', 'lifetimeDeposits', 'firstDepositAmount', 'isPlayerLocked', 'fraudLocked', 'negativeBrand', 'highRollerAdjusted', 'netRevenue', 'earnings', 'media', 'withdrawals'], $table, "WHERE `playerId`= {$playerId}");
            $resp = array('data' => $result, 'msg' => $obj->getMsg());
            header('Content-type: application/json');
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET,POST,DELETE,OPTION');
            header('Access-Control-Allow-Headers: *');
            echo json_encode($resp);
            break;
        case 'showall';
            $table = 'plyers';
            $result = $obj->selectList(['playerId', 'day', 'market', 'affiliateId', 'campaign', 'acquisitionNumber', 'brand', 'device', 'currency', 'firstPlayed', 'lastPlayed', 'numberOfLifetimeDeposits', 'lifetimeDeposits', 'firstDepositAmount', 'isPlayerLocked', 'fraudLocked', 'negativeBrand', 'highRollerAdjusted', 'netRevenue', 'earnings', 'media', 'withdrawals'], $table, null);
            $resp = array('data' => $result, 'msg' => $obj->getMsg());
            header('Content-type: application/json');
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET,POST,DELETE,OPTION');
            header('Access-Control-Allow-Headers: *');
            echo json_encode($resp);
            break;
        case 'delete':
            $idpage = $json->{'data'}->{'id'};
            $idname = $json->{'data'}->{'fieldname'};
            $table = $json->{'data'}->{'table'};
            $query_conditional = "Where $idname = $idpage";
            $resp = $obj->deleteWithPictures($table, ['picture'], $query_conditional);
            header('Content-type: application/json');
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET,POST,DELETE,OPTION');
            header('Access-Control-Allow-Headers: *');
            echo json_encode($resp);
            break;
        default:
            $ret = -999;
            $resp = array('code' => $ret, 'msg' => 'invalid operation');
            echo json_encode($resp);
            break;
    }
} else {
    $ret = -999;
    $resp = array('code' => $ret, 'msg' => 'invalid operation');
    //header('Content-type:application/json');
    //header('Access-Control-Allow-Origin: *');
    echo json_encode($resp);
}