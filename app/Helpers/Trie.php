<?php

namespace App\Helpers;

class Trie
{
    const FOUND_WORD = 2;
    const FOUND_PREFIX = 1;
    const FOUND_NONE = 0;
    public TrieNode $root;

    public function __construct()
    {
        $this->root = new TrieNode("");
    }

    public function insert(string $word): void
    {
        $node = $this->root;


        foreach (str_split($word) as $char) {
            if (key_exists($char, $node->children)) {
                $node = $node->children[$char];
            } else {
                $new_node = new TrieNode($char);
                $node->children[$char] = $new_node;
                $node = $new_node;
            }
        }

        $node->end = true;
    }

    public function search(string $word) : int
    {
        $node = $this->root;
        foreach(str_split($word) as $char) {
            if (!key_exists($char, $node->children)) {
                return self::FOUND_NONE;
            }
            $node = $node->children[$char];
        }
        return $node->end ? self::FOUND_WORD : self::FOUND_PREFIX;
    }


}
