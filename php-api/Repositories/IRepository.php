<?php


interface IRepository {
  /** retrieves an entity for provided id */
  function Get(string $id): array;

  /** retrieves the whole list of entities */
  function GetList(): array;
}



interface ITrainingRepository extends IRepository {
  /** retrieves everything matching particle */
  function GetFor(string $particle): array;

  /** retrieves entities belonging to id */
  function GetSetFor(string $id): array;

  /** retrieves generals belonging to id */
  function GetGeneralsFor(string $id): array;

  /** sets a general notion as solved */
  function SetGeneralsFor(string $id, string $notionId): void;
}
