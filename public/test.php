<?php

echo "<!DOCTYPE html><html lang='id'><head><meta charset='UTF-8'><title>Tes Server Nanya Events</title>";
echo "<style>body{font-family:sans-serif;padding:40px;background:#f8fafc;color:#1e293b}h1{color:#f37032}.card{background:#fff;padding:30px;border-radius:16px;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);max-width:500px;margin:auto}</style></head><body>";
echo "<div class='card'>";
echo "<h1>✅ PHP Aktif & Berjalan Normal!</h1>";
echo "<p><strong>Domain:</strong> " . htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'events.nanyang.sch.id') . "</p>";
echo "<p><strong>Versi PHP Server:</strong> " . phpversion() . "</p>";
echo "<p><strong>Waktu Server:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "<hr style='border:none;border-top:1px solid #e2e8f0;margin:20px 0;'>";
echo "<p style='color:#10b981;font-weight:bold;'>Web server Apache & PHP di server hosting Nanyang berfungsi dengan sempurna!</p>";
echo "</div></body></html>";
