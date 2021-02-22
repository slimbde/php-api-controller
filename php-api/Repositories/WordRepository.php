<?php

require_once 'DbHandler.php';
require_once 'IRepository.php';



class WordRepository implements ITrainingRepository {
  private $db;

  public function __construct() {
    $this->db = DbHandler::GetInstance();
  }

  public function GetFor(string $particle): array {
    $params = [
      ':particle' => "%$particle%",
      ':particle1' => "%$particle%"
    ];
    return $this->db->execute(" SELECT * 
                                FROM `words` 
                                WHERE `Word` LIKE :particle 
                                  OR `Translation` LIKE :particle1", $params);
  }

  public function GetSetFor(string $login): array {
    $params = [
      ':login' => $login
    ];

    $user = $this->db->execute("SELECT * FROM `users` where login=:login", $params);

    if (count($user)) {
      $point = new DateTime($user[0]['point']);

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
        $this->db->execute("UPDATE `users` SET `point`=:newPoint WHERE `login`=:login", $params);
      }

      // перепишем точку активности в базе
      $nowTimestamp = $now->format('Y-m-d H:i:s');

      $params = [
        ':nowTimestamp' => $nowTimestamp,
        ':login' => $login
      ];
      $this->db->execute('UPDATE `users` SET `active`=:nowTimestamp WHERE `login`=:login', $params);

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
          		FROM `words`
              WHERE `Add Date` IN (:today,:today1day,:today3day,:today7day,:today14day,:today30day,:today60day,:today120day)";

      return $this->db->execute($req, $params);
    }

    return [];
  }

  public function GetGeneralsFor(string $login): array {
    $params = [
      ':login' => $login
    ];

    return $this->db->execute("SELECT w.`#`, w.Issue, w.Answer
                                FROM `words` as w
                                WHERE INSTR(w.`Issue`, '__') > 0
                                AND w.`#` NOT IN (
                                  SELECT solved FROM `progress_generals` WHERE id=(
                                    SELECT `id` FROM `users` WHERE login=:login
                                  )
                                )", $params);
  }

  public function SetGeneralsFor(string $login, string $notionId): void {
    $params = [
      ':login' => $login,
      ':notionId' => $notionId
    ];

    $this->db->execute("INSERT INTO `progress_generals` (`id`,`solved`) VALUES ((SELECT `id` FROM `users` WHERE login=:login), :notionId)", $params);
  }

  public function GetGerundsFor(string $login): array {
    $params = [
      ':login' => $login
    ];

    return $this->db->execute("SELECT w.`#`, w.Issue, w.Answer
                                FROM `gerunds` as w
                                WHERE w.Issue <> ''
                                AND w.`#` NOT IN (
                                  SELECT solved FROM `progress_gerunds` WHERE id=(
                                    SELECT `id` FROM `users` WHERE login=:login
                                  )
                                )", $params);
  }

  public function SetGerundsFor(string $login, string $notionId): void {
    $params = [
      ':login' => $login,
      ':notionId' => $notionId
    ];

    $this->db->execute("INSERT INTO `progress_gerunds` (`id`,`solved`) VALUES ((SELECT `id` FROM `users` WHERE login=:login), :notionId)", $params);
  }

  public function GetPhrasesFor(string $login): array {
    $params = [
      ':login' => $login
    ];

    return $this->db->execute("SELECT w.`#`, w.Issue, w.Answer
                                FROM `phrases` as w
                                WHERE w.Issue <> ''
                                AND w.`#` NOT IN (
                                  SELECT solved FROM `progress_phrases` WHERE id=(
                                    SELECT `id` FROM `users` WHERE login=:login
                                  )
                                )", $params);
  }

  public function SetPhrasesFor(string $login, string $notionId): void {
    $params = [
      ':login' => $login,
      ':notionId' => $notionId
    ];

    $this->db->execute("INSERT INTO `progress_phrases` (`id`,`solved`) VALUES ((SELECT `id` FROM `users` WHERE login=:login), :notionId)", $params);
  }

  public function GetIdiomsFor(string $login): array {
    $params = [
      ':login' => $login
    ];

    return $this->db->execute("SELECT w.`#`, w.Issue, w.Answer
                                FROM `idioms` as w
                                WHERE w.Issue <> ''
                                AND w.`#` NOT IN (
                                  SELECT solved FROM `progress_idioms` WHERE id=(
                                    SELECT `id` FROM `users` WHERE login=:login
                                  )
                                )", $params);
  }

  public function SetIdiomsFor(string $login, string $notionId): void {
    $params = [
      ':login' => $login,
      ':notionId' => $notionId
    ];

    $this->db->execute("INSERT INTO `progress_idioms` (`id`,`solved`) VALUES ((SELECT `id` FROM `users` WHERE login=:login), :notionId)", $params);
  }

  public function GetPhrasalsCategories(): array {
    return $this->db->execute("SELECT DISTINCT `range` FROM `phrasals` ORDER BY `range` ASC ");
  }

  public function GetPhrasalsFor(string $category): array {
    $params = [
      ':category' => $category
    ];

    return $this->db->execute("SELECT `#`, `Translation`, `Phrasal`, `hint`
                                FROM `phrasals`
                                WHERE `range` = :category", $params);
  }

  public function Post($notion): array {
    $date = $this->db->execute("SELECT `Add Date` FROM `words`
                                GROUP BY `Add Date` HAVING COUNT(*) < 7
                                ORDER BY `Add Date`
                                LIMIT 1");

    $params = [
      ':notion' => $notion->notion,
      ':ipa' => $notion->ipa,
      ':meaning' => $notion->meaning,
      ':date' => $date[0]["Add Date"],
      ':example' => $notion->example,
      ':issue' => $notion->issue,
      ':solution' => $notion->solution,
    ];

    return $this->db->execute("INSERT INTO `words` (`Word`, `IPA`, `Translation`, `Add Date`, `Example`, `Issue`, `Answer`)
                               VALUES (:notion,:ipa,:meaning,:date,:example,:issue,:solution)", $params);
  }

  public function Delete($notionId): array {
    $params = [':id' => $notionId];

    $result = $this->db->execute(("DELETE FROM `words` WHERE `#`=:id"), $params);
    array_push($result, $notionId);

    return $result;
  }

  public function Put($notion): array {
    $params = [
      ':notion' => $notion->notion,
      ':ipa' => $notion->ipa,
      ':meaning' => $notion->meaning,
      ':example' => $notion->example,
      ':id' => $notion->id,
      ':issue' => $notion->issue,
      ':solution' => $notion->solution,
    ];

    $result = $this->db->execute("UPDATE `words` 
                                SET `Word`=:notion, `IPA`=:ipa, `Translation`=:meaning, `Example`=:example, `Issue`=:issue, `Answer`=:solution
                                WHERE `#`=:id", $params);
    array_push($result, $notion->id);
    return $result;
  }
}
