<?php

class Product {

    private $dbserver = "localhost";
    private $dbuser = "gregport_gallery";
    private $dbpass = "Gal123";
    private $dbdatabase = "gregport_gallery";
    private $msg = "";

    public function getMsg() {
        return $this->msg;
    }

    public function getCompaniesList() {
        $result = array();
        try {
            $mysqli = new mysqli($this->dbserver, $this->dbuser, $this->dbpass, $this->dbdatabase);
            if (mysqli_connect_errno()) {
                // printf("Connecting to the MySQL server is not possible. Error code: %s\n", mysqli_connect_error());
                $this->msg = $mysqli->error;
                return $result;
            }

            //echo "<br>\nConnected to server<br>";
            $query = "select com.CompanyId,com.CompanyName,com.CompanyAddress,con.contacsid, con.name,con.number, con.note From company com Right Join contacs con on con.companyId = com.CompanyId";
            if (!($stmt = $mysqli->prepare($query))) {
                $this->msg = $mysqli->error;
                $mysqli->close();
                //echo "<br>\nPrepare foiled <br>";
                return $result;
            }
            //echo "<br>\nPrepare successed <br>";
            if (!$stmt->execute()) {
                $mysqli->close();
                $this->msg = $stmt->error;
                //echo "<br>\nExecute foiled <br>";
                return $result;
            } else {
                //echo "<br>\nExecute successed <br>";
                $stmt->bind_result($companyid, $companyname, $companyaddress, $contacsid, $name, $number, $note);
                while ($stmt->fetch()) {
                    array_push($result, array("id" => $companyid, "companyname" => stripslashes($companyname), "address" => stripslashes($companyaddress), "contacsid" => $contacsid, "name" => stripslashes($name), "number" => $number, "note" => stripslashes($note)));
                }
            }

            //echo "<pre>";
            //print_r($result);
            //echo "</pre>";

            $stmt->close();
            $mysqli->close();
        } catch (Exception $e) {
            $this->msg = $e->getMessage();
        }
        return $result;
    }

    public function insertCompany($companyname, $address, $name, $number, $note) {
        //echo "<br>Name:$name   <br>Price:$price<br>";
        $result = -1;
        try {
            $mysqli = new mysqli($this->dbserver, $this->dbuser, $this->dbpass, $this->dbdatabase);
            if ($mysqli->connect_errno) {
                $this->msg = $mysqli->error;
                return $result;
            }
            $query = "insert into `company` (`CompanyName`,`CompanyAddress`) values(?,?)";
            if (!$stmt = $mysqli->prepare($query)) {
                $mysqli->close();
                $this->msg = $mysqli->error;
                return $result;
            }

            $stmt->bind_param('sd', $companyname, $address);
            if (!$stmt->execute()) {
                $mysqli->close();
                $this->msg = $stmt->error;
                return $result;
            } else {
                $idcomp = $mysqli->insert_id;
                //print_r();
                $query = "insert into `contacs` (`name`,`number`,`note`,`companyId`) values(?,?,?,?)";
                if (!$stmt2 = $mysqli->prepare($query)) {
                    $mysqli->close();
                    $this->msg = $mysqli->error;
                    return $result;
                }

                $stmt2->bind_param('sdsd', $name, $number, $note, $idcomp);
                if (!$stmt2->execute()) {
                    $mysqli->close();
                    $this->msg = $stmt2->error;
                    return $result;
                }
            }


            $result = 1;
            $this->msg = '';
            $stmt->close();
            $mysqli->close();
        } catch (Exception $e) {
            $this->msg = $e->getMessage();
        }
        return $result;
    }

    public function updateCompany($id, $companyname, $address, $name, $number, $note, $contacsid) {
        //echo "<br>ID:$idproduct<br>Name:$name   <br>Price:$price<br>";

        $result = -1;
        try {
            $mysqli = new mysqli($this->dbserver, $this->dbuser, $this->dbpass, $this->dbdatabase);
            if (mysqli_connect_errno()) {
                $this->msg = $mysqli->error;
                //printf("Connecting to the MySQL server is not possible. Error code: %s\n", mysqli_connect_error());
                return $result;
            }
            $query = "update `company` set `CompanyName` =?,`CompanyAddress` = ? where `CompanyId` = ?";
            if (!($stmt = $mysqli->prepare($query))) {
                //echo "<br>Prepare error<br>";
                //$this->msg = $mysqli->error;
                $mysqli->close();
                return $result;
            }
            //echo "<br>Prepare success<br>";
            $stmt->bind_param('ssd', $companyname, $address, $id);
            if (!$stmt->execute()) {
                //echo "<br>Execute error<br>";
                $this->msg = $stmt->error;
                $mysqli->close();
                return $result;
            } else {
                $query = "update `contacs` set `name` =?,`number` = ?,`note` = ? where `contacsid` = ?";
                if (!($stmt2 = $mysqli->prepare($query))) {
                    //echo "<br>Prepare error<br>";
                    //$this->msg = $mysqli->error;
                    $mysqli->close();
                    return $result;
                }
                //echo "<br>Prepare success<br>";, , , $contacsid
                $stmt2->bind_param('sdsd', $name, $number, $note, $contacsid);
                if (!$stmt2->execute()) {
                    //echo "<br>Execute error<br>";
                    $this->msg = $stmt2->error;
                    $mysqli->close();
                    return $result;
                }
            }
            //echo "<br>Execute success<br>";
            $result = 1;
            //$this->msg = "";
            $stmt->close();
            $mysqli->close();
        } catch (Exception $e) {
            //echo "dddddddddddddddddddddddddddddddddddddddddddddddddddddddd";
            $this->msg = $e->getMessage();
        }
        return $result;
    }

    public function deleteCompany($id) {
        $result = -1;
        try {
            $mysqli = new mysqli($this->dbserver, $this->dbuser, $this->dbpass, $this->dbdatabase);
            if ($mysqli->connect_errno) {
                $this->msg = $mysqli->error;
                return $result;
            }
            $query = "delete from company where CompanyId =?";
            if (!($stmt = $mysqli->prepare($query))) {
                $this->msg = $mysqli->error;
                $mysqli->close();
                return $result;
            }
            $stmt->bind_param('d', $id);
            if (!$stmt->execute()) {
                $this->msg = $stmt->error;
                $mysqli->close();
                return $result;
            } else {
                $query = "delete from contacs where companyId =?";
                if (!($stmt2 = $mysqli->prepare($query))) {
                    $this->msg = $mysqli->error;
                    $mysqli->close();
                    return $result;
                }
                $stmt2->bind_param('d', $id);
                if (!$stmt2->execute()) {
                    $this->msg = $stmt2->error;
                    $mysqli->close();
                    return $result;
                }
            }
            $result = 1;
            $this->msg = "";
            $stmt->close();
            $mysqli->close();
        } catch (Exception $e) {
            $this->msg = $e->getMessage();
        }
        return $result;
    }

}

?>