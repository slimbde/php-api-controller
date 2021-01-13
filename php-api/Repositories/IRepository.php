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

  /** retrieves gerunds belonging to id */
  function GetGerundsFor(string $id): array;

  /** sets a gerund notion as solved */
  function SetGerundsFor(string $id, string $notionId): void;

  /** retrieves phrases belonging to id */
  function GetPhrasesFor(string $id): array;

  /** sets a phrase notion as solved */
  function SetPhrasesFor(string $id, string $notionId): void;

  /** retrieves idioms belonging to id */
  function GetIdiomsFor(string $id): array;

  /** sets an idiom notion as solved */
  function SetIdiomsFor(string $id, string $notionId): void;
}
