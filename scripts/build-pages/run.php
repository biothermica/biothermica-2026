<?php

require_once 'autoload.php';
require_once 'config.php';

use myproject\App;

App::instance()->run($betaBasePath);
