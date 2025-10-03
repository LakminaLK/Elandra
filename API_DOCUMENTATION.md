# MongoDB Products REST API Documentation

## Overview
This API provides complete CRUD operations for products stored in MongoDB. All endpoints return JSON responses and follow RESTful conventions.

## Base URL
```
http://localhost/api
```

## Authentication
Protected admin routes require authentication via Sanctum token:
```
Authorization: Bearer {your-token}
```

## Product Endpoints

### 1. List All Products (Public)
**GET** `/api/products`

**Query Parameters:**
- `category` - Filter by category name
- `brand` - Filter by brand name
- `min_price` - Minimum price filter
- `max_price` - Maximum price filter
- `in_stock` - Filter by stock availability (true/false)
- `search` - Search in name and description
- `sort_by` - Sort field (name, price, created_at)
- `sort_order` - Sort direction (asc, desc)
- `page` - Page number for pagination
- `per_page` - Items per page (default: 15, max: 100)

**Example Request:**
```
GET /api/products?category=Electronics&min_price=100&max_price=500&sort_by=price&sort_order=asc&page=1&per_page=20
```

**Example Response:**
```json
{
    "status": "success",
    "data": {
        "products": [
            {
                "_id": "507f1f77bcf86cd799439011",
                "name": "Samsung Galaxy S23",
                "description": "Latest Samsung smartphone",
                "price": 299.99,
                "category": "Electronics",
                "brand": "Samsung",
                "stock_quantity": 50,
                "images": [
                    "storage/products/galaxy-s23-1.jpg",
                    "storage/products/galaxy-s23-2.jpg"
                ],
                "specifications": {
                    "storage": "128GB",
                    "color": "Black"
                },
                "created_at": "2024-01-15T10:30:00Z",
                "updated_at": "2024-01-15T10:30:00Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "per_page": 20,
            "total": 150,
            "last_page": 8,
            "from": 1,
            "to": 20
        }
    }
}
```

### 2. Get Single Product (Public)
**GET** `/api/products/{id}`

**Example Request:**
```
GET /api/products/507f1f77bcf86cd799439011
```

**Example Response:**
```json
{
    "status": "success",
    "data": {
        "_id": "507f1f77bcf86cd799439011",
        "name": "Samsung Galaxy S23",
        "description": "Latest Samsung smartphone with advanced camera",
        "price": 299.99,
        "category": "Electronics",
        "brand": "Samsung",
        "stock_quantity": 50,
        "images": [
            "storage/products/galaxy-s23-1.jpg",
            "storage/products/galaxy-s23-2.jpg"
        ],
        "specifications": {
            "storage": "128GB",
            "color": "Black",
            "screen_size": "6.1 inches"
        },
        "created_at": "2024-01-15T10:30:00Z",
        "updated_at": "2024-01-15T10:30:00Z"
    }
}
```

### 3. Get Product Categories (Public)
**GET** `/api/products/categories`

**Example Response:**
```json
{
    "status": "success",
    "data": [
        {
            "category": "Electronics",
            "count": 45
        },
        {
            "category": "Clothing",
            "count": 32
        },
        {
            "category": "Home & Garden",
            "count": 28
        }
    ]
}
```

### 4. Get Product Brands (Public)
**GET** `/api/products/brands`

**Example Response:**
```json
{
    "status": "success",
    "data": [
        {
            "brand": "Samsung",
            "count": 15
        },
        {
            "brand": "Apple",
            "count": 12
        },
        {
            "brand": "Nike",
            "count": 8
        }
    ]
}
```

### 5. Search Products (Public)
**GET** `/api/products/search`

**Query Parameters:**
- `q` - Search query (required)
- `category` - Filter by category
- `brand` - Filter by brand
- `min_price` - Minimum price
- `max_price` - Maximum price

**Example Request:**
```
GET /api/products/search?q=smartphone&category=Electronics&min_price=200
```

**Example Response:**
```json
{
    "status": "success",
    "data": {
        "products": [
            {
                "_id": "507f1f77bcf86cd799439011",
                "name": "Samsung Galaxy S23",
                "description": "Latest Samsung smartphone",
                "price": 299.99,
                "category": "Electronics",
                "brand": "Samsung",
                "stock_quantity": 50,
                "relevance_score": 0.95
            }
        ],
        "search_meta": {
            "query": "smartphone",
            "total_results": 15,
            "search_time": "0.123s"
        }
    }
}
```

### 6. Get Product Statistics (Public)
**GET** `/api/products/stats`

**Example Response:**
```json
{
    "status": "success",
    "data": {
        "total_products": 150,
        "total_in_stock": 120,
        "total_out_of_stock": 30,
        "categories_count": 8,
        "brands_count": 25,
        "average_price": 156.78,
        "price_range": {
            "min": 9.99,
            "max": 999.99
        }
    }
}
```

