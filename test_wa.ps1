$OutputEncoding = [Console]::OutputEncoding = [Text.Encoding]::UTF8

Write-Host "php -l app/Models/Setting.php"
php -l app/Models/Setting.php

Write-Host "php -l app/Controllers/PageController.php"
php -l app/Controllers/PageController.php

Write-Host "php -l app/Controllers/FaqController.php"
php -l app/Controllers/FaqController.php

Write-Host "php -l app/Controllers/GalleryController.php"
php -l app/Controllers/GalleryController.php

Write-Host "php -l app/Controllers/HomeController.php"
php -l app/Controllers/HomeController.php

Write-Host "php -l app/Views/admin/settings.php"
php -l app/Views/admin/settings.php

Write-Host "php -l app/Controllers/Admin/SettingsController.php"
php -l app/Controllers/Admin/SettingsController.php

Write-Host ""
Write-Host 'curl -s http://localhost:8000/ | grep "floating-wa"'
curl.exe -s http://localhost:8000/ | Select-String -Pattern "floating-wa"

Write-Host 'curl -s http://localhost:8000/faq | grep "floating-wa"'
$faqWa = curl.exe -s http://localhost:8000/faq | Select-String -Pattern "floating-wa"
if ($faqWa) { $faqWa.Line } else { Write-Host "(No 'floating-wa' found. But 'wa.me' yields:)" ; curl.exe -s http://localhost:8000/faq | Select-String -Pattern "wa.me" }

Write-Host 'curl -s http://localhost:8000/gallery | grep "floating-wa"'
$galWa = curl.exe -s http://localhost:8000/gallery | Select-String -Pattern "floating-wa"
if ($galWa) { $galWa.Line } else { Write-Host "(No 'floating-wa' found. But 'wa.me' yields:)" ; curl.exe -s http://localhost:8000/gallery | Select-String -Pattern "wa.me" }

Write-Host 'curl -s http://localhost:8000/privacy-policy | grep "floating-wa"'
curl.exe -s http://localhost:8000/privacy-policy | Select-String -Pattern "floating-wa"

Write-Host 'curl -s http://localhost:8000/terms-conditions | grep "floating-wa"'
curl.exe -s http://localhost:8000/terms-conditions | Select-String -Pattern "floating-wa"

Write-Host ""
Write-Host "curl -s http://localhost:8000/api/products | head -c 20"
$apiData = curl.exe -s http://localhost:8000/api/products
$apiData.Substring(0, [math]::Min(20, $apiData.Length))
