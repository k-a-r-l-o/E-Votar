if (-not (Test-Path .env)) {
    Write-Host "Creating .env file from .env.example..." -ForegroundColor Cyan
    Copy-Item .env.example .env
    php artisan key:generate
}

Write-Host "Starting E-Votar Development Environment..." -ForegroundColor Green
Write-Host "Access locally at: http://localhost:8000" -ForegroundColor Yellow

# Use npx to run concurrently, executing both PHP server and Vite
npx concurrently "php artisan serve" "npm run dev"
