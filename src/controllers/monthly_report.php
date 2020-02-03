<?php
session_start();
requireValidSession();

$currentDate = new DateTime();

$user = $_SESSION['user'];
$registries = WorkingHours::getMonthlyReport($user->id, new DateTime());

$report = [];
$workDay = 0;
$sumOfWorkedTime = 0;
$lastDay = getLastDayOfMonth($currentDate)->format('d');

for ($day = 1; $day <= $lastDay; $day++){
    $date = $currentDate->format('Y-m') . '-' . sprintf('%02d', $day);
    print_r($registries[$date]);
    echo "<br>";
}

//loadTemplateView('monthly_report', [
//    'registries' => $registries
//]);
