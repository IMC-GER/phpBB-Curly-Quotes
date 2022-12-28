# phpBB-Curly-Quotes

## Description

Replaces quotation marks with curly quotes.

## Screenshots
- [Post view difference](https://raw.githubusercontent.com/IMC-GER/images/main/screenshots/curlyquotes/de/post.jpg?raw=true)
- [ACP](https://raw.githubusercontent.com/IMC-GER/images/main/screenshots/curlyquotes/de/acp_settings.jpg?raw=true)

## Requirements
- php 8.0 or higher
- phpBB 3.3.3 or higher

## Installation

Copy the extension to `phpBB3/ext/imcger/curlyquotes`

Go to "ACP" > "Customise" > "Extensions Manager" and enable the "phpBB Curly Quotes" extension.
Go to "ACP" > "Extension" > "Curly Quotes Settings" and set the quote style for your languages.

## Settings
- Quotation marks styles
- Replace remaining quotes with Prime characters

## Changelog

### v1.1.1 (28-12-2022)
- Fixed copy paste mistake in ext.php

### v1.1.0 (26-12-2022)
- Added suport for coordinates and length specifications ft./in.
- Added replace quotes in preview
- Changed PHP max to v8.2
- Update check system requirement

### v1.0.3 (02-10-2022)
- Fixed don't show attachment in post

### v1.0.2 (25-09-2022)
- Fixed Error 500 wenn user only set the beginning quotation mark

### v1.0.1 (25-09-2022)
- Fixed no apostrophe if starting string has only one character

### v1.0.0 (24-09-2022)
- Fixed replace quotation marks in BBCode "code"

### v1.0.0-b4 (19-09-2022)
- Code changes
- Versions check

### v1.0.0-b3 (19-09-2022)
- Fixed language setting

### v1.0.0-b2 (16-09-2022)
- Code changes

### v1.0.0-b1 (15-09-2022)
- ACP Panel added
- Code changes

### v0.2.0-dev (12-09-2022)
- Replace remaining quotes with prime symbole

### v0.1.0-dev (10-09-2022)

## Update
- Navigate in the ACP to `Customise -> Manage extensions`.
- Click the `Disable` link for Curly Quotes.
- Delete the `curlyquotes` folder from `phpBB3/ext/imcger/`.
- Install the new Curly Quotes extension.

## Uninstallation
- Navigate in the ACP to `Customise -> Manage extensions`.
- Click the `Disable` link for Curly Quotes.
- To permanently uninstall, click `Delete Data`, then delete the `curlyquotes` folder from `phpBB3/ext/imcger/`.

## License
**phpBB-Curly-Quotes**
[GPLv2](https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)
