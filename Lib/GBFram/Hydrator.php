<?php

namespace Lib\GBFram;

trait Hydrator
{

    public function receiveHydratation(array $data)
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);

            if (is_callable([$this, $method])) {
                $this->$method($value);
            }
        }
    }

}
