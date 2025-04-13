<?php

namespace Fzayne\laraTranslation;

use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\Finder;

/**
 * Class LangKeyExtractor
 *
 * This class is responsible for scanning project files to extract translation keys
 * and exporting them to language-specific JSON files.
 */
class LangKeyExtractor
{
    // Directories to scan for translation keys
    protected $directories = [];

    // Regex patterns to match translation functions
    protected $patterns = [];

    // Extracted translation keys
    protected $translationKeys = [];

    // List of locales (e.g., ['en', 'fr'])
    protected $locales = [];

    // Output path for the generated translation files
    protected $output;

    /**
     * Constructor to optionally initialize directories, locales, and output path.
     */
    public function __construct($directories = [], $locales = [], $output = null)
    {
        $this->setDirectories($directories);
        $this->setLocales($locales);
        $this->output = $output ?? lang_path();

    }

    /**
     * Set the output path where translation JSON files will be saved.
     */
    public function setOutputPath($output)
    {
        $this->output = $output;

        return $this;

    }

    /**
     * Set the locales for which translation files will be generated.
     */
    public function setLocales($locales)
    {
        $this->locales = $locales;

        return $this;
    }

    /**
     * Set the directories to scan for translation keys.
     */
    public function setDirectories($directories)
    {
        $this->directories = $directories;

        return $this;

    }

    /**
     * Generate regular expression patterns based on translation function names.
     */
    public function generatePatterns($functions)
    {
        $this->patterns = [];
        foreach ($functions as $function) {
            $this->patterns[] = '/'.$function.'\([\'"](.+?)[\'"]\)/';
        }

        return $this;

    }

    /**
     * Extract and clean translation keys from file content using a given regex pattern.
     */
    protected function extractKeys(string $content, string $pattern, array &$keys): void
    {
        preg_match_all($pattern, $content, $matches);

        foreach ($matches[1] ?? [] as $key) {
            if (! empty($key)) {
                // Clean escaped quotes and backslashes
                $cleanKey = $this->cleanKey($key);
                $keys[$cleanKey] = true;
            }
        }
    }

    /**
     * Clean translation key from escape characters
     */
    protected function cleanKey(string $key): string
    {
        // Replace escaped single quotes with actual single quotes
        $key = str_replace("\\'", "'", $key);
        // Replace escaped double quotes with actual double quotes
        $key = str_replace('\\"', '"', $key);

        return $key;
    }

    /**
     * Extract all translation keys from the specified directories using the defined patterns.
     */
    public function extract()
    {
        $finder = new Finder;
        $finder->files()
            ->in($this->directories)
            ->name('*.php');

        foreach ($finder as $file) {
            $content = File::get($file->getRealPath());
            foreach ($this->patterns as $pattern) {
                $this->extractKeys($content, $pattern, $this->translationKeys);
            }
        }

        return $this;

    }

    /**
     * Merge extracted translation keys with existing translations in the output files.
     * Keeps existing translations and adds new ones with empty values.
     */
    public function mergeTranslations()
    {
        foreach ($this->locales as $locale) {
            $filePath = "{$this->output}/{$locale}.json";

            // Ensure language directory exists
            File::ensureDirectoryExists($this->output);

            // Load existing translations if file exists
            $existingTranslations = [];
            if (File::exists($filePath)) {
                $existingContent = File::get($filePath);
                $existingTranslations = json_decode($existingContent, true) ?? [];

                if (json_last_error() !== JSON_ERROR_NONE) {
                    continue; // Skip if Invalid json
                }
            }

            // New keys will be added with empty values
            $newTranslations = array_fill_keys(array_keys($this->translationKeys), '');
            // Keep existing translations if already translated
            $this->translationKeys = array_merge($newTranslations, $existingTranslations);

        }

        return $this;

    }

    /**
     * Save the translation keys into locale-based JSON files.
     */
    public function save()
    {
        foreach ($this->locales as $locale) {
            $filePath = "{$this->output}/{$locale}.json";

            file_put_contents(
                $filePath,
                json_encode($this->translationKeys, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            );

        }
    }

    /**
     * Get all extracted translation keys.
     */
    public function getLangKeys()
    {
        return $this->translationKeys;
    }
}
