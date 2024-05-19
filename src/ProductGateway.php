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
}