<?php
require_once 'TApiController.php';


class UsersController extends TApiController
{
  public function __construct(IRepository $repo)
  {
    parent::__construct($repo);
  }

  protected function getAction()
  {
    $method = $this->method;
    switch ($method) {
      case 'GET':
        if (sizeof($this->requestUri) > 2)
          return strpos($this->requestUri[2], "authenticate") !== FALSE ? 'AuthenticateAction' : 'GetOneAction';
        else
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

  //// GET: api/authenticate?login=...&password=...
  public function AuthenticateAction()
  {
    $login = $this->requestParams["login"];
    $password = $this->requestParams["password"];

    $users = $this->repo->GetList();

    foreach ($users as $key => $value) {
      if ($value["password"] === $password && $value["login"] === $login)
        return $this->response($value, 200);
    }

    return $this->response("User not found", 404);
  }


  //// GET: api/users
  public function GetListAction()
  {
    $users = $this->repo->GetList();
    if ($users)
      return $this->response($users, 200);

    return $this->response('Not found', 404);
  }

  //// GET: api/users/1
  public function GetOneAction()
  {
    //id должен быть первым параметром после /users/x
    $id = array_shift($this->requestUri);

    if ($id) {
      $user = $this->repo->Get($id);
      if ($user)
        return $this->response($user, 200);
    }
    return $this->response('Not found', 404);
  }

  //// POST: api/users
  public function PostAction()
  {
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
  public function PutAction()
  {
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
  public function DeleteAction()
  {
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
