<?php
class Database
{
  // ADD THIS LINE BELOW to declare the property
  public $conn; 

  private $db_name = "hotel_db";
  private $username = "user";
  private $password = "0968457514Bruno";
  private $socket_dir = "/cloudsql/project-2914a03a-8cff-4779-86f:asia-southeast1:hotel-db-restored";

  public function getConnection()
  {
    // Now that it is declared above, you can safely use it here
    $this->conn = null; 
    try {
      if (getenv('K_SERVICE')) {
        $dsn = "mysql:unix_socket=" . $this->socket_dir . ";dbname=" . $this->db_name . ";charset=utf8mb4";
      } else {
        $dsn = "mysql:host=localhost;port=3306;dbname=" . $this->db_name . ";charset=utf8mb4";
      }

      $this->conn = new PDO($dsn, $this->username, $this->password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    } catch (PDOException $e) {
      http_response_code(500);
      echo json_encode(["ok" => false, "error" => "DB connect: " . $e->getMessage()]);
      exit;
    }
    return $this->conn;
  }
}
