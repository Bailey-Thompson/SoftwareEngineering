<?php error_reporting (E_ALL ^ E_NOTICE);

class ProductGateway
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

    // public function getAll(): array
    // {
    //     $sql = "SELECT *
    //     FROM Driver
    //     JOIN Car ON Driver.ID = Car.ID
    //     JOIN Claims ON Driver.ID = Claims.ID";

    //     $stmt = $this->conn->query($sql);

    //     $data = [];

    //     while ($row  = $stmt->fetch(PDO::FETCH_ASSOC)) {

    //         $data[] = $row;
    //     }

    //     return $data;
    // }

    public function getAllCities(): array
    {
        $sql = "SELECT *
        FROM City";

        $stmt = $this->conn->query($sql);

        $data = [];

        while ($row  = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $data[] = $row;
        }

        return $data;
    }

    public function createAirport(array $data): string
    {
        $sql = "INSERT INTO City (AIRCODE, TIME_ZONE)
                VALUES (:AIRCODE, :TIME_ZONE)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":AIRCODE", $data["aircode"] ?? "", PDO::PARAM_STR);
        $stmt->bindValue(":TIME_ZONE", $data["time_zone"] ?? "", PDO::PARAM_STR);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function updateAirport(array $current, array $new, string $id): int
    {
        $sql = "UPDATE City
                SET TIME_ZONE = :TIME_ZONE
                WHERE AIRCODE =:AIRCODE";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":TIME_ZONE", !empty($new["time_zone"]) ? $new["time_zone"] : $current["TIME_ZONE"], PDO::PARAM_STR);

        $stmt->bindValue(":AIRCODE", $id, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function getAirport(string $id): ?array{
        $sql = "SELECT *
        FROM City
        WHERE City.aircode = :aircode";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":aircode", $id, PDO::PARAM_STR);

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data === false) {
            return null;
        }

        return $data;
    }

    public function deleteAirport(string $id): int
    {
        $sql = "DELETE FROM City WHERE AIRCODE = :AIRCODE";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":AIRCODE", $id, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function getAllAirplanes(): array
    {
        $sql = "SELECT *
        FROM Airplane";

        $stmt = $this->conn->query($sql);

        $data = [];

        while ($row  = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $data[] = $row;
        }

        return $data;
    }

    public function createAirplane(array $data): string
    {
        $sql = "INSERT INTO Airplane (NUMSER, MANUFACTURER, MODEL, TOTAL_SEATS)
                VALUES (:NUMSER, :MANUFACTURER, :MODEL, :TOTAL_SEATS)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":NUMSER", (int) $data["numser"], PDO::PARAM_INT);
        $stmt->bindValue(":MANUFACTURER", $data["manufacturer"] ?? "", PDO::PARAM_STR);
        $stmt->bindValue(":MODEL", $data["model"] ?? "", PDO::PARAM_STR);
        $stmt->bindValue(":TOTAL_SEATS", (int) ($data["total_seats"] ?? 0), PDO::PARAM_INT);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function updateAirplane(array $current, array $new, string $id): int
    {
        $sql = "UPDATE Airplane
                SET MANUFACTURER = :MANUFACTURER, MODEL = :MODEL, TOTAL_SEATS = :TOTAL_SEATS
                WHERE NUMSER =:NUMSER";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":MANUFACTURER", !empty($new["manufacturer"]) ? $new["manufacturer"] : $current["MANUFACTURER"], PDO::PARAM_STR);
        $stmt->bindValue(":MODEL", !empty($new["model"]) ? $new["model"] : $current["MODEL"], PDO::PARAM_STR);
        $stmt->bindValue(":TOTAL_SEATS", !empty($new["total_seats"]) ? (int)$new["total_seats"] : $current["TOTAL_SEATS"], PDO::PARAM_INT);

        $stmt->bindValue(":NUMSER", (int) $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function getAirplane(string $id): ?array{
        $sql = "SELECT *
        FROM Airplane
        WHERE Airplane.numser = :numser";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":numser", $id, PDO::PARAM_INT);

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data === false) {
            return null;
        }

        return $data;
    }

    public function deleteAirplane(string $id): int
    {
        $sql = "DELETE FROM Airplane WHERE NUMSER = :NUMSER";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":NUMSER", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }
}