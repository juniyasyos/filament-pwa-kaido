# Changelog

All notable changes to `filament-pwa-kaido` will be documented in this file.

## v4.0.0 - 2025-10-25

### 🚀 Major Release - Filament v4 Support

This release brings full compatibility with Filament v4 and includes major refactoring for better code quality.

### Added

- ✨ Filament v4 support
- ✨ Full PHP 8.2+ type hints and return types
- ✨ Improved service worker generation with better error handling
- ✨ Enhanced `ManifestService` with separated methods for better maintainability
- ✨ New `UPGRADE.md` guide for migrating from v3
- ✨ Better documentation and code comments
- ✨ Improved config file with better documentation

### Changed

- 🔄 **Breaking**: Updated minimum PHP version to 8.2
- 🔄 **Breaking**: Updated minimum Laravel version to 11.x
- 🔄 **Breaking**: Updated Filament requirement to v4.x
- 🔄 Refactored `PWASettingsPage` to use new Filament v4 `form()` method
- 🔄 Replaced `hint()` with `helperText()` in form components
- 🔄 Updated `FilamentPWAPlugin` to use instance properties instead of static
- 🔄 Modernized `FilamentPwaInstall` command with better output and error handling
- 🔄 Improved `PWAController` with strict return types
- 🔄 Enhanced routes file with better formatting and modern syntax
- 🔄 Updated Service Provider with better organization

### Improved

- 💡 Better code organization following SOLID principles
- 💡 Enhanced error handling throughout the package
- 💡 Improved service worker generation logic
- 💡 Better manifest generation with proper fallbacks
- 💡 More reliable icon and splash screen handling
- 💡 Cleaner and more maintainable codebase

### Fixed

- 🐛 Fixed service worker icon generation
- 🐛 Improved image type detection in `ManifestService`
- 🐛 Better handling of missing settings

### Documentation

- 📚 Completely rewritten README with better examples
- 📚 Added comprehensive upgrade guide
- 📚 Improved inline code documentation
- 📚 Better config file documentation

## v3.x

Previous versions were compatible with Filament v3.
