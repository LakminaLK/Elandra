#!/bin/bash
set -e

echo "🔨 Railway Build Process Starting..."

# Install Node.js dependencies for frontend build
echo "📦 Installing Node.js dependencies..."
npm ci --only=production

# Build frontend assets with Vite
echo "🎨 Building frontend assets..."
npm run build

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Clear any existing caches
echo "🧹 Clearing development caches..."
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*

echo "✅ Railway build completed successfully!"