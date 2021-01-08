<?php


interface IUserRepository {
  /** retrieves an entity for provided id */
  function Get(string $id): array;

  /** retrieves the whole list of entities */
  function GetList(): array;

  /** performs necessary user registration routine */
  function Register(string $login, string $password): array;

  /** retrieves db info from db */
  function GetDbInfo(): array;
}



interface ITrainingRepository {
  /** retrieves everything matching particle */
  function GetFor(string $particle): array;

  /** retrieves entities belonging to id */
  function GetSetFor(string $id): array;

  /** retrieves generals belonging to id */
  function GetGeneralsFor(string $id): array;

  /** sets a general notion as solved */
  function SetGeneralsFor(string $id, string $notionId): void;
}