## Admin Endpoints (Protected)

### 7. Create Product (Admin Only)
**POST** `/api/admin/products`

**Headers:**
```
Authorization: Bearer {your-token}
Content-Type: multipart/form-data
```

**Request Body:**
```json
{
    "name": "iPhone 15 Pro",
    "description": "Latest Apple smartphone with titanium design",
    "price": 999.99,
    "category": "Electronics",
    "brand": "Apple",
    "stock_quantity": 25,
    "specifications": {
        "storage": "256GB",
        "color": "Natural Titanium",
        "screen_size": "6.1 inches"
    }
}
```

**Files (optional):**
- `images[]` - Product image files

**Example Response:**
```json
{
    "status": "success",
    "message": "Product created successfully",
    "data": {
        "_id": "507f1f77bcf86cd799439012",
        "name": "iPhone 15 Pro",
        "description": "Latest Apple smartphone with titanium design",
        "price": 999.99,
        "category": "Electronics",
        "brand": "Apple",
        "stock_quantity": 25,
        "images": [
            "storage/products/iphone-15-pro-1.jpg"
        ],
        "specifications": {
            "storage": "256GB",
            "color": "Natural Titanium",
            "screen_size": "6.1 inches"
        },
        "created_at": "2024-01-15T11:00:00Z",
        "updated_at": "2024-01-15T11:00:00Z"
    }
}
```

### 8. Update Product (Admin Only)
**PUT** `/api/admin/products/{id}`

**Headers:**
```
Authorization: Bearer {your-token}
Content-Type: application/json
```

**Request Body:**
```json
{
    "name": "iPhone 15 Pro Max",
    "price": 1199.99,
    "stock_quantity": 30
}
```

**Example Response:**
```json
{
    "status": "success",
    "message": "Product updated successfully",
    "data": {
        "_id": "507f1f77bcf86cd799439012",
        "name": "iPhone 15 Pro Max",
        "description": "Latest Apple smartphone with titanium design",
        "price": 1199.99,
        "category": "Electronics",
        "brand": "Apple",
        "stock_quantity": 30,
        "updated_at": "2024-01-15T11:30:00Z"
    }
}
```

### 9. Delete Product (Admin Only)
**DELETE** `/api/admin/products/{id}`

**Headers:**
```
Authorization: Bearer {your-token}
```

**Example Response:**
```json
{
    "status": "success",
    "message": "Product deleted successfully"
}
```

## Error Responses

### Validation Error (422)
```json
{
    "status": "error",
    "message": "The given data was invalid.",
    "errors": {
        "name": ["The name field is required."],
        "price": ["The price must be a number."]
    }
}
```

### Not Found (404)
```json
{
    "status": "error",
    "message": "Product not found"
}
```

### Unauthorized (401)
```json
{
    "status": "error",
    "message": "Unauthenticated."
}
```

### Forbidden (403)
```json
{
    "status": "error",
    "message": "Access denied. Admin privileges required."
}
```

### Server Error (500)
```json
{
    "status": "error",
    "message": "An error occurred while processing your request"
}
```

## Rate Limiting
- Public endpoints: 60 requests per minute
- Authenticated endpoints: 100 requests per minute

## Testing with cURL

### Get all products:
```bash
curl -X GET "http://localhost/api/products" \
  -H "Accept: application/json"
```

### Search products:
```bash
curl -X GET "http://localhost/api/products/search?q=smartphone&category=Electronics" \
  -H "Accept: application/json"
```

### Create product (Admin):
```bash
curl -X POST "http://localhost/api/admin/products" \
  -H "Authorization: Bearer your-token-here" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test Product",
    "description": "A test product",
    "price": 99.99,
    "category": "Test",
    "brand": "TestBrand",
    "stock_quantity": 10
  }'
```

### Update product (Admin):
```bash
curl -X PUT "http://localhost/api/admin/products/507f1f77bcf86cd799439011" \
  -H "Authorization: Bearer your-token-here" \
  -H "Content-Type: application/json" \
  -d '{
    "price": 199.99,
    "stock_quantity": 20
  }'
```

### Delete product (Admin):
```bash
curl -X DELETE "http://localhost/api/admin/products/507f1f77bcf86cd799439011" \
  -H "Authorization: Bearer your-token-here"
```

## Notes
- All MongoDB ObjectIds are returned as strings
- Image uploads are stored in `storage/app/public/products/`
- Product specifications can contain any JSON structure
- Search is case-insensitive and searches in name and description fields
- Pagination is automatically applied to list endpoints