<?php

namespace App\Helpers;

class TrieNode
{
    public string $char;
    public array $children = [];
    public bool $end = false;
    public function __construct(string $char)
    {
        $this->char = $char;
    }
}
