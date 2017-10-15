<?php

namespace App;

abstract class BaseSearch
{
    public abstract function search($conditions);
    public abstract function prepare($parameters);
}
