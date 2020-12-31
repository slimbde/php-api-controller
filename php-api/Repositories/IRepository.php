<?php


interface IRepository
{
  function GetList(): array;
  function Get($id);
  function Put($obj);
  function Post($obj);
  function Delete($id);
}
