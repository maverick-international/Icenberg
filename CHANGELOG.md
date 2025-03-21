# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),


## [v0.1.0] 2022-06-01
- Initialises project

## [v0.1.2] 2022-06-02

### Added
 - The ability to pass arbitrary settings to Settings class.

## [v0.1.3] 2022-06-11

### Added
 - Integrated CLI

## [v0.1.4] 2022-07-06

### Added
 - url field support

## [v0.1.5] 2022-07-28

### Fixed
 - Allowed videos to autoplay on iOS devices

## [v0.1.6] 2022-08-28

### Fixed
 - Allows for deleted block settings

## [v0.1.7] 2022-09-07

### Added
 - Email field
 - Password Field
 - True/False field
 - Checkbox field

### Fixed
 - Wrapped url field in link
 - Handled non-video uploads with link - maybe should create a special file method for other applications?

## [v0.1.8] 2022-09-27

### Fixed
 - Applies inner background colour setting

## [v0.1.9] 2022-09-27

### Fixed
 - Applies video modal class correctly

## [v0.1.10] 2022-09-27

### Added
- Ability to use icomoon for button icons

### Fixed
- Class names for button icon background colours

## [v0.5.0] 2025-03-17
Paddy's day mega-release

### Added
- Gutenberg block support:
- Support for single fields, outside of the_row()
- A functioning CLI to create icenberg ready ACF gutenberg blocks
- icenberg.yaml config support

## [v0.5.1] 2025-03-21

### Added
- Allows pruning of unwanted fields from groups and repeaters
- Adds wrapping class to synchronise ACF block presentation on front and backends.

### fixed
- fixed some errors in the stubs

## [v0.5.2] 2025-03-21

### fixed
- fixed bug where custom tags were not passed to groups

## [v0.5.3] 2025-03-21

### Added
- new 'only()' method to extrat an indibidual field or fields from a group or set of repeater rows.
