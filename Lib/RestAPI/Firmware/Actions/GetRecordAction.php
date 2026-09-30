<?php

declare(strict_types=1);
/*
 * Copyright © MIKO LLC
 * Licensed under the GNU General Public License v3.0 or later;
 * see the LICENSE file in the root of this repository.
 */

namespace Modules\ModuleAutoprovision\Lib\RestAPI\Firmware\Actions;

use DzvinPBX\PBXCoreREST\Lib\PBXApiResult;
use Modules\ModuleAutoprovision\Models\ModuleAutoprovisionFirmware;

class GetRecordAction
{
    public static function main(array $data): PBXApiResult
    {
        $res = new PBXApiResult();
        $id  = (int)($data['id'] ?? 0);
        if ($id <= 0) {
            $res->httpCode            = 400;
            $res->messages['error'][] = 'firmware id is required';
            return $res;
        }
        $row = ModuleAutoprovisionFirmware::findFirst([
            'id = :id:',
            'bind' => ['id' => $id],
        ]);
        if ($row === null) {
            $res->httpCode = 404;
            return $res;
        }
        $res->success  = true;
        $res->httpCode = 200;
        $res->data     = [
            'id'          => (int)$row->id,
            'vendor'      => (string)$row->vendor,
            'model'       => $row->model,
            'filename'    => (string)$row->filename,
            'version'     => $row->version,
            'size'        => (int)$row->size,
            'sha256'      => (string)$row->sha256,
            'uploaded_at' => $row->uploaded_at,
            'notes'       => $row->notes,
        ];
        return $res;
    }
}
