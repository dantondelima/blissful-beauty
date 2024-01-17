<?php

use App\Domain\Model\User;
use App\Infrastructure\Database\ConnectionCreator;
use App\Infrastructure\Repository\PdoUserRepository;

require_once 'vendor/autoload.php';

$pdo = (new ConnectionCreator())->create();
$userRepository = new PdoUserRepository($pdo);

$user = new User(
    null,
    'Danton teste'
);

//insert
$userRepository->insert($user);

var_dump($user);

//remove 
$userRepository->remove($user);

//List all
var_dump($userRepository->allUsers());