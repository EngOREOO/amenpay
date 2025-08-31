# Laravel Boost Setup Guide

## Overview

Laravel Boost has been successfully installed in your P-Finance backend project. This powerful MCP (Model Context Protocol) server provides AI-assisted development capabilities specifically designed for Laravel applications.

## What Was Installed

### 1. MCP Server Configuration
- **File**: `.cursor/mcp.json`
- **Server**: `laravel-boost`
- **Command**: `php ./artisan boost:mcp`

### 2. AI Guidelines
- **File**: `.cursor/rules/laravel-boost.mdc`
- **Coverage**: 9 comprehensive guidelines including:
  - Foundation rules
  - Laravel core (v12)
  - PHP best practices
  - Pint code formatting
  - Tailwind CSS (v3)
  - Testing enforcement

## Available Boost Tools

Laravel Boost provides the following MCP tools:

| Tool | Description |
|------|-------------|
| `application-info` | Read PHP & Laravel versions, database engine, ecosystem packages, and Eloquent models |
| `browser-logs` | Read logs and errors from the browser |
| `database-connections` | Inspect available database connections |
| `database-query` | Execute queries against the database |
| `database-schema` | Read the database schema |
| `get-absolute-url` | Convert relative paths to absolute URLs |
| `get-config` | Get configuration values using dot notation |
| `last-error` | Read the last error from application logs |
| `list-artisan-commands` | Inspect available Artisan commands |
| `list-available-config-keys` | Inspect available configuration keys |
| `list-available-env-vars` | Inspect available environment variables |
| `list-routes` | Inspect application routes |
| `read-log-entries` | Read the last N log entries |
| `report-feedback` | Share Boost & Laravel AI feedback |
| `search-docs` | Query Laravel documentation API |
| `tinker` | Execute arbitrary PHP code within the application context |

## How to Use

### 1. In Cursor IDE
The MCP server is automatically configured and will be available when you open your project in Cursor. The AI guidelines are automatically applied to enhance your development experience.

### 2. Manual MCP Server Registration
If you need to register the MCP server manually in other editors, use:

```json
{
    "mcpServers": {
        "laravel-boost": {
            "command": "php",
            "args": ["./artisan", "boost:mcp"]
        }
    }
}
```

### 3. Command Line Tools
You can also use Boost tools directly from the command line:

```bash
# Execute a specific tool
php artisan boost:execute-tool <tool-name> <arguments>

# Start the MCP server manually
php artisan boost:mcp
```

## AI Guidelines Features

### Laravel 12 Specific
- Streamlined file structure (no middleware files in `app/Http/Middleware/`)
- Uses `bootstrap/app.php` for middleware and exception registration
- Commands auto-register from `app/Console/Commands/`

### Code Quality
- Enforces PHP 8.2+ features
- Requires explicit return type declarations
- Uses constructor property promotion
- Enforces curly braces for all control structures

### Testing
- Every change must be programmatically tested
- Prefers feature tests over unit tests
- Uses factories and seeders for test data

### Frontend
- Tailwind CSS v3 support
- Dark mode considerations
- Responsive design patterns

## Package Versions Supported

- **Laravel Framework**: 12.x
- **PHP**: 8.2+
- **Tailwind CSS**: 3.x
- **Laravel Pint**: 1.x
- **Laravel Sail**: 1.x

## Documentation Access

The `search-docs` tool provides access to:
- Laravel Framework documentation (10.x, 11.x, 12.x)
- Filament documentation (2.x, 3.x, 4.x)
- Livewire documentation (1.x, 2.x, 3.x)
- Tailwind CSS documentation (3.x, 4.x)
- Pest documentation (3.x, 4.x)

## Next Steps

1. **Restart Cursor**: Close and reopen Cursor to ensure the MCP server is loaded
2. **Test the Integration**: Try asking the AI to help with Laravel-specific tasks
3. **Explore Tools**: Use the available Boost tools for database queries, configuration access, and more
4. **Custom Guidelines**: Add custom AI guidelines in `.ai/guidelines/*.blade.php` files if needed

## Troubleshooting

### MCP Server Not Working
- Ensure you're in the correct directory (`p-finance-backend`)
- Check that `php artisan boost:mcp` runs without errors
- Verify the `.cursor/mcp.json` file exists and is properly formatted

### AI Guidelines Not Applied
- Check that `.cursor/rules/laravel-boost.mdc` exists
- Restart Cursor after installation
- Verify the rules file contains the `<laravel-boost-guidelines>` tags

### Tool Execution Errors
- Ensure all dependencies are installed (`composer install`)
- Check Laravel application is properly configured
- Verify database connections if using database-related tools

## Resources

- [Laravel Boost Official Documentation](https://boost.laravel.com)
- [MCP Protocol Documentation](https://modelcontextprotocol.io)
- [Laravel 12 Documentation](https://laravel.com/docs/12.x)

---

**Note**: Laravel Boost is currently in beta and receives frequent updates. Check the official documentation for the latest features and improvements.
