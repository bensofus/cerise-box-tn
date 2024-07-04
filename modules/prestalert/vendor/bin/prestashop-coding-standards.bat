@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../prestashop/php-dev-tools/bin/prestashop-coding-standards
php "%BIN_TARGET%" %*
