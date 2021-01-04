<?php
require_once 'TApiController.php';


class WordsController extends TApiController {
  public function __construct(ITrainingRepository $repo) {
    parent::__construct($repo);
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
    $words = $this->repo->GetList();
    return $this->response($words);
  }

  //// GET: php-api/words/getsetfor?id=...
  public function GetSetForAction() {
    try {
      $id = $this->requestParams["id"];
      return $this->response($this->repo->GetSetFor($id));
    } catch (Throwable $th) {
      return $this->response($th->getMessage());
    }
  }

  //// GET: php-api/words/getgeneralsfor?id=...
  public function GetGeneralsForAction() {
    try {
      $id = $this->requestParams["id"];
      return $this->response($this->repo->GetGeneralsFor($id));
    } catch (Throwable $th) {
      return $this->response($th->getMessage());
    }
  }
}
