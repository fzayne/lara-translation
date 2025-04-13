<?php

namespace Fzayne\LaraTranslation\Console;

use Fzayne\LaraTranslation\LangKeyExtractor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExportTranslationKeys extends Command
{
    protected $signature = 'transkeys:export {locales?*}';

    protected $description = 'Export all unique translation keys from files to JSON files for specified locales in the lang directory.';

    public function handle()
    {
        // Retrieve locales from the command arguments
        $locales = $this->argument('locales');

        // If no locales were provided, prompt the user to input them interactively
        if (empty($locales)) {
            $input = $this->ask('Please specify the locales (e.g., ar fr en)');
            $locales = explode(' ', $input);
        }

        // Get the list of directories to scan from config
        $directories = config('lara-translation.directories');

        // Validate each configured directory
        foreach ($directories as $dir) {
            if (! File::isDirectory($dir)) {
                $this->error("Directory not found: {$dir}");

                return Command::FAILURE; // Exit with failure if a directory is invalid
            }
        }

        // Initialize and configure the LangKeyExtractor
        $keyExtractor = new LangKeyExtractor;
        // Chain the methods to extract, merge, and save translation keys
        $keyExtractor->setDirectories($directories)
            ->setLocales($locales)
            ->setOutputPath(config('lara-translation.output'))
            ->generatePatterns(config('lara-translation.patterns'))
            ->extract()
            ->mergeTranslations()
            ->save();

    }
}
