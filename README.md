# TYPO3 Extension "rdct"

Adds functionality to trigger redirects based on the &RDCT GET parameter.

This is a very old and static "short-url" functionality previously part of TYPO3 Core
until TYPO3 v8. The functionality has been moved into its own extension, receiving its
own public repository.

## Installation
The latest version can be installed via TER (https://extensions.typo3.org) or via composer
by adding ''composer require friendsoftypo3/rdct'' in a TYPO3 v8+ installation.

## Inside the logic
All data is stored in a database table called `cache_md5params`, where the `hash` field
is a unique identifier which will be used in `index.php?RDCT=$hash`.

The previously `$TSFE->sendRedirect` functionality was moved into `FoT3\Rdct\Redirects->sendRedirect()`
and hooks into the Frontend requests automatically.

For creating links - adding the records into the database table, the method 
`FoT3\Rdct\Redirects->makeRedirectUrl()` should be called, which was previously located under
TYPO3 Cores' `GeneralUtility::makeRedirectUrl()`,


## Current state
The latest version here reflects a feature-complete state, and solely acts as a
compatibility layer for extensions and installations in need of this legacy feature.

**Note: For ALL other use cases a more flexible redirect solution as extension found in TER
should be used**

## Contribution
Feel free to submit any pull request, or add documentation, tests, as you please.
We will publish a new version every once in a while, depending on the amount of changes
and pull requests submitted.

### License
The extension is published under GPL v2+, all included third-party libraries are
published under their respective licenses.

### Authors
Initially developed by Kasper Skaarhoj, it was polished by many contributors in this
area while being part of the TYPO3 Core, and then extracted into its own extension by
Benni Mack.

This package is now maintained by a loose group of TYPO3 enthusiasts inside
the TYPO3 Community. Feel free to contact Benni Mack (benni.mack@typo3.org) for any
questions regarding "rdct".
