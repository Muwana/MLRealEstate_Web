<?php
echo "<h1>Debug Test - ML RealEstate</h1>";

// Test 1: Check if files exist
echo "<h2>1. File Check:</h2>";
$files = ['login.php', 'signup.php', 'dashboard/user_dashboard.php'];
foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✅ $file exists<br>";
    } else {
        echo "❌ $file missing<br>";
    }
}

// Test 2: Check PHP errors
echo "<h2>2. PHP Configuration:</h2>";
echo "Error Reporting: " . ini_get('error_reporting') . "<br>";
echo "Display Errors: " . ini_get('display_errors') . "<br>";

// Test 3: Simple API test
echo "<h2>3. API Test Form:</h2>";
?>
<h3>Test Login (Use NEW Email)</h3>
<input type="email" name="email" placeholder="Email" value="newuser@example.com" required>
<input type="password" name="password" placeholder="Password" value="123456" required>
<button type="submit">Test Login API</button>
<div id="apiResult" style="margin-top: 20px; padding: 10px; border: 1px solid #ccc;"></div>

<script>
document.getElementById('apiTestForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const resultDiv = document.getElementById('apiResult');
    
    resultDiv.innerHTML = 'Sending request...';
    
    try {
        const response = await fetch('login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                email: formData.get('email'),
                password: formData.get('password')
            })
        });
        
        // Get raw response text first
        const rawResponse = await response.text();
        resultDiv.innerHTML = `<strong>Raw Response:</strong><br><pre>${rawResponse}</pre>`;
        
        // Try to parse as JSON
        try {
            const data = JSON.parse(rawResponse);
            resultDiv.innerHTML += `<br><strong>Parsed JSON:</strong><br><pre>${JSON.stringify(data, null, 2)}</pre>`;
        } catch (jsonError) {
            resultDiv.innerHTML += `<br><strong>JSON Parse Error:</strong> ${jsonError}`;
        }
        
    } catch (error) {
        resultDiv.innerHTML = `<strong>Fetch Error:</strong> ${error}`;
    }
});
</script>

<?php
// Test 4: Check session
echo "<h2>4. Session Test:</h2>";
session_start();
echo "Session ID: " . session_id() . "<br>";
echo "Session User: " . ($_SESSION['user']['email'] ?? 'Not set') . "<br>";
?>