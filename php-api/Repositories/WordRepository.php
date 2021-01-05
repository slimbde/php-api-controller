<?php

require_once 'DbHandler.php';



class WordRepository implements ITrainingRepository {
  private $db;

  public function __construct() {
    $this->db = DbHandler::GetInstance();
  }

  public function GetList(): array {
    return $this->db->execute("SELECT * FROM WORDS");
  }

  public function GetFor(string $particle): array {
    $params = [
      ':particle' => "%$particle%",
      ':particle1' => "%$particle%"
    ];
    return $this->db->execute(" SELECT * 
                                FROM WORDS 
                                WHERE `Word` LIKE :particle 
                                  OR `Translation` LIKE :particle1", $params);
  }

  public function GetSetFor(string $id): array {
    $params = [
      ':id' => $id
    ];

    $user = $this->db->execute("SELECT * FROM USERS where id=:id", $params);

    if (count($user)) {
      $point = new DateTime($user[0]['point']);
      $login = $user[0]['login'];

      $now = new DateTime();
      $nowDate = $now->format('Y-m-d');                       // берем дату в текущий момент времени
      $activeDate = substr($user[0]['active'], 0, 10);        // грузим дату последней активности

      if ($nowDate != $activeDate) {                          // если текущая дата не равна дате последней активности
        $point->modify('+1 day');                             // инкрементируем точку отсчета для выборки слов
        $newPoint = $point->format('Y-m-d H:i:s');

        $params = [
          ':newPoint' => $newPoint,
          ':login' => $login
        ];
        $this->db->execute("UPDATE `USERS` SET `point`=:newPoint WHERE `login`=:login", $params);
      }

      // перепишем точку активности в базе
      $nowTimestamp = $now->format('Y-m-d H:i:s');

      $params = [
        ':nowTimestamp' => $nowTimestamp,
        ':login' => $login
      ];
      $this->db->execute('UPDATE USERS SET `active`=:nowTimestamp WHERE `login`=:login', $params);

      $params = [
        ":today" => $point->format('d.m.Y'),
        ":today1day" => (clone $point)->modify('-1 day')->format('d.m.Y'),
        ":today3day" => (clone $point)->modify('-3 day')->format('d.m.Y'),
        ":today7day" => (clone $point)->modify('-7 day')->format('d.m.Y'),
        ":today14day" => (clone $point)->modify('-14 day')->format('d.m.Y'),
        ":today30day" => (clone $point)->modify('-30 day')->format('d.m.Y'),
        ":today60day" => (clone $point)->modify('-60 day')->format('d.m.Y'),
        ":today120day" => (clone $point)->modify('-120 day')->format('d.m.Y'),
      ];

      $req = "SELECT *
          		FROM WORDS
              WHERE `Add Date` IN (:today,:today1day,:today3day,:today7day,:today14day,:today30day,:today60day,:today120day)";

      return $this->db->execute($req, $params);
    }

    return [];
  }

  public function GetGeneralsFor(string $id): array {
    $params = [
      ':id' => $id
    ];

    return $this->db->execute("SELECT w.`#`, w.Issue, w.Answer
                                FROM WORDS as w
                                WHERE w.Issue <> ''
                                AND w.`#` NOT IN (SELECT solved FROM PROGRESS_GENERALS WHERE id=:id)", $params);
  }

  public function SetGeneralsFor(string $id, string $notionId): void {
    $params = [
      ':id' => $id,
      ':notionId' => $notionId
    ];

    $this->db->execute("INSERT INTO PROGRESS_GENERALS (`id`,`solved`) VALUES (:id, :notionId)", $params);
  }

  public function Get(string $id): array {
    throw new Exception("WordRepository - Get: not implemented");
  }
}
