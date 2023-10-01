<?php
session_start();
require_once("bootstrap.php");


$templateVariables = [
  'session' => $_SESSION
];

echo $twig->render('index.twig', $templateVariables);
