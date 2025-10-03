<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Customer API Test</title>
    @vite(['resources/js/app.js'])
</head>
<body>
    <div style="padding: 20px; font-family: Arial, sans-serif;">
        <h1>Customer API Test Page</h1>
        <button id="testApi" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">Test API</button>
        <button id="loadCustomers" style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">Load Customers</button>
        
        <div id="results" style="margin-top: 20px; padding: 15px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px;">
            <strong>Results will appear here...</strong>
        </div>
        
        <div id="console" style="margin-top: 20px; padding: 15px; background: #000; color: #00ff00; border-radius: 5px; font-family: monospace; height: 300px; overflow-y: auto;">
            <strong>Console Output:</strong><br>
        </div>
    </div>

    <script>
        const resultsDiv = document.getElementById('results');
        const consoleDiv = document.getElementById('console');
        
        function log(message) {
            console.log(message);
            consoleDiv.innerHTML += message + '<br>';
            consoleDiv.scrollTop = consoleDiv.scrollHeight;
        }
        
        function displayResults(data) {
            resultsDiv.innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
        }
        
        // Wait for Axios to be available
        function waitForAxios() {
            return new Promise((resolve) => {
                if (window.axios) {
                    resolve();
                } else {
                    const checkInterval = setInterval(() => {
                        if (window.axios) {
                            clearInterval(checkInterval);
                            resolve();
                        }
                    }, 100);
                }
            });
        }
        
        document.addEventListener('DOMContentLoaded', async function() {
            log('🎯 PAGE LOADED - Waiting for Axios...');
            
            await waitForAxios();
            log('✅ AXIOS AVAILABLE');
            
            // Configure Axios
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            window.axios.defaults.headers.common['Accept'] = 'application/json';
            window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            
            log('🚀 AXIOS CONFIGURED');
            
            // Test API button
            document.getElementById('testApi').addEventListener('click', async () => {
                try {
                    log('🧪 Testing API...');
                    const response = await window.axios.get('/api/public/customers/test/api');
                    log('✅ Test API Success: ' + JSON.stringify(response.data));
                    displayResults(response.data);
                } catch (error) {
                    log('❌ Test API Error: ' + error.message);
                    displayResults({ error: error.message });
                }
            });
            
            // Load customers button
            document.getElementById('loadCustomers').addEventListener('click', async () => {
                try {
                    log('📊 Loading customers...');
                    const response = await window.axios.get('/api/public/customers');
                    log('✅ Customers loaded successfully');
                    log('Data: ' + JSON.stringify(response.data));
                    displayResults(response.data);
                } catch (error) {
                    log('❌ Load customers error: ' + error.message);
                    displayResults({ error: error.message });
                }
            });
            
            log('🎉 Event listeners set up successfully');
        });
    </script>
</body>
</html>