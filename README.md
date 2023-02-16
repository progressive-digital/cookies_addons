# cookies_addons
Additional functionality for the Cookies Module, to cover more use cases.

## How to include it in your Drupal project
Add the repository to your composer.json like this:
```json
"repositories": [
  {
      "type": "composer",
      "url": "https://packages.drupal.org/8"
  },
  {
    "type": "git",
    "url": "https://github.com/progressive-digital/cookies_addons.git"
  }
]
```

And then require this module:
```bash
composer require 'progressive-digital/cookies_addons:^1.0'
```
