<?php

require_once 'IRepository.php';
require_once 'DbHandler.php';



class UserRepository implements IRepository
{
  private $db;

  public function __construct()
  {
    $this->db = DbHandler::GetInstance();
  }

  public function GetList(): array
  {
    return $this->db->Get("SELECT * FROM USERS");
  }

  public function Get($id)
  {
    return $this->db->Get("SELECT * FROM USERS WHERE id=$id");
  }

  public function Put($obj)
  {
    throw new Exception("UserRepository - Put: not implemented");
  }

  public function Post($obj)
  {
    throw new Exception("UserRepository - Post: not implemented");
  }

  public function Delete($id)
  {
    throw new Exception("UserRepository - Delete: not implemented");
  }
}
