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

  public function AlterCredentials(string $prevLogin, string $login, string $password): array {
    $params = [
      ":prevLogin" => $prevLogin,
      ":newLogin" => $login
    ];

    if (strlen($password) > 0) {
      $params[":newPassword"] =  $password;
      return $this->db->execute("UPDATE `users` SET `login`=:newLogin , `password`=:newPassword WHERE `login`=:prevLogin", $params);
    } else
      return $this->db->execute("UPDATE `users` SET `login`=:newLogin WHERE `login`=:prevLogin", $params);
  }

  public function GetDbInfo(): array {
    return $this->db->execute("SELECT
                                (SELECT COUNT(1) FROM `words`) AS Words,
                                (SELECT COUNT(1) FROM `phrases`) AS Phrases,
                                (SELECT COUNT(1) FROM `gerunds`) AS Gerunds,
                                (SELECT COUNT(1) FROM `phrasals`) AS Phrasals,
                                (SELECT COUNT(1) FROM `idioms`) AS Idioms")[0];
  }
}
