
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
- new 'only()' method to extract an individual field or fields from a group or set of repeater rows.

## [v0.6.0] 2025-03-21

**Potentially Breaking changes**: Relationship and Post Object fields now return a value, so if they exist in groups that are dispayed using previous versions of icenberg they will now display, which may not be desired. 

### Added
- Ability to use global options fields
- Relationship field support
- Post object field support
- Testing framework (Brain Monkey/Pest) and scaffolding for future implementation
- Google Maps field
- Value method to retrieve unprocessed field/sub field values
  
### Fixed
- Inconsistent variable naming in field classes
- Dynamic property assignment in Form class 

## [v0.6.2] 2025-03-22

### Fixed
- Major regressions due to error in handling option fields

## [v0.6.3] 2025-03-22

### Added
- CLI can now create flexible content blocks when the --flexible flag is used

## [v0.6.4] 2025-03-23

### Fixed
- Json stub now valid
- hyphens and underscores in acf gutenberg blocks don't work for some reason, handled this in cli

## [v0.7.0] 2025-04-08
Major changes to allow Icenberg to work well outside of the loop and the confines of 'block'

### Added 
- Ability to pass an alternative BEM block
- Ability to specify a post_id for fields outside of the loop

### Removed
- removed experimental Preview formatting as was too specific to be useful and was an annoyance on relationship fields and post objects where you're more likely to just want a formatted link.

## [v0.7.1] 2025-04-08

### Fixed
- implementation bugs in out of loop fields

## [v0.8.0] 2025-04-10

### Added
- Modifiers to complete the BEM

## [v0.8.2] 2025-04-11

### Fixed
- Bug where the field name was being wiped in option fields

## [v0.8.3] 2025-04-11

### Fixed
- Whitespace at tail of css classes

## [v0.8.4] 2025-04-23

### Fixed
- Ability to pass custom attributes to `enclose` and `get_enclose` re-added

## [v0.8.5] 2025-04-30

### Fixed
- Correct path to autoloader in Bootstrap method, looks in root and theme root 

## [v0.8.6] 2025-05-01

### Fixed
- php blocks not generating correctly from stub

## [v0.8.7] 2025-05-01

### Added
- ability to pass background content to an ACF gutenberg block
