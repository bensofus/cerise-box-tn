@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../prestashop/autoindex/bin/autoindex
php "%BIN_TARGET%" %*
