<?php error_reporting (E_ALL ^ E_NOTICE);

class ProductGateway
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getConnection();
    }

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
                WHERE NUMSER = :NUMSER";
    
        $stmt = $this->conn->prepare($sql);
    
        $manufacturer = isset($new["manufacturer"]) && !empty($new["manufacturer"]) ? $new["manufacturer"] : $current["Manufacturer"];
        $model = isset($new["model"]) && !empty($new["model"]) ? $new["model"] : $current["Model"];
        $totalSeats = isset($new["total_seats"]) && !empty($new["total_seats"]) ? (int)$new["total_seats"] : $current["Total_Seats"];
    
        $stmt->bindValue(":MANUFACTURER", $manufacturer, PDO::PARAM_STR);
        $stmt->bindValue(":MODEL", $model, PDO::PARAM_STR);
        $stmt->bindValue(":TOTAL_SEATS", $totalSeats, PDO::PARAM_INT);
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

    public function getAllFlights(): array
    {
        $sql = "SELECT *
        FROM Flight";

        $stmt = $this->conn->query($sql);

        $data = [];

        while ($row  = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $data[] = $row;
        }

        return $data;
    }

    public function createFlight(array $data): string
    {
        function formatDate($date) {
            $d = DateTime::createFromFormat('Y-m-d', $date);
            return $d ? $d->format('Y-m-d') : null;
        }
        function formatTime($time) {
            $t = DateTime::createFromFormat('H:i', $time);
            return $t ? $t->format('H:i:s') : null;
        }
    
        $dept_date = isset($data["dept_date"]) && !empty($data["dept_date"]) ? formatDate($data["dept_date"]) : null;
        $arr_date = isset($data["arr_date"]) && !empty($data["arr_time"]) ? formatDate($data["arr_date"]) : null;
        $dept_time = isset($data["dept_time"]) && !empty($data["dept_time"]) ? formatTime($data["dept_time"]) : null;
        $arr_time = isset($data["arr_time"]) && !empty($data["arr_time"]) ? formatTime($data["arr_time"]) : null;
    
        $sql = "INSERT INTO Flight (FLIGHTNUM, ORIGIN_AIRCODE, DESTINATION_AIRCODE, DEPT_TIME, DEPT_DATE, ARR_TIME, ARR_DATE, NUMSER)
                VALUES (:FLIGHTNUM, :ORIGIN_AIRCODE, :DESTINATION_AIRCODE, :DEPT_TIME, :DEPT_DATE, :ARR_TIME, :ARR_DATE, :NUMSER)";
    
        $stmt = $this->conn->prepare($sql);
    
        $stmt->bindValue(":FLIGHTNUM", (int) $data["flightnum"], PDO::PARAM_INT);
        $stmt->bindValue(":ORIGIN_AIRCODE", $data["origin_aircode"] ?? "", PDO::PARAM_STR);
        $stmt->bindValue(":DESTINATION_AIRCODE", $data["destination_aircode"] ?? "", PDO::PARAM_STR);
        $stmt->bindValue(":DEPT_TIME", $dept_time, PDO::PARAM_STR);
        $stmt->bindValue(":DEPT_DATE", $dept_date, PDO::PARAM_STR);
        $stmt->bindValue(":ARR_TIME", $arr_time, PDO::PARAM_STR);
        $stmt->bindValue(":ARR_DATE", $arr_date, PDO::PARAM_STR);
        $stmt->bindValue(":NUMSER", (int) ($data["numser"] ?? 0), PDO::PARAM_INT);
    
        $stmt->execute();
    
        return $this->conn->lastInsertId();
    }

    public function updateFlight(array $current, array $new, string $id): int
    {
        $sql = "UPDATE Flight
                SET ORIGIN_AIRCODE = :ORIGIN_AIRCODE, DESTINATION_AIRCODE = :DESTINATION_AIRCODE, DEPT_TIME = :DEPT_TIME, DEPT_DATE = :DEPT_DATE, ARR_TIME = :ARR_TIME, ARR_DATE = :ARR_DATE, NUMSER = :NUMSER
                WHERE FLIGHTNUM =:FLIGHTNUM";

        $stmt = $this->conn->prepare($sql);

        $origin = isset($new["origin_aircode"]) && !empty($new["origin_aircode"]) ? $new["origin_aircode"] : $current["Origin_Aircode"];
        $destination = isset($new["destination_aircode"]) && !empty($new["destination_aircode"]) ? $new["destination_aircode"] : $current["Destination_Aircode"];
        $depttime = isset($new["dept_time"]) && !empty($new["dept_time"]) ? $new["dept_time"] : $current["Dept_Time"];
        $deptdate= isset($new["dept_date"]) && !empty($new["dept_date"]) ? $new["dept_date"] : $current["Dept_Date"];
        $arrtime = isset($new["arr_time"]) && !empty($new["arr_time"]) ? $new["arr_time"] : $current["Arr_Time"];
        $arrdate = isset($new["arr_date"]) && !empty($new["arr_date"]) ? $new["arr_date"] : $current["Arr_Date"];
        $numser = isset($new["numser"]) && !empty($new["numser"]) ? $new["numser"] : $current["NUMSER"];

        $stmt->bindValue(":ORIGIN_AIRCODE", $origin, PDO::PARAM_STR);
        $stmt->bindValue(":DESTINATION_AIRCODE", $destination, PDO::PARAM_STR);
        $stmt->bindValue(":DEPT_TIME", $depttime, PDO::PARAM_STR);
        $stmt->bindValue(":DEPT_DATE", $deptdate, PDO::PARAM_STR);
        $stmt->bindValue(":ARR_TIME", $arrtime, PDO::PARAM_STR);
        $stmt->bindValue(":ARR_DATE", $arrdate, PDO::PARAM_STR);
        $stmt->bindValue(":NUMSER", $numser, PDO::PARAM_INT);

        $stmt->bindValue(":FLIGHTNUM", (int) $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function getFlight(string $id): ?array{
        $sql = "SELECT *
        FROM Flight
        WHERE Flight.flightnum = :flightnum";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":flightnum", $id, PDO::PARAM_INT);

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data === false) {
            return null;
        }

        return $data;
    }

    public function deleteFlight(string $id): int
    {
        $sql = "DELETE FROM Flight WHERE FLIGHTNUM = :FLIGHTNUM";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":FLIGHTNUM", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function createStop(array $data): string
    {
        function formatDate($date) {
            $d = DateTime::createFromFormat('Y-m-d', $date);
            return $d ? $d->format('Y-m-d') : null;
        }
        function formatTime($time) {
            $t = DateTime::createFromFormat('H:i', $time);
            return $t ? $t->format('H:i:s') : null;
        }
    
        $dept_date = isset($data["dept_date"]) && !empty($data["dept_date"]) ? formatDate($data["dept_date"]) : null;
        $arr_date = isset($data["arr_date"]) && !empty($data["arr_time"]) ? formatDate($data["arr_date"]) : null;
        $dept_time = isset($data["dept_time"]) && !empty($data["dept_time"]) ? formatTime($data["dept_time"]) : null;
        $arr_time = isset($data["arr_time"]) && !empty($data["arr_time"]) ? formatTime($data["arr_time"]) : null;
    
        $sql = "INSERT INTO Flight_Stops (FLIGHTNUM, AIRCODE, STOP_ORDER, DEPT_TIME, DEPT_DATE, ARR_TIME, ARR_DATE)
                VALUES (:FLIGHTNUM, :AIRCODE, :STOP_ORDER, :DEPT_TIME, :DEPT_DATE, :ARR_TIME, :ARR_DATE)";
    
        $stmt = $this->conn->prepare($sql);
    
        $stmt->bindValue(":FLIGHTNUM", (int) $data["flightnum"], PDO::PARAM_INT);
        $stmt->bindValue(":AIRCODE", $data["aircode"] ?? "", PDO::PARAM_STR);
        $stmt->bindValue(":STOP_ORDER", (int) $data["stop_order"], PDO::PARAM_INT);
        $stmt->bindValue(":DEPT_TIME", $dept_time, PDO::PARAM_STR);
        $stmt->bindValue(":DEPT_DATE", $dept_date, PDO::PARAM_STR);
        $stmt->bindValue(":ARR_TIME", $arr_time, PDO::PARAM_STR);
        $stmt->bindValue(":ARR_DATE", $arr_date, PDO::PARAM_STR);
    
        $stmt->execute();
    
        return $this->conn->lastInsertId();
    }

    public function getStop(string $id): ?array{
        $sql = "SELECT *
        FROM Flight_Stops
        WHERE Flight_Stops.flightnum = :flightnum";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":flightnum", $id, PDO::PARAM_INT);

        $stmt->execute();

        
        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $data[] = $row;
        }

        return $data;
    }

    public function createPassenger(array $data): string
    {
        $sql = "INSERT INTO Passenger (PASSNUM, SURNAME, FORENAME, ADDRESS, PHONE)
                VALUES (:PASSNUM, :SURNAME, :FORENAME, :ADDRESS, :PHONE)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":PASSNUM", $data["passnum"], PDO::PARAM_INT);
        $stmt->bindValue(":SURNAME", $data["surname"] ?? "", PDO::PARAM_STR);
        $stmt->bindValue(":FORENAME", $data["forename"] ?? "", PDO::PARAM_STR);
        $stmt->bindValue(":ADDRESS", $data["address"] ?? "", PDO::PARAM_STR);
        $stmt->bindValue(":PHONE", $data["phone"] ?? "", PDO::PARAM_STR);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function updatePassenger(array $current, array $new, string $id): int
    {
        $sql = "UPDATE Passenger
                SET SURNAME = :SURNAME, FORENAME = :FORENAME, ADDRESS = :ADDRESS, PHONE = :PHONE
                WHERE PASSNUM = :PASSNUM";
    
        $stmt = $this->conn->prepare($sql);
    
        $surname = isset($new["surname"]) && !empty($new["surname"]) ? $new["surname"] : $current["Surname"];
        $forename = isset($new["forename"]) && !empty($new["forename"]) ? $new["forename"] : $current["Forename"];
        $address = isset($new["address"]) && !empty($new["address"]) ? $new["address"] : $current["Address"];
        $phone = isset($new["phone"]) && !empty($new["phone"]) ? $new["phone"] : $current["Phone"];
    
        $stmt->bindValue(":SURNAME", $surname, PDO::PARAM_STR);
        $stmt->bindValue(":FORENAME", $forename, PDO::PARAM_STR);
        $stmt->bindValue(":ADDRESS", $address, PDO::PARAM_STR);
        $stmt->bindValue(":PHONE", $phone, PDO::PARAM_STR);
        $stmt->bindValue(":PASSNUM", (int) $id, PDO::PARAM_INT);
    
        $stmt->execute();
    
        return $stmt->rowCount();
    }

    public function getPassenger(string $id): ?array{
        $sql = "SELECT *
        FROM Passenger
        WHERE Passenger.passnum = :passnum";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":passnum", $id, PDO::PARAM_INT);

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data === false) {
            return null;
        }

        return $data;
    }

    public function deletePassenger(string $id): int
    {
        $sql = "DELETE FROM Passenger WHERE PASSNUM = :PASSNUM";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":PASSNUM", $id, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function createBooking(array $data): string
    {
        $sql = "INSERT INTO Flight_Information (PASSNUM, FLIGHTNUM)
                VALUES (:PASSNUM, :FLIGHTNUM)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":PASSNUM", (int) $data["passnum"], PDO::PARAM_INT);
        $stmt->bindValue(":FLIGHTNUM", (int) ($data["flightnum"] ?? 0), PDO::PARAM_INT);

        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function getBooking(string $id): ?array{
        $sql = "SELECT *
        FROM Flight_Information
        WHERE Flight_Information.passnum = :passnum";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(":passnum", $id, PDO::PARAM_INT);

        $stmt->execute();

        
        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $data[] = $row;
        }

        return $data;
    }
}