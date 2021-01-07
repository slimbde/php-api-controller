<?php
///////////////// SINGLETON

class DbHandler {
  private $SQLhost = '127.0.0.1';
  //private $SQLhost = 'fdb19.awardspace.net';
  private $SQLuser = '2650602_db';
  private $SQLpass = '2213qweasdzxc';
  private $SQLdbase = '2650602_db';
  private $SQLport = '3306';
  private static $instance = null;

  private $pdo;

  private function __construct() {
    $dsn = "mysql:dbname=$this->SQLdbase;host=$this->SQLhost;port=$this->SQLport";

    try {
      $this->pdo = new PDO(
        $dsn,
        $this->SQLuser,
        $this->SQLpass
      );
      $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);              // to make `prepare` actually work
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // to disable doubled result fields
      $this->pdo->exec("SET NAMES UTF8");
    } catch (PDOException $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function __destruct() {
    try {
      $this->pdo = null;                  //Closes connection
    } catch (PDOException $e) {
      file_put_contents("log/dberror.log", "Date: " . date('M j Y - G:i:s') . " ---- Error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
      die($e->getMessage());
    }
  }

  public static function GetInstance(): DbHandler {
    if (self::$instance == null)
      self::$instance = new self;

    return self::$instance;
  }

  public function execute(string $request, array $params = []): array {
    $stmt = $this->pdo->prepare($request);
    $stmt->execute($params);

    $ret = [];
    if (strpos($request, "INSERT") !== FALSE) {
      $ret = [$this->pdo->lastInsertId()];
      return $ret;
    }

    foreach ($stmt as $row)
      array_push($ret, $row);

    return $ret;
  }
}
