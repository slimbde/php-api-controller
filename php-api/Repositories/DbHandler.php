<?php
///////////////// SINGLETON

class DbHandler
{
  private $SQLhost = '127.0.0.1';
  //private $SQLhost = '109.191.156.118';
  private $SQLuser = '2650602_db';
  private $SQLpass = '2213qweasdzxc';
  private $SQLdbase = '2650602_db';
  private $SQLport = '3306';
  private $mysqli;
  private static $instance = null;

  private function __construct()
  {
    $this->mysqli = new mysqli(
      $this->SQLhost,
      $this->SQLuser,
      $this->SQLpass,
      $this->SQLdbase,
      $this->SQLport
    );
    if ($this->mysqli->connect_errno)
      throw new Exception("Не удалось подключиться к MySQL: (" . $this->mysqli->connect_errno . ") " . $this->mysqli->connect_error);

    $result = $this->mysqli->query("SET NAMES UTF8");
  }

  public function __destruct()
  {
    if ($this->mysqli && !$this->mysqli->connect_errno)
      $this->mysqli->close();
  }

  public static function GetInstance(): DbHandler
  {
    if (self::$instance == null)
      self::$instance = new self;

    return self::$instance;
  }

  public function Get($request): array
  {
    $ret = array();
    $result = ($this->mysqli)->query($request);
    if ($result) {
      $result->data_seek(0);
      while ($row = $result->fetch_assoc())
        array_push($ret, $row);
    }

    return $ret;
  }

  public function Set($request): string
  {
    if (!$this->mysqli->query($request))
      return $this->GetError();

    return "";
  }

  public function GetError(): string
  {
    return $this->mysqli->error;
  }
}
