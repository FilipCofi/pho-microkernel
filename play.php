<?php

require "vendor/autoload.php";

use Pho\Kernel\Kernel;
use PhoNetworksAutogenerated\Graph;
use PhoNetworksAutogenerated\User;

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$configs = array(
  "services"=>array(
   		"database" => getenv('DATABASE_URI'),
    	"storage" => getenv("STORAGE_URI"),
  ),
  "default_objects" => array(
  		"graph" => Graph::class,
  		"founder" => User::class,
  )
);

$kernel = new \Pho\Kernel\Kernel($configs);
$founder = new \PhoNetworksAutogenerated\User($kernel, $kernel->space(), "123456");
$kernel->boot($founder);

$founder = $kernel->founder();
$graph = $kernel->graph();