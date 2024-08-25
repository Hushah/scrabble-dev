<?php

namespace Src;

/**
 * Class Boot
 * @package Src
 */
class Boot
{
    /**
     * @var string
     */
    private string $locale = 'en-gb';

    /**
     * @var string
     */
    private string $root = __DIR__;

    /**
     * @var string
     */
    private string $dictionaryPath = 'Engine/Dictionary';

    /**
     * @var string
     */
    private string $dictionaryFileName = 'dictionary.txt';

    /**
     * @var string 
     */
    private string $letterValuesFileName = 'letterValues.txt';

    /**
     * Boot constructor.
     * @param string|null $locale
     */
    public function __construct(?string $locale = null)
    {
        if (null !== $locale) {
            $this->setLocale($locale);
        }
        $this->autoloader();
    }

    private function autoloader()
    {
        spl_autoload_register(function ($class_name) {
            include __DIR__ . str_replace(array('Src', '\\'), array('', '/'), $class_name) . '.php';
        });
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @return string
     */
    public function getRoot(): string
    {
        return $this->root;
    }

    /**
     * @param string $root
     */
    public function setRoot(string $root)
    {
        $this->root = $root;
    }

    /**
     * @return string
     */
    public function getDictionaryFilePath(): string
    {
        return $this->getFilePathWithName($this->getDictionaryFileName());
    }

    /**
     * @return string
     */
    public function getLetterValuesFilePath(): string
    {
        return $this->getFilePathWithName($this->getLetterValuesFileName());
    }

    /**
     * @param $name
     * @return string
     */
    private function getFilePathWithName($name): string
    {
        return sprintf(
            "%s/%s/%s",
            implode('/', array($this->getRoot(), $this->dictionaryPath)),
            $this->getLocale(),
            $name
        );
    }

    /**
     * @param string $dictionaryPath
     */
    public function setDictionaryPath(string $dictionaryPath)
    {
        $this->dictionaryPath = $dictionaryPath;
    }

    /**
     * @return string
     */
    public function getDictionaryFileName(): string
    {
        return $this->dictionaryFileName;
    }

    /**
     * @param string $dictionaryFileName
     */
    public function setDictionaryFileName(string $dictionaryFileName)
    {
        $this->dictionaryFileName = $dictionaryFileName;
    }

    /**
     * @return string
     */
    public function getLetterValuesFileName(): string
    {
        return $this->letterValuesFileName;
    }

    /**
     * @param string $letterValuesFileName
     */
    public function setLetterValuesFileName(string $letterValuesFileName)
    {
        $this->letterValuesFileName = $letterValuesFileName;
    }

    /**
     * @param string $locale
     */
    public function setLocale(string $locale)
    {
        $this->locale = $locale;
    }
}
