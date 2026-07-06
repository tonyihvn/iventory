# Custom Error Handling System

## Overview
This application has a custom error handling system that provides beautiful, user-friendly error pages for both end-users and developers. The system automatically displays appropriate error pages based on HTTP status codes and provides detailed debug information when APP_DEBUG is enabled.

## Error Pages

### User-Facing Error Pages (Production & Development)

#### 404 - Page Not Found
- **File**: `resources/views/errors/404.blade.php`
- **When**: Resource requested doesn't exist
- **Shows**: Helpful suggestions to find the content

#### 403 - Access Denied
- **File**: `resources/views/errors/403.blade.php`
- **When**: User doesn't have permission to access resource
- **Shows**: User role and permission information

#### 419 - Session Expired
- **File**: `resources/views/errors/419.blade.php`
- **When**: CSRF token mismatch or session timeout
- **Shows**: Login/logout options and session information

#### 422 - Validation Error
- **File**: `resources/views/errors/422.blade.php`
- **When**: Form submission has validation errors
- **Shows**: Guidance on what went wrong

#### 500 - Server Error
- **File**: `resources/views/errors/500.blade.php`
- **When**: General server-side errors
- **Shows**: User-friendly message and basic troubleshooting

### Developer Debug Page (Development Only)

#### Debug Error Page
- **File**: `resources/views/errors/debug.blade.php`
- **When**: APP_DEBUG=true and any exception occurs
- **Shows**:
  - Complete exception message
  - Stack trace with file paths and line numbers
  - Exception class and code
  - Request context (URL, method, IP, user agent)
  - Query parameters and POST data
  - Authenticated user information
  - Tabbed interface for organized information

## How It Works

### Exception Flow

1. **Exception Occurs** → Thrown from application code
2. **Handler Catches It** → `app/Exceptions/Handler.php`
3. **Determine Type** → HTTP exception or generic exception
4. **Check Debug Mode** → `config('app.debug')`
5. **Render Appropriate View**:
   - If DEBUG ON: Show detailed debug page
   - If DEBUG OFF: Show user-friendly error page

### Status Code Mapping

The handler maps HTTP status codes to specific views:
- `404.blade.php` → 404 Not Found
- `403.blade.php` → 403 Forbidden
- `419.blade.php` → 419 Session Expired
- `422.blade.php` → 422 Unprocessable Entity
- `500.blade.php` → 500 Server Error
- `debug.blade.php` → All errors when APP_DEBUG=true
- `minimal.blade.php` → Fallback for unmapped status codes

## Configuration

### Enable/Disable Debug Mode

Edit your `.env` file:

```env
# Development
APP_DEBUG=true

# Production
APP_DEBUG=false
```

### Add Custom Error Pages

To create a custom error page for a specific status code:

1. Create a new file: `resources/views/errors/{STATUS_CODE}.blade.php`
2. Extend the layout: `@extends('errors.layout')`
3. Define content with proper sections

Example for 502 Bad Gateway:

```blade
@extends('errors.layout')

@section('title', '502 - Bad Gateway')

@section('content')
    <div class="error-header">
        <div class="error-icon">🌐</div>
        <div class="error-code">502</div>
        <div class="error-title">Bad Gateway</div>
        <div class="error-subtitle">The server received an invalid response</div>
    </div>

    <div class="error-body">
        <div class="error-message">
            The upstream server is not responding correctly. Please try again.
        </div>
        
        <div class="actions">
            <a href="{{ url('/') }}" class="btn btn-primary">Go Home</a>
        </div>
    </div>
@endsection
```

## Custom Styling

### Base Layout (`resources/views/errors/layout.blade.php`)

- Gradient background (purple theme)
- Responsive design for mobile/tablet
- Professional styling with smooth animations
- CSS-only (no external dependencies)

### Colors & Theme

