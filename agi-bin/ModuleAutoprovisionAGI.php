#!/usr/bin/php
<?php
/**
 * Copyright © MIKO LLC
 * Licensed under the GNU General Public License v3.0 or later;
 * see the LICENSE file in the root of this repository.
 * Written by Alexey Portnov, 11 2018
 */

use Modules\ModuleAutoprovision\Lib\Autoprovision;

require_once 'Globals.php';

$agiWorker    = new Autoprovision();
$agiWorker->StartAGIProvision();
