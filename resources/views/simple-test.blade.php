<!DOCTYPE html>
<html>
<head>
    <title>Simple Customer Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .result { margin: 10px 0; padding: 10px; border: 1px solid #ccc; }
        .success { border-color: green; background: #f0fff0; }
        .error { border-color: red; background: #fff0f0; }
    </style>
</head>
<body>
    <h1>Simple Customer API Test</h1>
    <button onclick="testAPI()">Test Customers API</button>
    <div id="result"></div>

    <script>
    async function testAPI() {
        const result = document.getElementById('result');
        result.innerHTML = 'Testing...';
        
        try {
            const response = await fetch('/admin/customers', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const data = await response.json();
            
            result.innerHTML = `
                <div class="result success">
                    <h3>✅ API Working!</h3>
                    <p><strong>Status:</strong> ${response.status}</p>
                    <p><strong>Total Customers:</strong> ${data.total || data.length || 'Unknown'}</p>
                    <p><strong>Customers Array Length:</strong> ${data.data ? data.data.length : 'No data array'}</p>
                    <h4>Data Structure:</h4>
                    <pre>${JSON.stringify(data, null, 2)}</pre>
                </div>
            `;
        } catch (error) {
            result.innerHTML = `
                <div class="result error">
                    <h3>❌ API Error</h3>
                    <p><strong>Error:</strong> ${error.message}</p>
                </div>
            `;
        }
    }
    </script>
</body>
</html>