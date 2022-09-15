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

  public function GetDbInfo(): array {
    return $this->db->execute("SELECT
                                (SELECT COUNT(1) FROM `words`) AS Words,
                                (SELECT COUNT(1) FROM `phrases`) AS Phrases,
                                (SELECT COUNT(1) FROM `gerunds`) AS Gerunds,
                                (SELECT COUNT(1) FROM `phrasals`) AS Phrasals,
                                (SELECT COUNT(1) FROM `idioms`) AS Idioms")[0];
  }

  public function checkFishAuth(string $login, string $hash): int {
    $params = [
      ":login" => $login,
      ":hash" => $hash,
    ];

    $scores = $this->db->execute("SELECT Scores FROM `FishAuth` WHERE Login=:login AND PasswordHash=:hash", $params);
    $scores = intval($scores[0]["Scores"]);

    if ($scores === 0)
      throw new Exception("No such user", 404);

    return $scores;
  }

  public function registerFish(string $login, string $hash): void {
    $params = [
      ":login" => $login,
    ];

    $exists = $this->db->execute("SELECT * FROM `FishAuth` WHERE login=:login", $params);
    if (count($exists) > 0)
      throw new Exception("User-exists", 409);

    $params = [
      ":login" => $login,
      ":hash" => $hash,
    ];

    $this->db->execute("INSERT INTO `FishAuth`(`Login`,`PasswordHash`) VALUES (:login,:hash)", $params);
  }

  public function addFishScores(string $login, int $scores): void {
    $params = [
      ":login" => $login,
      ":scores" => $scores,
    ];

    $this->db->execute("UPDATE `FishAuth` SET Scores=:scores WHERE Login=:login", $params);
  }

  public function getFishScores(): array {
    $scores = $this->db->execute("SELECT Login, Scores FROM `FishAuth`");
    return $scores;
  }
}