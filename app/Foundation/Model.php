<?php

namespace App\Foundation;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Model
 * @package App\Foundation
 */
class Model extends Eloquent
{
    public function fromDateTime($value)
    {
        return strtotime(parent::fromDateTime($value));
    }
}
