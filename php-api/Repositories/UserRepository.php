<?php

require_once 'IRepository.php';
require_once 'DbHandler.php';



class UserRepository implements IRepository {
  private $db;

  public function __construct() {
    $this->db = DbHandler::GetInstance();
  }

  public function GetList(): array {
    return $this->db->execute("SELECT * FROM USERS");
  }

  public function Get(string $id): array {
    $params = [':id' => $id];
    return $this->db->execute("SELECT * FROM USERS WHERE id=:id", $params);
  }
}
