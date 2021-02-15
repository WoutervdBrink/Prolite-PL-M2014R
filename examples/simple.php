<?php

use Knevelina\Prolite\Display;
use Knevelina\Prolite\DisplayText;
use Knevelina\Prolite\Models\Timer;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/functions.php';

$display = new Display(1);

$display->getPageBank()->setPage(0, DisplayText::DISPLAY_APPEAR . 'Page 1');
$display->getPageBank()->setPage(1, DisplayText::DISPLAY_APPEAR . 'Page 2');
$display->getPageBank()->setPage(2, DisplayText::DISPLAY_APPEAR . 'Page 3');

$display->getTimerBank()->setTimer(0, new Timer(-1, -1, -1, [0, 1, 2]));

writetty($display->getConfiguration());