<?php
// Test password hashing
$password = '123456';
$hashed = password_hash($password, PASSWORD_DEFAULT);

echo "<h2>Password Hashing Test</h2>";
echo "Original Password: $password<br>";
echo "Hashed Password: $hashed<br>";
echo "Length: " . strlen($hashed) . " characters<br>";

// Test verification
$verify = password_verify($password, $hashed);
echo "Verification: " . ($verify ? "✅ SUCCESS" : "❌ FAILED") . "<br>";

// Test with wrong password
$wrong_verify = password_verify('wrongpassword', $hashed);
echo "Wrong Password Test: " . ($wrong_verify ? "❌ FAILED" : "✅ SUCCESS") . "<br>";
?>