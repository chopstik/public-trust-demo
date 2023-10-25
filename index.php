<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\User;
use App\Transaction;

echo '<h3>1. Please create some PHP code in a memory restricted environment to retrieve fictional user data from a database and anonymize the email address. Please consider the use of generators or pagination and the use of SOLID.</h3>';

$user = new User();
$user->createTestRecords();

/*echo '<h4>Raw user data</h4>';
echo '<pre>';
print_r($user->getUsers());
echo '</pre>';*/

echo '<hr>';

echo '<h4>Obfuscated user data</h4>';
echo '<pre>';
print_r($user->getUsers(obfuscate: true));
echo '</pre>';


echo '<h3>2. You are given the set below as data. Please create a database design using a SQL database design, please consider Normal Form.</h3>';

$transaction = new Transaction();
$transaction->ingestJson();

echo '<h4>Raw transactions</h4>';
echo '<pre>';
print_r($transaction->getTransactions());
echo '</pre>';
