<?php

require_once 'IRepository.php';
require_once 'DbHandler.php';



class UserRepository implements IUserRepository {
  private $db;

  public function __construct() {
    $this->db = DbHandler::GetInstance();
  }

  public function GetList(): array {
    return $this->db->execute("SELECT * FROM `users`");
  }

  public function Get(string $id): array {
    $params = [':id' => $id];
    return $this->db->execute("SELECT * FROM `users` WHERE id=:id", $params);
  }

  public function Register(string $login, string $password): array {
    $params = [":login" => $login];

    $exists = $this->db->execute("SELECT * FROM `users` WHERE login=:login", $params);
    if (count($exists) > 0)
      throw new Exception("User-exists", 409);

    $active = (new DateTime())->format('Y-m-d H:i:s');
    $initialPoint = '2018-03-12 00:00:00';

    $params = [":login" => $login, ":password" => $password, ":active" => $active, ":initialPoint" => $initialPoint];
    $id = $this->db->execute("INSERT INTO `users` (`login`,`password`,`active`,`point`) VALUES (:login, :password, :active, :initialPoint)", $params);

    return $this->Get($id[0]);
  }
}
