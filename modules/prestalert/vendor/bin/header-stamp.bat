@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../prestashop/header-stamp/bin/header-stamp
php "%BIN_TARGET%" %*
