#!/bin/bash

# Script para compilar assets del proyecto Money Landing
# Soluciona el problema de Node no instalado en WSL

echo "======================================"
echo "Money Landing - Compilador de Assets"
echo "======================================"
echo ""

# Verificar si estamos en el directorio correcto
if [ ! -f "package.json" ]; then
    echo "❌ Error: Este script debe ejecutarse desde el directorio raíz del proyecto"
    exit 1
fi

echo "📦 Verificando Node.js..."

# Intentar encontrar Node
if command -v node &> /dev/null; then
    echo "✅ Node.js encontrado: $(node --version)"
    echo "📦 Instalando dependencias..."
    npm install --silent
    echo "🔨 Compilando assets con Vite..."
    npm run build
    echo ""
    echo "✅ ¡Assets compilados exitosamente!"
    echo "📂 Los archivos están en: public/build/"
    exit 0
fi

echo "⚠️  Node.js no está instalado en este sistema"
echo ""
echo "Opciones para compilar:"
echo ""
echo "1️⃣  Usar Docker (recomendado):"
echo "   docker run --rm -v \"\$(pwd)\":/app -w /app node:20 npm run build"
echo ""
echo "2️⃣  Instalar Node.js localmente:"
echo "   En Windows: https://nodejs.org/download/"
echo "   En macOS: brew install node"
echo "   En Linux: sudo apt install nodejs npm"
echo ""
echo "3️⃣  Usar el servidor de desarrollo (sin compilar):"
echo "   npm run dev"
echo ""

# Si Docker está disponible, ofrecer compilar
if command -v docker &> /dev/null; then
    echo "🐳 Docker detectado. ¿Quieres compilar con Docker? (s/n)"
    read -r response
    if [[ "$response" =~ ^[SsYy]$ ]]; then
        echo "🔨 Compilando con Docker..."
        docker run --rm -v "$(pwd)":/app -w /app node:20 bash -c "npm install && npm run build"
        if [ $? -eq 0 ]; then
            echo ""
            echo "✅ ¡Assets compilados exitosamente con Docker!"
            echo "📂 Los archivos están en: public/build/"
            exit 0
        else
            echo "❌ Error al compilar con Docker"
            exit 1
        fi
    fi
fi

echo ""
echo "ℹ️  Por favor, compila los assets manualmente usando uno de los métodos anteriores."
exit 1
