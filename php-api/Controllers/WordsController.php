<?php
require_once 'TApiController.php';


class WordsController extends TApiController {
  private $repo;

  public function __construct(ITrainingRepository $repo) {
    parent::__construct();
    $this->repo = $repo;
  }

  protected function getAction() {
    switch ($this->method) {
      case 'GET':
        if (sizeof($this->requestUri) > 2) {
          if (strpos($this->requestUri[2], "getfor") !== FALSE)
            return "GetForAction";
          else if (strpos($this->requestUri[2], "getsetfor") !== FALSE)
            return "GetSetForAction";
          else if (strpos($this->requestUri[2], "getgeneralsfor") !== FALSE)
            return "GetGeneralsForAction";
          else if (strpos($this->requestUri[2], "setgeneralsfor") !== FALSE)
            return "SetGeneralsForAction";
          else if (strpos($this->requestUri[2], "getgerundsfor") !== FALSE)
            return "GetGerundsForAction";
          else if (strpos($this->requestUri[2], "setgerundsfor") !== FALSE)
            return "SetGerundsForAction";
          else if (strpos($this->requestUri[2], "getphrasesfor") !== FALSE)
            return "GetPhrasesForAction";
          else if (strpos($this->requestUri[2], "setphrasesfor") !== FALSE)
            return "SetPhrasesForAction";
          else if (strpos($this->requestUri[2], "getidiomsfor") !== FALSE)
            return "GetIdiomsForAction";
          else if (strpos($this->requestUri[2], "setidiomsfor") !== FALSE)
            return "SetIdiomsForAction";
        } else
          return "GetListAction";
      case 'POST':
        return 'PostAction';
      case 'PUT':
        return 'PutAction';
      case 'DELETE':
        return 'DeleteAction';
      default:
        return null;
    }
  }

  //// GET: php-api/words/getfor?particle=...
  public function GetForAction() {
    try {
      $particle = $this->requestParams["particle"];
      return $this->response($this->repo->GetFor($particle));
    } catch (Throwable $ex) {
      return $this->response($ex->getMessage());
    }
  }

  //// GET: php-api/words
  public function GetListAction() {
    return $this->response([]);
  }

  //// GET: php-api/words/getsetfor?login=...
  public function GetSetForAction() {
    try {
      $login = $this->requestParams["login"];
      return $this->response($this->repo->GetSetFor($login));
    } catch (Throwable $th) {
      return $this->response($th->getMessage());
    }
  }

  //// GET: php-api/words/getgeneralsfor?login=...
  public function GetGeneralsForAction() {
    try {
      $login = $this->requestParams["login"];
      return $this->response($this->repo->GetGeneralsFor($login));
    } catch (Throwable $th) {
      return $this->response($th->getMessage());
    }
  }

  //// GET: php-api/words/setgeneralsfor?login=...&notionId=...
  public function SetGeneralsForAction() {
    try {
      $login = $this->requestParams["login"];
      $notionId = $this->requestParams["notionId"];
      $this->repo->SetGeneralsFor($login, $notionId);
      return $this->response("OK");
    } catch (Throwable $th) {
      return $this->response($th->getMessage());
    }
  }

  //// GET: php-api/words/getgerundsfor?login=...
  public function GetGerundsForAction() {
    try {
      $login = $this->requestParams["login"];
      return $this->response($this->repo->GetGerundsFor($login));
    } catch (Throwable $th) {
      return $this->response($th->getMessage());
    }
  }

  //// GET: php-api/words/setgerundsfor?login=...&notionId=...
  public function SetGerundsForAction() {
    try {
      $login = $this->requestParams["login"];
      $notionId = $this->requestParams["notionId"];
      $this->repo->SetGerundsFor($login, $notionId);
      return $this->response("OK");
    } catch (Throwable $th) {
      return $this->response($th->getMessage());
    }
  }

  //// GET: php-api/words/getphrasesfor?login=...
  public function GetPhrasesForAction() {
    try {
      $login = $this->requestParams["login"];
      return $this->response($this->repo->GetPhrasesFor($login));
    } catch (Throwable $th) {
      return $this->response($th->getMessage());
    }
  }

  //// GET: php-api/words/setphrasesfor?login=...&notionId=...
  public function SetPhrasesForAction() {
    try {
      $login = $this->requestParams["login"];
      $notionId = $this->requestParams["notionId"];
      $this->repo->SetPhrasesFor($login, $notionId);
      return $this->response("OK");
    } catch (Throwable $th) {
      return $this->response($th->getMessage());
    }
  }

  //// GET: php-api/words/getidiomsfor?login=...
  public function GetIdiomsForAction() {
    try {
      $login = $this->requestParams["login"];
      return $this->response($this->repo->GetIdiomsFor($login));
    } catch (Throwable $th) {
      return $this->response($th->getMessage());
    }
  }

  //// GET: php-api/words/setidiomsfor?login=...&notionId=...
  public function SetIdiomsForAction() {
    try {
      $login = $this->requestParams["login"];
      $notionId = $this->requestParams["notionId"];
      $this->repo->SetIdiomsFor($login, $notionId);
      return $this->response("OK");
    } catch (Throwable $th) {
      return $this->response($th->getMessage());
    }
  }
}
