<?php

declare(strict_types=1);
/**
 * Copyright (C) MIKO LLC
 * Licensed under the GNU General Public License v3.0 or later;
 * see the LICENSE file in the root of this repository.
 * Written by Nikolay Beketov, 5 2020
 *
 */

namespace Modules\ModuleAutoprovision\Lib;


interface ConfManager
{
    /**
     * Создает конфигурационный файл для телефона.
     *
     * @param $req_data
     * @param $sip_peers
     *
     * @return string
     */
    public function generateConfig($req_data, $sip_peers): string;
}