<?php
require_once 'TApiController.php';


class UsersController extends TApiController {
  private $repo;

  public function __construct(IUserRepository $repo) {
    parent::__construct();
    $this->repo = $repo;
  }

  protected function getAction() {
    switch ($this->method) {
      case 'GET':
        if (sizeof($this->requestUri) > 2) {
          if (strpos($this->requestUri[2], "authenticate") !== FALSE)
            return "AuthenticateAction";
          else if (strpos($this->requestUri[2], "getdbinfo") !== FALSE)
            return "GetDbInfoAction";
          else if (strpos($this->requestUri[2], "fish-auth") !== FALSE)
            return "FishAuthAction";
          else if (strpos($this->requestUri[2], "fish-register") !== FALSE)
            return "FishRegisterAction";
          else if (strpos($this->requestUri[2], "fish-add-scores") !== FALSE)
            return "FishAddScoresAction";
          else if (strpos($this->requestUri[2], "fish-get-scores") !== FALSE)
            return "FishGetScoresAction";
        } else
          return "GetListAction";
        break;
      case 'POST':
        return 'RegisterAction';
      case 'PUT':
        return 'PutAction';
      case 'DELETE':
        return 'DeleteAction';
      default:
        return null;
    }
  }

  //// GET: api/users/authenticate?login=...&password=...
  public function AuthenticateAction() {
    $login = $this->requestParams["login"];
    $password = $this->requestParams["password"];

    $users = $this->repo->GetList();

    foreach ($users as $key => $value) {
      if ($value["password"] === $password && strtolower($value["login"]) === strtolower($login))
        return $this->response("OK");
    }

    return $this->response([]);
  }

  //// POST: api/users
  public function RegisterAction() {
    try {
      $login = $_POST["login"];
      $password = $_POST["password"];

      $result = $this->repo->Register($login, $password);
      return $this->response("OK");
    } catch (Throwable $th) {
      return $this->response($th->getMessage());
    }
  }

  //// GET: api/users
  public function GetListAction() {
    $users = $this->repo->GetList();
    return $this->response($users);
  }

  //// GET: api/users/getdbinfo
  public function GetDbInfoAction() {
    return $this->response($this->repo->GetDbInfo());
  }

  //// GET: api/users/1
  public function GetOneAction() {
    return $this->response();
  }

  public function FishAuthAction() {
    $login = $this->requestParams["login"];
    $hash = $this->requestParams["hash"];

    $scores = $this->repo->checkFishAuth($login, $hash);
    return $scores > 0
      ? $this->response([$scores])
      : $this->response([0]);        // empty array means 404
  }

  public function FishRegisterAction() {
    try {
      $login = $this->requestParams["login"];
      $hash = $this->requestParams["hash"];

      $this->repo->registerFish($login, $hash);
      return $this->response("OK");
    } catch (Throwable $th) {
      return $this->response($th->getMessage());
    }
  }

  public function FishAddScoresAction() {
    try {
      $login = $this->requestParams["login"];
      $scores = $this->requestParams["scores"];

      $this->repo->addFishScores($login, $scores);
      return $this->response("OK");
    } catch (Throwable $th) {
      return $this->response($th->getMessage());
    }
  }

  public function FishGetScoresAction() {
    try {
      $scores = $this->repo->getFishScores();
      return $this->response($scores);
    } catch (Throwable $th) {
      return $this->response($th->getMessage());
    }
  }

  //// POST: api/users
  public function PostAction() {
    //$name = $this->requestParams['name'] ?? '';
    //$email = $this->requestParams['email'] ?? '';
    //if ($name && $email) {
    //  $db = (new Db())->getConnect();
    //  $user = new Users($db, [
    //    'name' => $name,
    //    'email' => $email
    //  ]);
    //  if ($user = $user->saveNew()) {
    //    return $this->response('Data saved.', 200);
    //  }
    //}
    return $this->response("Saving error", 500);
  }

  //// PUT: api/users/1 + параметры запроса name, email
  public function PutAction() {
    //$parse_url = parse_url($this->requestUri[0]);
    //$userId = $parse_url['path'] ?? null;

    //$db = (new Db())->getConnect();

    //if (!$userId || !Users::getById($db, $userId)) {
    //  return $this->response("User with id=$userId not found", 404);
    //}

    //$name = $this->requestParams['name'] ?? '';
    //$email = $this->requestParams['email'] ?? '';

    //if ($name && $email) {
    //  if ($user = Users::update($db, $userId, $name, $email)) {
    //    return $this->response('Data updated.', 200);
    //  }
    //}
    return $this->response("Update error", 400);
  }

  //// DELETE: api/users/1
  public function DeleteAction() {
    //$parse_url = parse_url($this->requestUri[0]);
    //$userId = $parse_url['path'] ?? null;

    //$db = (new Db())->getConnect();

    //if (!$userId || !Users::getById($db, $userId)) {
    //  return $this->response("User with id=$userId not found", 404);
    //}
    //if (Users::deleteById($db, $userId)) {
    //  return $this->response('Data deleted.', 200);
    //}
    return $this->response("Delete error", 500);
  }
}