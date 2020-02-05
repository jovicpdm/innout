<?php
session_start();
requireValidSession();

$users = User::get();
foreach ($users as $user){
    $user->start_date = (new DateTime($user->start_date))->format('d/m/Y');
}

loadTemplateView('users', ['users' => $users]);