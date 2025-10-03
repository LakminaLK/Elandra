<!DOCTYPE html>
<html>
<head>
    <title>Debug API Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
    <h1>Customer API Debug Test</h1>
    <button onclick="testAPI()">Test Customers API</button>
    <button onclick="testAuth()">Test Authentication</button>
    <div id="results" style="margin-top: 20px; padding: 20px; background: #f5f5f5; font-family: monospace; white-space: pre-wrap;"></div>

    <script>
        // Configure axios
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.headers.common['Accept'] = 'application/json';
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        async function testAPI() {
            const resultsDiv = document.getElementById('results');
            resultsDiv.innerHTML = 'Testing customers API...\n';
            
            try {
                console.log('Making axios request to /admin/customers');
                const response = await axios.get('/admin/customers');
                
                resultsDiv.innerHTML += 'SUCCESS!\n';
                resultsDiv.innerHTML += 'Status: ' + response.status + '\n';
                resultsDiv.innerHTML += 'Data: ' + JSON.stringify(response.data, null, 2) + '\n';
                
            } catch (error) {
                resultsDiv.innerHTML += 'ERROR!\n';
                if (error.response) {
                    resultsDiv.innerHTML += 'Status: ' + error.response.status + '\n';
                    resultsDiv.innerHTML += 'Response: ' + JSON.stringify(error.response.data, null, 2) + '\n';
                } else {
                    resultsDiv.innerHTML += 'Error: ' + error.message + '\n';
                }
            }
        }
        
        async function testAuth() {
            const resultsDiv = document.getElementById('results');
            resultsDiv.innerHTML = 'Testing authentication...\n';
            
            try {
                const response = await axios.get('/admin/debug-auth');
                resultsDiv.innerHTML += 'Auth Status: ' + JSON.stringify(response.data, null, 2) + '\n';
            } catch (error) {
                resultsDiv.innerHTML += 'Auth Error: ' + (error.response ? error.response.status : error.message) + '\n';
            }
        }
    </script>
</body>
</html>