- **Primary**: `#667eea` (Indigo)
- **Secondary**: `#764ba2` (Purple)
- **Background**: White container on gradient
- **Text**: Dark gray (#333) on white, white on gradient

### Customizing Colors

Edit `resources/views/errors/layout.blade.php` CSS:

```css
.error-header {
    background: linear-gradient(135deg, YOUR_COLOR_1 0%, YOUR_COLOR_2 100%);
}
```

## Developer Features

### Debug Tab: Message
Shows the main error message, exception class, file, line number, and HTTP status.

### Debug Tab: Stack Trace
Displays the complete call stack with:
- File paths (relative to project root)
- Line numbers
- Function/method names
- Number of arguments passed

### Debug Tab: Context
Shows exception-specific context data (if available).

### Debug Tab: Request
Displays:
- Full URL
- HTTP method
- Client IP address
- User agent
- Query parameters
- POST data (with password fields hidden)
- Authenticated user info

## Security Considerations

### Production Mode (APP_DEBUG=false)
- ✅ No sensitive information displayed
- ✅ Stack traces hidden
- ✅ Generic error messages shown
- ✅ Secure for public users

### Development Mode (APP_DEBUG=true)
- ⚠️ Full stack traces visible
- ⚠️ Detailed error information shown
- ⚠️ Request data displayed (passwords hidden)
- ⚠️ Should NEVER be used in production

### Password Protection

Sensitive fields automatically hidden from debug display:
- `password`
- `password_confirmation`
- `_token` (CSRF token)

Add more sensitive fields in `Handler.php`:

```php
protected $dontFlash = [
    'password',
    'password_confirmation',
    'credit_card',
];
```

## Logging

All exceptions are logged to:
```
storage/logs/laravel.log
```

Check this file to:
- Track when errors occurred
- Identify patterns in failures
- Debug production issues (when logs available)

## Best Practices

### For Developers

1. **Check Debug Mode During Development**
   ```bash
   APP_DEBUG=true php artisan serve
   ```

2. **Review Stack Traces**
   - Identify the exact file and line causing the error
   - Trace backwards through the call stack
   - Look for your code in the trace

3. **Use Browser DevTools**
   - Network tab shows request/response
   - Console shows any JS errors
   - Elements tab shows DOM structure

4. **Enable File Editor in Debug**
   Add to `.env`:
   ```env
   IGNITION_EDITOR=phpstorm
   ```
   Click the file link to open directly in your IDE

### For End Users

1. **Read Error Messages**
   - They explain what went wrong
   - Suggest possible solutions
   - Provide next steps

2. **Follow Suggestions**
   - Refresh the page
   - Clear cookies
   - Try logging in again
   - Contact support if persists

## Testing Error Pages

### Test 404 Error
```bash
php artisan tinker
>>> throw new Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Test');
```

### Test 500 Error
```bash
# Visit a route that throws an exception
```

### Test Debug Mode
```bash
# Set APP_DEBUG=true in .env
# Trigger an error to see debug page
```

## Troubleshooting

### Error Page Not Showing
1. Check `APP_DEBUG` setting in `.env`
2. Verify view files exist in `resources/views/errors/`
3. Check file permissions
4. Clear config cache: `php artisan config:cache`

### Static Resources Not Loading
- Ensure assets are published
- Check file paths in error views
- Verify web server can serve static files

### Can't See Stack Trace
1. Verify `APP_DEBUG=true`
2. Check it's not a 404/403 (these show user-friendly pages)
3. Clear application cache: `php artisan cache:clear`

## Related Files

- `app/Exceptions/Handler.php` - Main exception handler
- `resources/views/errors/layout.blade.php` - Base layout
- `resources/views/errors/*.blade.php` - Specific error pages
- `.env` - Configuration
- `storage/logs/laravel.log` - Application logs

## Support

For questions or issues with error handling:
1. Review application logs
2. Check the debug page for details
3. Review this documentation
4. Contact development team
