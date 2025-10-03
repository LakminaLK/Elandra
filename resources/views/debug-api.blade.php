<!DOCTYPE html>
<html>
<head>
    <title>Debug API</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>API Debug Test</h1>
    <button onclick="testAuth()">Test Authentication</button>
    <button onclick="testCustomersAPI()">Test Customers API</button>
    <div id="output"></div>

    <script>
        function getCSRFToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        }
        
        async function testAuth() {
            try {
                const response = await fetch('/admin/debug-auth');
                const data = await response.json();
                document.getElementById('output').innerHTML = '<h3>Auth Test:</h3><pre>' + JSON.stringify(data, null, 2) + '</pre>';
            } catch (error) {
                document.getElementById('output').innerHTML = '<h3>Auth Error:</h3>' + error.message;
            }
        }
        
        async function testCustomersAPI() {
            try {
                const response = await fetch('/admin/customers', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCSRFToken(),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Status: ' + response.status);
                }
                
                const data = await response.json();
                document.getElementById('output').innerHTML = '<h3>Customers API:</h3><pre>' + JSON.stringify(data, null, 2) + '</pre>';
            } catch (error) {
                document.getElementById('output').innerHTML = '<h3>Customers Error:</h3>' + error.message;
            }
        }
    </script>
</body>
</html>