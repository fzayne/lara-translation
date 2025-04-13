# LaraTranslation

**LaraTranslation** is a Laravel package that extracts translation keys from your PHP and Blade files and exports them to JSON files for multiple locales.

This is especially useful for applications using Laravel's JSON translation format, helping you keep track of all translation keys in use throughout your views and code.

---

## 🚀 Features

- Scan specific directories for translation function calls
- Supports `trans()`, `__()`, and `@lang` patterns
- Exports translation keys to JSON files per locale
- Merges with existing translations without overwriting existing values
- Easy Artisan command to trigger extraction and export

---

## 📦 Installation

### Step 1: Install via Composer

If using directly from GitHub:

```bash
composer require fzayne/lara-translation
```

### Step 2: Publish Config

```bash
php artisan vendor:publish --tag=lara-translation-config
```

---

## ⚙️ Configuration

The package provides a simple way to customize the directories, patterns, and output file where translation keys are extracted and stored.

### 1. **Directories**

You can define the directories in which the package will search for translation keys. By default, the package looks for translation keys in the `app/View` and `resource/views` directories. You can add or modify these directories in the configuration file to include other locations as needed.
```php
    'directories' => [
        app_path('View'),
        resource_path('views'),
    ],

```
### 2. **Patterns**

The package supports searching for specific translation functions or patterns within your files. The default patterns include common Laravel translation functions like `trans()`, `__()`, and `@lang`. You can customize this list to match any other translation function or pattern that you use in your application.
```php
      'patterns' => [
        'trans',  
        '__',     
        '@lang',  
    ],


```
### 3. **Output Path**

Once the translation keys are extracted, they are saved to a file. By default, these keys are stored in the language files within the `lang/` directory. You can configure a different output path where the generated files will be saved. This makes it easy to organize your translation keys wherever you need them.

---

This configuration allows you to easily control where the package searches for translation keys, what functions or patterns it looks for, and where the output files will be saved.

---

## 🛠 Usage

Run the Artisan command to export translation keys:

```bash
php artisan transkeys:export {locales?*}
```

**Exemple:**

```bash
php artisan transkeys:export en fr ar
```

This will create or update `en.json`, `fr.json`, and `ar.json` in your `lang/` folder with all translation keys found.

---

# License

This package is open-source and available under the [MIT License](LICENSE).

You are free to use, modify, and distribute it, as long as you include the original copyright and license notice in your copies of the software.

---

# Credits

This package was inspired by the work of [KaziSTM](https://github.com/KaziSTM), who provided valuable insights and ideas for extracting translation keys more efficiently. Special thanks to the open-source community for their contributions.

If you use or find this package useful, feel free to credit the original ideas and contributions.

---
