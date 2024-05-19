<?php error_reporting (E_ALL ^ E_NOTICE);

class ProductGateway
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    public function getAll(): array
    {
        $sql = "SELECT *
        FROM Driver
        JOIN Car ON Driver.ID = Car.ID
        JOIN Claims ON Driver.ID = Claims.ID";

        $stmt = $this->conn->query($sql);

        $data = [];

        while ($row  = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $data[] = $row;
        }

        return $data;
    }
    
    public function create(array $data): string
    {
        

        $sql = "INSERT INTO Driver (ID, KIDSDRIV, AGE, INCOME, MSTATUS, GENDER, EDUCATION, OCCUPATION)
                VALUES (:ID, :KIDSDRIV, :AGE, :INCOME, :MSTATUS, :GENDER, :EDUCATION, :OCCUPATION)";

        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":ID", (int) $data["id"], PDO::PARAM_INT);
        $stmt->bindValue(":KIDSDRIV", (int) ($data["kidsdriv"] ?? 0), PDO::PARAM_INT);
        $stmt->bindValue(":AGE", (int) ($data["age"] ?? 0), PDO::PARAM_INT);
        $stmt->bindValue(":INCOME", isset($data["income"]) ? "$".$data["income"] : "", PDO::PARAM_STR);
        $stmt->bindValue(":MSTATUS", $data["mstatus"] ?? "", PDO::PARAM_STR);
        $stmt->bindValue(":GENDER", $data["gender"] ?? "", PDO::PARAM_STR);
        $stmt->bindValue(":EDUCATION", $data["education"] ?? "", PDO::PARAM_STR);
        $stmt->bindValue(":OCCUPATION", $data["occupation"] ?? "", PDO::PARAM_STR);

        $stmt->execute();

        $sql = "INSERT INTO Car (ID, CAR_TYPE, RED_CAR, CAR_AGE)
                VALUES (:ID, :CAR_TYPE, :RED_CAR, :CAR_AGE)";

        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":ID", (int) $data["id"], PDO::PARAM_INT);
        $stmt->bindValue(":CAR_TYPE", $data["car_type"] ?? "", PDO::PARAM_STR);
        $stmt->bindValue(":RED_CAR", $data["red_car"] ?? "", PDO::PARAM_STR);
        $stmt->bindValue(":CAR_AGE", (int) ($data["car_age"] ?? 0), PDO::PARAM_INT);

        $stmt->execute();

        $sql = "INSERT INTO Claims (ID, CLAIM_FLAG, CLM_AMT, CLM_FREQ, OLDCLAIM)
                VALUES (:ID, :CLAIM_FLAG, :CLM_AMT, :CLM_FREQ, :OLDCLAIM)";

        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(":ID", (int) $data["id"], PDO::PARAM_INT);
        $stmt->bindValue(":CLAIM_FLAG", (int) ($data["claim_flag"] ?? 0), PDO::PARAM_INT);
        $clm_amt = isset($data["clm_amt"]) ? $data["clm_amt"] : "";
        $stmt->bindValue(":CLM_AMT", "$".$clm_amt, PDO::PARAM_STR);
        $stmt->bindValue(":CLM_FREQ", (int) ($data["clm_freq"] ?? 0), PDO::PARAM_INT);
        $oldclaim = isset($data["oldclaim"]) ? $data["oldclaim"] : "";
        $stmt->bindValue(":OLDCLAIM", "$".$oldclaim, PDO::PARAM_STR);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function get(string $id): ?array
    {
        $sql = "SELECT *
        FROM Driver
        JOIN Car ON Driver.ID = Car.ID
        JOIN Claims ON Driver.ID = Claims.ID
        WHERE Driver.id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data === false) {
            return null;
        }

        return $data;
    }

    public function update(array $current, array $new, int $id): int
    {
        $sql = "UPDATE Driver
                SET KIDSDRIV = :KIDSDRIV, AGE = :AGE, INCOME = :INCOME, MSTATUS = :MSTATUS, GENDER = :GENDER, EDUCATION = :EDUCATION, OCCUPATION = :OCCUPATION
                WHERE ID = :ID";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":KIDSDRIV", !empty($new["kidsdriv"]) ? (int)$new["kidsdriv"] : $current["KIDSDRIV"], PDO::PARAM_INT);
        $stmt->bindValue(":AGE", !empty($new["age"]) ? (int)$new["age"] : $current["AGE"], PDO::PARAM_INT);
        $stmt->bindValue(":INCOME", !empty($new["income"]) ? $new["income"] : $current["INCOME"], PDO::PARAM_STR);
        $stmt->bindValue(":MSTATUS", !empty($new["mstatus"]) ? $new["mstatus"] : $current["MSTATUS"], PDO::PARAM_STR);
        $stmt->bindValue(":GENDER", !empty($new["gender"]) ? $new["gender"] : $current["GENDER"], PDO::PARAM_STR);
        $stmt->bindValue(":EDUCATION", !empty($new["education"]) ? $new["education"] : $current["EDUCATION"], PDO::PARAM_STR);
        $stmt->bindValue(":OCCUPATION", !empty($new["occupation"]) ? $new["occupation"] : $current["OCCUPATION"], PDO::PARAM_STR);

        $stmt->bindValue(":ID", (int) $id, PDO::PARAM_INT);

        $stmt->execute();

        $sql = "UPDATE Car
                SET CAR_TYPE = :CAR_TYPE, RED_CAR = :RED_CAR, CAR_AGE = :CAR_AGE
                WHERE ID = :ID";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":CAR_TYPE", !empty($new["car_type"]) ? $new["car_type"] : $current["CAR_TYPE"], PDO::PARAM_STR);
        $stmt->bindValue(":RED_CAR", !empty($new["red_car"]) ? $new["red_car"] : $current["RED_CAR"], PDO::PARAM_STR);
        $stmt->bindValue(":CAR_AGE", !empty($new["car_age"]) ? (int)$new["car_age"] : $current["CAR_AGE"], PDO::PARAM_INT);

        $stmt->bindValue(":ID", (int) $id, PDO::PARAM_INT);

        $stmt->execute();

        $sql = "UPDATE Claims
                SET CLAIM_FLAG = :CLAIM_FLAG, CLM_AMT = :CLM_AMT, CLM_FREQ = :CLM_FREQ, OLDCLAIM = :OLDCLAIM
                WHERE ID = :ID";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":CLAIM_FLAG", !empty($new["claim_flag"]) ? (int)$new["claim_flag"] : $current["CLAIM_FLAG"], PDO::PARAM_INT);
        $stmt->bindValue(":CLM_AMT", !empty($new["clm_amt"]) ? $new["clm_amt"] : $current["CLM_AMT"], PDO::PARAM_STR);
        $stmt->bindValue(":CLM_FREQ", !empty($new["clm_freq"]) ? (int)$new["clm_freq"] : $current["CLM_FREQ"], PDO::PARAM_INT);
        $stmt->bindValue(":OLDCLAIM", !empty($new["oldclaim"]) ? $new["oldclaim"] : $current["OLDCLAIM"], PDO::PARAM_STR);

        $stmt->bindValue(":ID", (int) $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete(string $id): int
    {
        $sqlDriver = "DELETE FROM Driver WHERE ID = :ID";

        $stmtDriver = $this->conn->prepare($sqlDriver);

        $stmtDriver->bindValue(":ID", $id, PDO::PARAM_INT);

        $stmtDriver->execute();

        $sqlCar = "DELETE FROM Car WHERE ID = :ID";

        $stmtCar = $this->conn->prepare($sqlCar);

        $stmtCar->bindValue(":ID", $id, PDO::PARAM_INT);

        $stmtCar->execute();

        $sqlClaims = "DELETE FROM Claims WHERE ID = :ID";

        $stmtClaims = $this->conn->prepare($sqlClaims);

        $stmtClaims->bindValue(":ID", $id, PDO::PARAM_INT);
        
        $stmtClaims->execute();

        return $stmtDriver->rowCount() + $stmtCar->rowCount() + $stmtClaims->rowCount();
    }
}