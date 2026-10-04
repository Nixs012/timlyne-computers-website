$OutputEncoding = [Console]::OutputEncoding = [Text.Encoding]::UTF8

Write-Host "--- Syntax checking changed files ---"
php -l app/Models/Setting.php
php -l app/Controllers/Admin/SeoController.php
php -l app/Views/admin/seo.php
php -l app/routes.php
php -l app/Controllers/HomeController.php
php -l app/Controllers/FaqController.php
php -l app/Controllers/GalleryController.php
php -l app/Views/home.php

Write-Host "`n--- Testing /admin/seo response (unauthenticated) ---"
curl.exe -s -o NUL -w "%{http_code}" http://localhost:8000/admin/seo
Write-Host ""

Write-Host "`n--- Testing SEO test script ---"
php public/test_seo.php set
Write-Host "Set home_meta_title to 'TEST NEW SEO TITLE'"
curl.exe -s http://localhost:8000/ | Select-String -Pattern "<title>" | Select-Object -First 1

php public/test_seo.php revert
Write-Host "Reverted home_meta_title to empty string"
curl.exe -s http://localhost:8000/ | Select-String -Pattern "<title>" | Select-Object -First 1

Write-Host "`n--- Testing API products endpoint ---"
$apiData = curl.exe -s http://localhost:8000/api/products
$apiData.Substring(0, [math]::Min(20, $apiData.Length))
