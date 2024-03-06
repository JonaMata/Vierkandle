<?php

namespace App\Models;

use App\Helpers\Trie;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Vierkandle extends Model
{
    use HasFactory;

    protected $appends = ['solution_count', 'size'];

    public function solutions(): HasMany
    {
        return $this->hasMany(VierkandleSolution::class);
    }

    public function getSolutionsAttribute(): Collection {
        $solutions = $this->solutions()->get();
        $solved = VierkandleSolve::ofUser(auth()->user(), $this)->solution_ids;
        if (auth()->check()) {
            foreach ($solutions as $solution) {
                $solution->guessed = in_array($solution->id, $solved);
            }
        }
        return $solutions;
    }

    public function getSizeAttribute(): int {
        return intval(sqrt(strlen($this->letters)));
    }

    public function getSolutionCountAttribute(): int {
        return $this->solutions()->where('bonus', false)->count();
    }

    /**
     * @throws \Exception
     */
    public function generate(int $size, string $word = null): int
    {
        $wordsTrie = new Trie(base_path() . '/resources/files/wordlist_'.$size.'.txt');
        \Laravel\Prompts\info('Trie generated');
        if ($word && $wordsTrie->search($word) != Trie::FOUND_WORD) {
            throw new \Exception("Word not found in wordlist");
        }
        $found = false;
        $tries = 0;
        while (!$found) {
            $tries++;
            \Laravel\Prompts\info('Try ' . $tries);
            if ($word) {
                $letters = $this->generateBoardFromWord($size, $word);
                if (!$letters) {
                    continue;
                }
            } else {
                $letters = $this->generateBoard($size);
            }
            $this->letters = $letters;
            \Laravel\Prompts\info('Board generated');
            $solutions = $this->findSolutions($wordsTrie);
            \Laravel\Prompts\info('Found ' . count($solutions) . ' solutions');
            $longestWord = collect($solutions)->filter(fn($solution) => !$solution['bonus'])->max(fn($solution) => strlen($solution['word']));
            $usedLetters = collect($solutions)->filter(fn($solution) => !$solution['bonus'])->map(fn($solution) => $solution['chain'])->flatten()->unique()->count();
            $mandatoryWords = collect($solutions)->filter(fn($solution) => !$solution['bonus'])->count();
            $found = $longestWord >= 8 && $usedLetters >= $size ** 2 && $mandatoryWords <= $size ** 3;
        }
        \Laravel\Prompts\info('Starting save');
        $this->saveWithSolutions($solutions);
        return $tries;
    }

    public function generateBoard(int $size): string
    {
        $sampleSpace = str_split("EEEEEEENNNNNNAAAAAOOOOOIIIIDDDDRRRRSSSSTTTTGGGKKKLLLMMMBBPPUUUFFHHJJVVZZCCWWXYQ");
        $keys = [];
        for ($i = 0; $i < $size ** 2; $i++) {
            $keys[] = rand(0, count($sampleSpace) - 1);
        }
        $usedLetters = array_map(fn($key): string => $sampleSpace[$key], $keys);
        shuffle($usedLetters);
        $letters = implode($usedLetters);
        return $letters;
    }

    private function generateBoardFromWord($size, $word): string | bool {
        $letters = str_repeat('.', $size ** 2);
        $starts = range(0, strlen($letters));
        shuffle($starts);
        foreach ($starts as $start) {
            $board = $this->placeWord($start, $word, $letters);
            if ($board) {
                for ($i = 0; $i < strlen($board); $i++) {
                    if ($board[$i] == '.') {
                        $board[$i] = str_split("EEEEEEENNNNNNAAAAAOOOOOIIIIDDDDRRRRSSSSTTTTGGGKKKLLLMMMBBPPUUUFFHHJJVVZZCCWWXYQ")[rand(0, 78)];
                    }
                }
                return $board;
            }
        }
        return false;
    }

    private function placeWord($lastIndex, $word, $letters): string | bool {
        if (strlen($word) == 0) {
            return $letters;
        }
        $neighbours = $this->findNeighbours($lastIndex, $letters);
        shuffle($neighbours);
        foreach ($neighbours as $neighbour) {
            if ($letters[$neighbour] == '.') {
                $newLetters = $letters;
                $newLetters[$neighbour] = $word[0];
                $result = $this->placeWord($neighbour, substr($word, 1), $newLetters);
                if ($result) {
                    return $result;
                }
            }
        }
        return false;
    }

    private function findSolutions($wordsTrie): array
    {
        $solutions = [];
        $found = [];
        for ($start = 0; $start < strlen($this->letters); $start++) {
            \Laravel\Prompts\info('Starting from ' . $start);
            $solutions = array_merge($solutions, $this->attempt([$start], $this->letters[$start], $wordsTrie, $found));
        }
        return $solutions;
    }

    private function saveWithSolutions(array $solutions): void
    {
        $this->save();
        $client = new \GuzzleHttp\Client(['base_uri' => 'https://nl.wiktionary.org', 'allow_redirects' => false]);
        foreach ($solutions as $solution) {
            $url = 'w/index.php?search=' . strtolower($solution['word']) . '&ns0=1';
            $response = $client->request('GET', $url);
            $solution['url'] = $response->getStatusCode() == 302 ? $response->getHeader('location')[0] : null;
            if (!$solution['url']) {
                $solution['bonus'] = true;
            }
            $this->solutions()->create($solution);
        }
    }

    private function attempt(array $chain, string $word, Trie $wordsTrie, array &$found): array
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
        foreach ($this->findNeighbours(end($chain), $this->letters) as $neighbour) {
            if (!in_array($neighbour, $chain)) {
                $solutions = array_merge($solutions, $this->attempt(array_merge($chain, [$neighbour]), $word . str_split($this->letters)[$neighbour], $wordsTrie, $found));
            }
        }

        return $solutions;
    }

    private static function findNeighbours($index, $letters): array
    {
        $size = intval(sqrt(strlen($letters)));
        $curX = $index % $size;
        $curY = intdiv($index, $size);

        $neighbours = [];

        for ($x = $curX - 1; $x <= $curX + 1; $x++) {
            for ($y = $curY - 1; $y <= $curY + 1; $y++) {
                if ($x >= 0 && $x < $size && $y >= 0 && $y < $size) {
                    if ($x != $curX || $y != $curY) {
                        $neighbours[] = intval($y * $size + $x);
                    }
                }
            }
        }

        return $neighbours;
    }
}
