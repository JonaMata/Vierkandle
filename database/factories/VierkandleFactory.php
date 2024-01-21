<?php

namespace Database\Factories;

use App\Helpers\Trie;
use App\Models\Vierkandle;
use App\Models\VierkandleSolution;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;

class VierkandleFactory extends Factory
{
    protected $model = Vierkandle::class;

    public function configure()
    {
        return $this->afterCreating(function (Vierkandle $vierkandle) {
            $solutions = $this->findSolutions($vierkandle->letters);
            $client = new \GuzzleHttp\Client(['base_uri' => 'https://nl.wiktionary.org', 'allow_redirects' => false]);
            foreach ($solutions as $solution) {
                $url = 'w/index.php?search=' . strtolower($solution['word']) . '&ns0=1';
                $response = $client->request('GET', $url);
                $solution['url'] = $response->getStatusCode() == 302 ? $response->getHeader('location')[0] : null;
                if (!$solution['url']) {
                    $solution['bonus'] = true;
                }
                $vierkandle->solutions()->create($solution);
            }
        });
    }

    public function definition()
    {
        $found = false;
        $tries = 0;
        while (!$found) {
            $tries++;
            \Laravel\Prompts\info('Try ' . $tries . '...');
            $letters = $this->generateBoard(4);
            $solutions = $this->findSolutions($letters);
            $longestWord = collect($solutions)->filter(fn($solution) => !$solution['bonus'])->max(fn($solution) => strlen($solution['word']));
            $usedLetters = collect($solutions)->filter(fn($solution) => !$solution['bonus'])->map(fn($solution) => $solution['chain'])->flatten()->unique()->count();
            $mandatoryWords = collect($solutions)->filter(fn($solution) => !$solution['bonus'])->count();
            $found = $longestWord >= 8 && $usedLetters >= 16 && $mandatoryWords <= 100;
        }
        return [
            'letters' => $letters,
            'date' => Carbon::tomorrow(),
        ];
    }

    function generateBoard(int $size): string
    {
//        $sampleSpace = str_split("EEEEEEEEEEEEEEEEEENNNNNNNNNNAAAAAAOOOOOOIIIIDDDDDRRRRRSSSSSTTTTTGGGKKKLLLMMMBBPPUUUFFHHJJVVZZCCWWXYQ");
        $sampleSpace = str_split("EEEEEEENNNNNNAAAAAOOOOOIIIIDDDDRRRRSSSSTTTTGGGKKKLLLMMMBBPPUUUFFHHJJVVZZCCWWXYQ");
        $keys = [];
        for ($i = 0; $i < $size ** 2; $i++) {
            $keys[] = rand(0, count($sampleSpace) - 1);
        }
//        $usedLetters = array_map(fn($key): string => $sampleSpace[$key], array_rand($sampleSpace, $size * $size));
        $usedLetters = array_map(fn($key): string => $sampleSpace[$key], $keys);
        shuffle($usedLetters);
        $letters = implode($usedLetters);
        for ($x = 0; $x < $size; $x++) {
//            print substr($letters, $x*$size, $size)."\n";
        }
        return $letters;
    }

    /**
     * Find the solutions of a given vierkandle
     * @param string $letters
     * @return string[]
     */
    function findSolutions(string $letters): array
    {
        $wordsTrie = $this->loadWords(base_path() . '/resources/files/wordlist.txt', $letters);
        $solutions = [];
        $found = [];
        for ($start = 0; $start < strlen($letters); $start++) {
            $solutions = array_merge($solutions, $this->attempt([$start], $letters[$start], $letters, $wordsTrie, $found));
        }
        return $solutions;
    }

    function attempt(array $chain, string $word, string $letters, Trie $wordsTrie, array &$found): array
    {
        $solutions = [];
        $wordsResult = $wordsTrie->search($word);

        if ($wordsResult == Trie::FOUND_NONE) {
            return $solutions;
        }

        if (($wordsResult == Trie::FOUND_WORD || $wordsResult == Trie::FOUND_BONUS_WORD) && !in_array($word, $found)) {
            $found[] = $word;
            $solutions[] = ['word' => $word, 'chain' => $chain, 'bonus' => $wordsResult == Trie::FOUND_BONUS_WORD];
        }
        foreach ($this->findNeighbours($letters, end($chain)) as $neighbour) {
            if (!in_array($neighbour, $chain)) {
                $solutions = array_merge($solutions, $this->attempt(array_merge($chain, [$neighbour]), $word . str_split($letters)[$neighbour], $letters, $wordsTrie, $found));
            }
        }

        return $solutions;
    }

    function findNeighbours($letters, $index): array
    {
        $size = sqrt(strlen($letters));
        $curX = $index % $size;
        $curY = intdiv($index, $size);

        $neighbours = [];

        for ($x = $curX - 1; $x <= $curX + 1; $x++) {
            for ($y = $curY - 1; $y <= $curY + 1; $y++) {
                if ($x >= 0 && $x < $size && $y >= 0 && $y < $size) {
                    if ($x != $curX || $y != $curY) {
                        $neighbours[] = $y * $size + $x;
                    }
                }
            }
        }

        return $neighbours;
    }

    function loadWords(string $path, string $letters): Trie
    {
        $newTrie = new Trie();
        foreach (file($path) as $word) {
            $splits = explode(',', trim($word));
            $word = $splits[0];
            $perc = $splits[1];
            if ($this->wordIsPossible($letters, $word)) {
                $newTrie->insert($word, $perc < 70);
            }
        }
        return $newTrie;
    }

    function wordIsPossible(string $letters, string $word): bool
    {
        foreach (str_split($word) as $char) {
            if (!str_contains($letters, $char)) {
                return false;
            }
        }
        return true;
    }
}
