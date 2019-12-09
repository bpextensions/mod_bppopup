# BP Popup
A Joomla! 3 module that creates image, iframe or HTML popup window.

## Requirements
- PHP 7
- Joomla 3.8.x

## Building extension from repo
### Build reqirements
- PHP 7
- Composer
- Node/Npm

We assume you have `npm` and `composer` available globally.

### Building procedure
- Install composer requirements: `composer install`
- Install node requirements: `npm install`
- Build installation package: `phing build`

Your installation package should now be ready in `.build/`

## Changelog

### v1.1.0
- Added HTML support
- Fixed Node security notification

### v1.0.1
- Added menu item parameter
- Moved assets building to [WebpackEncore](https://github.com/symfony/webpack-encore)
