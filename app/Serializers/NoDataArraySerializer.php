<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: NoDataArraySerializer.php
 * Description:
 * Date: 2020/01/16
 * Time: 12:45 上午
 */

namespace App\Serializers;


use League\Fractal\Serializer\ArraySerializer;

class NoDataArraySerializer extends ArraySerializer
{
    /**
     * Serialize a collection.
     * @param $resourceKey
     * @param array $data
     * @return array
     */
    public function collection($resourceKey, array $data)
    {
        return ($resourceKey) ? [$resourceKey => $data] : $data;
    }

    /**
     * Serialize an item.
     * @param $resourceKey
     * @param array $data
     * @return array
     */
    public function item($resourceKey, array $data)
    {
        return ($resourceKey) ? [$resourceKey => $data] : $data;
    }
}
