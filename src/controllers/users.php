<?php
session_start();
requireValidSession(true);

if (isset($_GET['delete'])) {
    try {
        User::deleteById($_GET['delete']);
        addSuccessMsg('Usuário excluído com sucesso.');
    } catch (Exception $e){
        if(stripos($e->getMessage(), 'FOREIGN KEY')){
            addErrorMsg('Não é possível excluir o usuário com registros de ponto.');
        } else $exception = $e;
    }
}

$users = User::get();
foreach ($users as $user){
    $user->start_date = (new DateTime($user->start_date))->format('d/m/Y');
}



loadTemplateView('users', [
    'users' => $users, 'exception' => $exception
]);