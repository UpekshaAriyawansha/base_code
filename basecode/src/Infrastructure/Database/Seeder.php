<?php

namespace Src\Infrastructure\Database;

abstract class Seeder
{
    abstract public function run(): void;
    
}