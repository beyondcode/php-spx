#!/bin/bash

# Variables
REPO_URL="https://api.github.com/repos/beyondcode/herd-spx-extension/releases/latest"
HERD_INSTALLED=0
HERD_BIN_DIR="$HOME/Library/Application Support/Herd/bin"
HERD_CONFIG_DIR="$HOME/Library/Application Support/Herd/config/php"
ARCH=$(uname -m)

# Determine architecture
if [[ $ARCH == "arm64" ]]; then
    ARCH="arm64"
else
    ARCH="x64"
fi

VERBOSE=0

# Parse command line arguments
while [[ "$#" -gt 0 ]]; do
    case $1 in
        -v|--verbose) VERBOSE=1 ;;
        *) echo "Unknown parameter passed: $1"; exit 1 ;;
    esac
    shift
done

# Helper functions
info() {
    local blue_bg="\033[44m"
    local white_text="\033[97m"
    local reset="\033[0m"
    echo -e " ${blue_bg}${white_text} INFO ${reset} $1\n"
}

success() {
    local green_bg="\033[42m"
    local white_text="\033[97m"
    local reset="\033[0m"
    echo -e " ${green_bg}${white_text} SUCCESS ${reset} $1\n"
}

error() {
    local red_bg="\033[41m"
    local white_text="\033[97m"
    local reset="\033[0m"
    echo -e " ${red_bg}${white_text} ERROR ${reset} $1\n"
}

# Fetch the latest release tag from GitHub API
LATEST_RELEASE=$(curl -s $REPO_URL | grep '"tag_name":' | sed -E 's/.*"([^"]+)".*/\1/')

echo ""
info "Latest release: $LATEST_RELEASE"


# Function to download and extract the .so file
download_and_extract() {
    local version=$1
    local php_version=$2
    local target_dir=$HERD_CONFIG_DIR/$version
    local file_url="https://github.com/beyondcode/herd-spx-extension/releases/download/$LATEST_RELEASE/spx-php-$php_version-nts-mac-$ARCH-clang.zip"
    local zip_file="spx-php-$php_version.zip"
    local so_file="spx.so"

    info "Downloading $zip_file..."

    #curl -L -o "$target_dir/$zip_file" $file_url
    
    info "Extracting $zip_file..."
    # unzip -o "$target_dir/$zip_file" -d "$target_dir/spx" > /dev/null

    # Make sure the .so file exists
    if [[ ! -f "$target_dir/spx/$so_file" ]]; then
        echo "There was an error extracting the extension."
        echo "$so_file not found in $target_dir/spx"
        exit 1
    fi

    # Remove the quarantine attribute (might not be needed when the repo is public)
    # sudo xattr -r -d com.apple.quarantine "$target_dir/spx/$so_file"

    # rm "$target_dir/$zip_file"
}

info "Installing the SPX Profiler extension..."

# Check if Laravel Herd is installed
PHP_PATH=$(which php)
if [[ $PHP_PATH == *"/Application Support/Herd/bin/"* ]]; then
    HERD_INSTALLED=1
fi

if [[ $HERD_INSTALLED -eq 1 ]]; then
    info "Laravel Herd is installed. Installing for all available PHP versions..."
    for version in {74..84}; do
        PHP_BIN="$HERD_BIN_DIR/php$version"
        CONFIG_DIR="$HERD_CONFIG_DIR/$version"
        if [[ -f $PHP_BIN ]]; then
            PHP_VERSION_STRING="${version:0:1}.${version:1}"
            EXT_DIR=$CONFIG_DIR
            PHP_INI_PATH=$CONFIG_DIR/php.ini

            SO_FILE="$EXT_DIR/spx/spx.so"

            # Check if the extension is already in php.ini
            if ! grep -q "extension=$SO_FILE" "$PHP_INI_PATH"; then
                info "Installing for PHP $PHP_VERSION_STRING..."
                
                # Download and extract the .so file
                _=$(download_and_extract $version $PHP_VERSION_STRING)

                {
                    echo ""
                    echo ""
                    echo "extension=$SO_FILE"
                    echo "spx.http_enabled=1"
                    echo "spx.http_key=LARAVEL-HERD"
                    echo "spx.http_ip_whitelist=\"127.0.0.1\""
                    echo "spx.http_ui_assets_dir=$CONFIG_DIR/spx/assets/web-ui"
                    echo ""
                } >> "$PHP_INI_PATH"

                success "Extension installed successfully"
            else
                if [[ $VERBOSE -eq 1 ]]; then
                    info "Skipping, already installed…"
                fi
            fi
        fi
    done

    info "Restarting Laravel Herd..."
    
    if [[ $VERBOSE -eq 1 ]]; then
        herd restart
    else
        herd restart > /dev/null
    fi

    # Validate the installation
    SPX_INSTALLED=$(php -m | grep SPX)
    if [[ -z $SPX_INSTALLED ]]; then
        error "There was an error installing the extension."
        error "Please check the output above for any errors."
        exit 1
    fi

    success "SPX Profiler extension installed successfully."
else
    error "Laravel Herd is not installed. Exiting..."
    error "Please follow the manual installation instructions on the GitHub repository."
    exit 1
fi