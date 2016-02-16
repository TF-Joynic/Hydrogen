<?php

namespace Hydrogen\Log\Storage;

abstract class AbstractStorage
{
    const FILE = 'Hydrogen\\Log\\Storage\\File';
    const MONGO = 'Hydrogen\\Log\\Storage\\File';

    public abstract function record($level, $logStr);
}