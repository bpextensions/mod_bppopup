# BP Popup
A Joomla! 3 module that creates image, iframe or HTML popup window.

## Requirements
- PHP 7.2
- Joomla 3.8.x

## Building extension from repo
### Build requirements
- PHP 7.2
- Composer
- Node/Npm

We assume you have `npm` and `composer` available globally.

### Building preparing
How to prepare your installation for development.
- Install composer requirements: `composer install`
- Install node requirements: `npm install`
- Run `npm run build` to build your js/css assets. 
- Run `npm run watch` to build your js/css assets after each change. 

### Installation package build procedure
- Build installation package: `composer build`
- Your installation zip package should now be ready in `.build/`

## Changelog

### v1.2.4
- Fixed module behavior when Joomla! is installed in a subdirectory.
- Added donation message in administration

### v1.2.1
- Fixing iframe popup issue.

### v1.2.0
- Moving PHP requirement to 7.2+.
- Fixing documentation.
- Added scroll activation.
- Added activation on reaching page end.
- Added WYSIWYG editor text as a popup-content.
- Added ability to popup on every page display.

### v1.1.2
- Added update server.

### v1.1.1
- Added URL for image type popups.

### v1.1.0
- Added HTML support.
- Fixed Node security notification.

### v1.0.1
- Added menu item parameter.
- Moved assets building to [WebpackEncore](https://github.com/symfony/webpack-encore).
