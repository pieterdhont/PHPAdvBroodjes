<?php
// bootstrap.php
require_once("vendor/autoload.php");
spl_autoload_register();

use Twig\Environment;
use Twig\Loader\FilesystemLoader;


$loader = new FilesystemLoader('App/Presentation');
$twig = new Environment($loader);
