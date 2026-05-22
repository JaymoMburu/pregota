#!/bin/bash
# ═══════════════════════════════════════════════════════════════
#  Pregota — Shared Hosting Deploy Script
#  For InterServer (cPanel) shared hosting
#  Usage:
#    First deploy:  bash deploy.sh --fresh
#    Update only:   bash deploy.sh
# ═══════════════════════════════════════════════════════════════

set -e  # exit on any error

# ── Config — edit these before running ─────────────────────────
REPO_URL="https://github.com/YOUR_USERNAME/pregota.git"
APP_DIR="$HOME/pregota"          # app lives OUTSIDE public_html
PUBLIC_HTML="$HOME/public_html"  # your domain's web root
BRANCH="main"
# ───────────────────────────────────────────────────────────────

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

info()    { echo -e "${GREEN}[✓]${NC} $1"; }
warn()    { echo -e "${YELLOW}[!]${NC} $1"; }
error()   { echo -e "${RED}[✗]${NC} $1"; exit 1; }
section() { echo -e "\n${GREEN}══ $1 ══${NC}"; }

# ── Detect PHP binary ───────────────────────────────────────────
section "Detecting PHP"
PHP=""
for candidate in php8.2 php82 php8.1 php81 php8 php; do
    if command -v "$candidate" &>/dev/null; then
        version=$("$candidate" -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")
        if [[ $(echo "$version >= 8.1" | bc -l) -eq 1 ]]; then
            PHP="$candidate"
            info "Using $PHP (PHP $version)"
            break
        fi
    fi
done
[[ -z "$PHP" ]] && error "PHP 8.1+ not found. Contact InterServer support to enable PHP 8.2."

# ── Detect or install Composer ──────────────────────────────────
section "Checking Composer"
if command -v composer &>/dev/null; then
    COMPOSER="composer"
    info "Composer found globally"
elif [[ -f "$HOME/composer.phar" ]]; then
    COMPOSER="$PHP $HOME/composer.phar"
    info "Composer found at ~/composer.phar"
else
    warn "Composer not found — downloading..."
    curl -sS https://getcomposer.org/installer | $PHP -- --install-dir="$HOME" --filename=composer.phar
    COMPOSER="$PHP $HOME/composer.phar"
    info "Composer installed at ~/composer.phar"
fi

# ════════════════════════════════════════════════════════════════
#  FRESH INSTALL
# ════════════════════════════════════════════════════════════════
if [[ "$1" == "--fresh" ]]; then

    section "Fresh Install"

    # Clone repo
    if [[ -d "$APP_DIR" ]]; then
        warn "Directory $APP_DIR already exists — removing and re-cloning"
        rm -rf "$APP_DIR"
    fi

    info "Cloning from $REPO_URL..."
    git clone --branch "$BRANCH" "$REPO_URL" "$APP_DIR"
    cd "$APP_DIR"

    # Install dependencies
    section "Installing Dependencies"
    $COMPOSER install --no-dev --optimize-autoloader --no-interaction
    info "Composer packages installed"

    # Set up .env
    section "Environment Setup"
    if [[ ! -f ".env" ]]; then
        cp .env.example .env
        warn ".env created from .env.example"
        warn "You MUST edit .env now. Opening in nano..."
        echo ""
        echo "  Set these values:"
        echo "  APP_URL=https://pregota.com"
        echo "  DB_HOST=localhost"
        echo "  DB_DATABASE=<your_cpanel_db>"
        echo "  DB_USERNAME=<your_cpanel_db_user>"
        echo "  DB_PASSWORD=<your_db_password>"
        echo "  QUEUE_CONNECTION=sync"
        echo "  SESSION_DRIVER=file"
        echo "  CACHE_STORE=file"
        echo "  MPESA_ENVIRONMENT=production"
        echo "  (and all your M-Pesa keys)"
        echo ""
        read -p "Press ENTER to open .env in nano..."
        nano .env
    else
        info ".env already exists — skipping"
    fi

    # Generate app key
    section "App Key"
    $PHP artisan key:generate --force
    info "Application key generated"

    # Storage permissions
    section "Permissions"
    chmod -R 755 storage bootstrap/cache
    $PHP artisan storage:link 2>/dev/null || true
    info "Storage permissions set"

    # Database
    section "Database"
    read -p "Run migrations now? (yes/no): " RUN_MIGRATE
    if [[ "$RUN_MIGRATE" == "yes" ]]; then
        $PHP artisan migrate --force
        info "Migrations complete"
    else
        warn "Skipped migrations — run manually: php artisan migrate --force"
    fi

    # Point public_html to Laravel's public folder
    section "Web Root Setup"
    echo ""
    echo "  Two options to serve Laravel from pregota.com:"
    echo ""
    echo "  Option A (recommended): In cPanel → Domains,"
    echo "  set Document Root for pregota.com to:"
    echo "  $APP_DIR/public"
    echo ""
    echo "  Option B (fallback): Copy index.php + .htaccess to public_html"
    echo ""
    read -p "Use Option B fallback now? (yes/no): " USE_FALLBACK

    if [[ "$USE_FALLBACK" == "yes" ]]; then
        # Copy public files and rewrite index.php paths
        cp "$APP_DIR/public/index.php" "$PUBLIC_HTML/index.php"
        cp "$APP_DIR/public/.htaccess" "$PUBLIC_HTML/.htaccess"

        # Rewrite paths in index.php to point to the app directory
        sed -i "s|__DIR__.'/../vendor|'$APP_DIR/vendor|g" "$PUBLIC_HTML/index.php"
        sed -i "s|__DIR__.'/..'|'$APP_DIR'|g" "$PUBLIC_HTML/index.php"

        info "public_html/index.php patched to point to $APP_DIR"
    else
        info "Set document root manually in cPanel to: $APP_DIR/public"
    fi

    # Cache everything
    section "Caching"
    $PHP artisan config:cache
    $PHP artisan route:cache
    $PHP artisan view:cache
    info "Config, routes, and views cached"

    # Set up cron
    section "Cron Job"
    echo ""
    echo "  Add this cron job in cPanel → Cron Jobs:"
    echo "  Every minute (*  *  *  *  *)"
    echo "  Command: $PHP $APP_DIR/artisan schedule:run >> /dev/null 2>&1"
    echo ""

    section "Done — Fresh Install Complete"
    echo ""
    echo "  App directory : $APP_DIR"
    echo "  Web root      : $APP_DIR/public"
    echo "  URL           : https://pregota.com"
    echo ""
    echo "  Next steps:"
    echo "  1. Verify https://pregota.com loads correctly"
    echo "  2. Test M-Pesa STK Push end-to-end"
    echo "  3. Add the cron job in cPanel"
    echo ""

# ════════════════════════════════════════════════════════════════
#  UPDATE DEPLOY (no args — pull latest and refresh)
# ════════════════════════════════════════════════════════════════
else

    section "Deploying Update"
    [[ ! -d "$APP_DIR" ]] && error "App directory not found. Run: bash deploy.sh --fresh"
    cd "$APP_DIR"

    # Pull latest code
    info "Pulling latest from $BRANCH..."
    git fetch origin
    git reset --hard "origin/$BRANCH"

    # Update dependencies (only if composer.lock changed)
    section "Dependencies"
    $COMPOSER install --no-dev --optimize-autoloader --no-interaction
    info "Composer up to date"

    # Run any new migrations
    section "Migrations"
    $PHP artisan migrate --force
    info "Migrations applied"

    # Clear and rebuild cache
    section "Cache Refresh"
    $PHP artisan config:clear
    $PHP artisan route:clear
    $PHP artisan view:clear
    $PHP artisan config:cache
    $PHP artisan route:cache
    $PHP artisan view:cache
    info "Cache rebuilt"

    # Fix permissions (in case new files were added)
    chmod -R 755 storage bootstrap/cache

    section "Update Complete"
    echo ""
    echo "  Deployed: $(git log -1 --format='%h — %s (%ar)')"
    echo ""

fi
