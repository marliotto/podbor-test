<?php

/**
 * Project: podbor-test.
 * User: marliotto
 * Date: 11.10.2021
 * Time: 21:29
 */

namespace Marliotto\PodborTest;


class Decoder
{
    /**
     * @param string $data
     *
     * @return mixed
     */
    public function decode(string $data)
    {
        // TODO: convert json exception to common exception
        return json_decode($data, true, \JSON_THROW_ON_ERROR);
    }
}
