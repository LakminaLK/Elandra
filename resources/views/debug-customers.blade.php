<!DOCTYPE html>
<html>
<head>
    <title>Debug Customers API</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Debug Customers API</h1>
    <button onclick="debugAPI()">Test Customers Endpoint</button>
    <div id="debug-result"></div>

    <script>
    async function debugAPI() {
        const resultDiv = document.getElementById('debug-result');
        resultDiv.innerHTML = 'Testing...';
        
        try {
            console.log('Making request to /admin/customers');
            
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

            console.log('Response status:', response.status);
            console.log('Response headers:', [...response.headers.entries()]);

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const data = await response.json();
            console.log('Response data:', data);
            
            resultDiv.innerHTML = `
                <h3>✅ Success!</h3>
                <p><strong>Status:</strong> ${response.status}</p>
                <p><strong>Customers found:</strong> ${data.total || data.length || 'Unknown'}</p>
                <pre>${JSON.stringify(data, null, 2)}</pre>
            `;
        } catch (error) {
            console.error('API Error:', error);
            resultDiv.innerHTML = `
                <h3>❌ Error</h3>
                <p><strong>Error:</strong> ${error.message}</p>
                <p>Check the browser console for more details.</p>
            `;
        }
    }
    </script>
</body>
</html>