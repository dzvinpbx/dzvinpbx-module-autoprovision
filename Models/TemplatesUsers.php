<?php

declare(strict_types=1);
/*
 * Copyright © MIKO LLC
 * Licensed under the GNU General Public License v3.0 or later;
 * see the LICENSE file in the root of this repository.
 * Written by Alexey Portnov, 11 2018
 */

namespace Modules\ModuleAutoprovision\Models;

use DzvinPBX\Modules\Models\ModulesModelsBase;

/**
 * Per-user/per-MAC binding to a provisioning template.
 */
class TemplatesUsers extends ModulesModelsBase
{
    /**
     * @Primary
     * @Identity
     * @Column(type="integer", nullable=false)
     */
    public $id;

    /**
     * @Column(type="string", nullable=true)
     */
    public $userId;

    /**
     * @Column(type="string", nullable=true)
     */
    public $mac;

    /**
     * @Column(type="string", nullable=true)
     */
    public $templateId;

    public function initialize(): void
    {
        $this->setSource('m_TemplatesUsers');
        parent::initialize();
    }
}
