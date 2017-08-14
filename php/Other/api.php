<?php
require_once './Product.php';
$data = file_get_contents('php://input');
$json = json_decode($data);
$op = $json->{'op'};
if (isset($op)) {
    switch ($op) {
        case "getcompanieslist":
            $obj = new Product();
            $ret = $obj->getCompaniesList();
            //print_r($ret);
            $count = count($ret, 1);
            $msg = $obj->getMsg();
            if (!empty($msg)) {
                $resp = array('code' => -1, 'msg' => $msg);
            } else {
                $resp = array('code' => 1, 'msg' => '', 'data' => $ret);
            }
            header('Content-type:application/json');
            header('Access-Control-Allow-Origin:*');
            header('Access-Control-Allow-Methods: GET,POST');
            echo json_encode($resp);
            break;
        case 'save';
            //print_r($json->{'data'});
            $obj = new Product();
            $id = $json->{'data'}->{'id'};
            $companyname = addslashes($json->{'data'}->{'companyname'});
            $address = addslashes($json->{'data'}->{'address'});
            $name = addslashes($json->{'data'}->{'name'});
            $number = addslashes($json->{'data'}->{'number'});
            $note = addslashes($json->{'data'}->{'note'});
            $contacsid = addslashes($json->{'data'}->{'contacsid'});

            $code = -1;
            //echo "<br>IDpr 1:$idproduct<br>Name:$name   <br>Price:$price<br>";
            if (empty($id) || $id == '' || $id == 0) {
                //insert new product
                $code = $obj->insertCompany($companyname, $address, $name, $number, $note);
            } else {
                //update product
                //echo "<br>IDprod2:$idproduct<br>Name:$name   <br>Price:$price<br>";
                $code = $obj->updateCompany($id, $companyname, $address, $name, $number, $note, $contacsid);
            }
            $resp = array('code' => $code, 'msg' => $obj->getMsg());
            header('Content-type: application/json');
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET,POST');
            echo json_encode($resp);
            break;
        case 'delete':
            $id = $json->{'id'};
            $obj = new Product();
            $code = $obj->deleteCompany($id);
            $resp = array('code' => $code, 'msg' => $obj->getMsg());
            header('Content-type: application/json');
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET,POST');
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
    header('Content-type:application/json');
    header('Access-Control-Allow-Origin: *');
    echo json_encode($resp);
}
?>

