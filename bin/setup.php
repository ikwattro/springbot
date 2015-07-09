<?php

require_once(__DIR__.'/../vendor/autoload.php');

use Ikwattro\SpringBot\Bot;

$bot = new Bot(false);
$bot->process();