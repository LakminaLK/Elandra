import axios from 'axios';

// Create axios instance with base configuration
const apiClient = axios.create({
    baseURL: '/api',
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    }
});

// Request interceptor to add auth token
apiClient.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('auth_token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Response interceptor for error handling
apiClient.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response?.status === 401) {
            // Handle unauthorized - redirect to login
            localStorage.removeItem('auth_token');
            window.location.href = '/login';
        } else if (error.response?.status === 403) {
            // Handle forbidden
            console.error('Access forbidden');
        } else if (error.response?.status >= 500) {
            // Handle server errors
            console.error('Server error:', error.response.data);
        }
        return Promise.reject(error);
    }
);

// API service methods
export const authAPI = {
    // Authentication
    register: (userData) => apiClient.post('/auth/register', userData),
    login: (credentials) => apiClient.post('/auth/login', credentials),
    logout: () => apiClient.post('/auth/logout'),
    getUser: () => apiClient.get('/auth/user'),
    refreshToken: () => apiClient.post('/auth/refresh'),
    
    // Token management
    getTokens: () => apiClient.get('/auth/tokens'),
    revokeToken: (tokenId) => apiClient.delete(`/auth/tokens/${tokenId}`),
    revokeAllTokens: () => apiClient.delete('/auth/tokens'),
};

export const productsAPI = {
    // Public endpoints
    getAll: (params = {}) => apiClient.get('/products', { params }),
    getFeatured: () => apiClient.get('/products/featured'),
    getCategories: () => apiClient.get('/products/categories'),
    search: (query) => apiClient.get('/products/search', { params: { q: query } }),
    getById: (id) => apiClient.get(`/products/${id}`),
    
    // Admin endpoints
    create: (productData) => apiClient.post('/admin/products', productData),
    update: (id, productData) => apiClient.put(`/admin/products/${id}`, productData),
    delete: (id) => apiClient.delete(`/admin/products/${id}`),
    uploadImage: (id, imageData) => apiClient.post(`/admin/products/${id}/images`, imageData, {
        headers: { 'Content-Type': 'multipart/form-data' }
    }),
};

export const cartAPI = {
    get: () => apiClient.get('/cart'),
    add: (productId, quantity = 1) => apiClient.post('/cart/add', { product_id: productId, quantity }),
    update: (itemId, quantity) => apiClient.put(`/cart/items/${itemId}`, { quantity }),
    remove: (itemId) => apiClient.delete(`/cart/items/${itemId}`),
    clear: () => apiClient.delete('/cart/clear'),
    getCount: () => apiClient.get('/cart/count'),
};

export const ordersAPI = {
    // Customer endpoints
    getAll: (params = {}) => apiClient.get('/orders', { params }),
    getById: (id) => apiClient.get(`/orders/${id}`),
    create: (orderData) => apiClient.post('/orders', orderData),
    
    // Admin endpoints
    adminGetAll: (params = {}) => apiClient.get('/admin/orders', { params }),
    updateStatus: (id, status) => apiClient.patch(`/admin/orders/${id}/status`, { status }),
};

export default apiClient;