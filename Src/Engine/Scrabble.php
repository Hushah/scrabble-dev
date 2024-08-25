<?php


namespace Src\Engine;


use Src\Engine\Dictionary\Dictionary;

/**
 * Class Scrabble
 * @package Src\Engine
 */
class Scrabble
{
    /**
     * @var Dictionary
     */
    private Dictionary $dictionary;

    /**
     * @var string
     */
    private string $rack = '';

    /**
     * @var array 
     */
    private array $letterCount = array();

    /**
     * @var int 
     */
    private int $minWordLength = 3;

    /**
     * @var int 
     */
    private int $maxResultsDisplay = 100;

    /**
     * Scrabble constructor.
     * @param Dictionary $dictionary
     */
    public function __construct(Dictionary $dictionary)
    {
        $this->setDictionary($dictionary);
    }

    /**
     * @return Dictionary
     */
    public function getDictionary(): Dictionary
    {
        return $this->dictionary;
    }

    /**
     * @param Dictionary $dictionary
     * @return Scrabble
     */
    public function setDictionary(Dictionary $dictionary): Scrabble
    {
        $this->dictionary = $dictionary;
        
        return $this;
    }

    /**
     * @param string $word
     * @return int
     */
    public function calcWordScore(string $word): int
    {
        $letters = $this->formatStringToArray(strtoupper($word));

        $tileScores = $this->getTileScores();
        
        $score = 0;
        
        foreach ($letters as $letter) {
            if (key_exists($letter, $tileScores)) {
                $score += $tileScores[$letter];
            }
        }
        
        return $score;
    }

    /**
     * @param array $rack
     * @param array $compareWord
     * @return bool
     */
    public function canBeSpelt(array $rack, array $compareWord): bool
    {
        foreach (array_diff_assoc($compareWord, $rack) as $k => $v) {
            if ( ! key_exists($k, $rack) || $v > $rack[$k]) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * @param string|null $rack
     * @return array
     */
    public function matchInDictionary(string $rack = null): array
    {
        $rack = $this->getLetterCount($rack);

        $wordPool = array();

        foreach($this->getDictionary()->getWords() as $word) {

            $word = $this->removeNonAlphaCharacters($word);

            if (strlen($word) < $this->getMinWordLength()) {
                continue;
            }

            $wordCounted = $this->getWordLettersCounted($word);

            if (true === $this->canBeSpelt($rack, $wordCounted)) {
                $wordPool[$word] = $this->calcWordScore($word);
            }
        }

        arsort($wordPool);
        
        if (null === $this->getMaxResultsDisplay()) {
            return $wordPool;
        }

        return array_slice($wordPool, 0, $this->getMaxResultsDisplay());
    }

    /**
     * @param string $string
     * @return string|string[]|null
     */
    public function removeNonAlphaCharacters(string $string)
    {
        /**
         * todo replace this with a method to strip non alpha chars
         * COMPLETED
         * Author: Hussain Shah
         */

        /** preg_replace() takes all the chars from the regular expression ('/[^a-zA-Z]/') 
         * and replaces them with an empty string ('') which is then assigned back to the $string variable.
         */
        $string = preg_replace('/[^a-zA-Z]/', '', $string);

        return $string;
    }

    /**
     * @param string $string
     * @return array
     */
    public function formatStringToArray(string $string): array
    {
        return str_split($this->removeNonAlphaCharacters($string));
    }

    /**
     * @return string
     */
    public function getRack(): string
    {
        return $this->rack;
    }

    /**
     * @param string $rack
     */
    public function setRack(string $rack)
    {
        $this->rack = $rack;
    }

    /**
     * @param string $word
     * @return array
     */
    public function getWordLettersCounted(string $word): array
    {
        $stringArray = $this->formatStringToArray(strtolower(trim($word)));

        $count = array();
        if (is_array($stringArray)) {
            foreach ($stringArray as $value) {
                if ( ! key_exists($value, $count)) {
                    $count[$value] = 0;
                }
                $count[$value]++;
            }
        }
        
        return $count;
    }

    /**
     * @param string|null $string
     * @return array
     */
    public function getLetterCount(string $string = null): array
    {
        if (empty($this->letterCount)) {
            if (null === $string) {
                $string = $this->getRack();
            }

            $this->setLetterCount($this->getWordLettersCounted($string));
        }

        return $this->letterCount;
    }

    /**
     * @param array $letterCount
     */
    public function setLetterCount(array $letterCount)
    {
        $this->letterCount = $letterCount;
    }

    /**
     * @return array
     */
    public function getTileScores(): array
    {
        return $this->getDictionary()->getValues();
    }

    /**
     * @return int
     */
    public function getMinWordLength(): int
    {
        return $this->minWordLength;
    }

    /**
     * @param int $minWordLength
     */
    public function setMinWordLength(int $minWordLength)
    {
        $this->minWordLength = $minWordLength;
    }

    /**
     * @return int
     */
    public function getMaxResultsDisplay(): int
    {
        return $this->maxResultsDisplay;
    }

    /**
     * @param int $maxResultsDisplay
     */
    public function setMaxResultsDisplay(int $maxResultsDisplay)
    {
        $this->maxResultsDisplay = $maxResultsDisplay;
    }
}
