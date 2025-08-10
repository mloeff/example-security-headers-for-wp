# WordPress Security Headers Examples

[![WordPress](https://img.shields.io/badge/WordPress-6.0+-blue.svg)](https://wordpress.org)
[![License](https://img.shields.io/badge/License-GPL%20v3-green.svg)](LICENSE)
[![MetricPoints](https://img.shields.io/badge/Monitoring-MetricPoints-orange.svg)](https://metricpoints.com)

**Secure Your WordPress Site with Professional-Grade Security Headers**

This repository contains production-ready security header implementations for WordPress sites, including Content Security Policy (CSP), HTTP Strict Transport Security (HSTS), and other essential security measures. These examples are designed to be easily integrated into your WordPress theme or plugin.

## 🛡️ What Are Security Headers?

Security headers are HTTP response headers that help protect your website from various attacks and vulnerabilities. They provide an additional layer of security beyond your WordPress security plugins and server configurations.

### **Why Security Headers Matter for WordPress**
- **XSS Protection**: Prevent cross-site scripting attacks
- **Clickjacking Defense**: Stop malicious sites from embedding your content
- **HTTPS Enforcement**: Ensure all traffic uses secure connections
- **Content Control**: Restrict which resources can load on your site
- **Modern Browser Support**: Leverage built-in browser security features

## 🔒 Security Headers Included

### **1. Content Security Policy (CSP)**
The most powerful security header that controls which resources can load on your site.

**Features:**
- Nonce-based inline script security
- Popular third-party service support (Google Analytics, Hotjar, etc.)
- Report-only mode for safe testing
- Automatic nonce injection for WordPress
- Comprehensive directive coverage

### **2. HTTP Strict Transport Security (HSTS)**
Forces browsers to use HTTPS for all future requests to your domain.

**Benefits:**
- Prevents protocol downgrade attacks
- Ensures encrypted connections
- Improves SEO and user trust
- Protects against man-in-the-middle attacks

### **3. Permissions Policy**
Controls which browser features and APIs can be used on your site.

**Controlled Features:**
- Camera and microphone access
- Geolocation services
- Payment APIs
- Fullscreen mode
- And many more

### **4. Referrer Policy**
Controls how much referrer information is sent with requests.

**Options:**
- `strict-origin-when-cross-origin` (recommended)
- `no-referrer-when-downgrade`
- `same-origin`
- `strict-origin`

### **5. Additional Security Headers**
- **X-Content-Type-Options**: Prevents MIME type sniffing
- **X-Frame-Options**: Clickjacking protection
- **X-XSS-Protection**: Additional XSS protection for older browsers

## 🚀 Quick Start

### **Option 1: Include in Your Theme (Recommended)**

1. **Download the security headers file:**
   ```bash
   wget https://raw.githubusercontent.com/yourusername/example-security-headers-for-wp/main/security-headers.php
   ```

2. **Place it in your theme directory:**
   ```bash
   # For Twenty Twenty-Four theme
   cp security-headers.php wp-content/themes/twentytwentyfour/
   
   # For custom theme
   cp security-headers.php wp-content/themes/your-theme/
   ```

3. **Require it in your theme's functions.php:**
   ```php
   <?php
   // Add this line to your theme's functions.php
   require_once get_template_directory() . '/security-headers.php';
   ```

### **Option 2: Include in a Custom Plugin**

1. **Create a new plugin file:**
   ```php
   <?php
   /*
   Plugin Name: Security Headers
   Description: Adds comprehensive security headers to WordPress
   Version: 1.0.0
   Author: Your Name
   License: GPL v3
   */
   
   // Prevent direct access
   if (!defined('ABSPATH')) {
       exit;
   }
   
   // Include security headers
   require_once plugin_dir_path(__FILE__) . 'security-headers.php';
   ```

2. **Upload both files to `/wp-content/plugins/security-headers/`**

3. **Activate the plugin in WordPress admin**

## 📋 Implementation Examples

### **Basic CSP Implementation**
```php
<?php
// Add to your theme's functions.php
add_action('send_headers', function () {
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';");
});
```

### **Advanced CSP with Nonces**
```php
<?php
// Generate nonce for inline scripts
add_action('init', function () {
    if (!defined('CSP_NONCE')) {
        define('CSP_NONCE', base64_encode(random_bytes(16)));
    }
});

// Add CSP header with nonce
add_action('send_headers', function () {
    $nonce = CSP_NONCE;
    header("Content-Security-Policy: script-src 'self' 'nonce-{$nonce}';");
});
```

### **HSTS Implementation**
```php
<?php
add_action('send_headers', function () {
    header('Strict-Transport-Security: max-age=63072000; includeSubDomains; preload');
});
```

## 🔧 Customization Guide

### **Adding Your Own Domains**
Edit the arrays in `security-headers.php` to include your trusted domains:

```php
$script_src = [
    "'self'",
    "'nonce-{$nonce}'",
    "https://www.googletagmanager.com",    // Google Analytics
    "https://your-cdn.com",               // Your CDN
    "https://api.yourservice.com",        // Your API
];
```

### **Modifying CSP Directives**
Adjust the CSP policy to match your site's needs:

```php
// More permissive for development
$csp = implode('; ', [
    "default-src 'self'",
    "script-src 'self' 'unsafe-inline'",  // Allow inline scripts
    "style-src 'self' 'unsafe-inline'",   // Allow inline styles
    "img-src 'self' data: https:",        // Allow data URIs and HTTPS images
]);
```

### **Testing in Report-Only Mode**
Start with report-only mode to test your policy:

```php
// Change this line in security-headers.php
header("Content-Security-Policy-Report-Only: {$csp}");

// To this when ready to enforce
header("Content-Security-Policy: {$csp}");
```

## 📊 Monitoring with MetricPoints

While these security headers provide excellent protection, monitoring their effectiveness is crucial. [MetricPoints](https://metricpoints.com) offers comprehensive CSP violation monitoring to help you:

### **Why Monitor CSP Violations?**
- **Identify Blocked Resources**: See which legitimate resources are being blocked
- **Track Policy Effectiveness**: Monitor how well your security policy is working
- **Debug Issues**: Get detailed reports about policy violations
- **Optimize Performance**: Ensure critical resources load without issues

### **Setting Up CSP Reporting**
Add the report-uri directive to your CSP policy:

```php
// In your security-headers.php
$csp = implode('; ', [
    "default-src 'self'",
    "script-src 'self' 'nonce-{$nonce}'",
    "report-uri https://metricpoints.com/api/csp-reports/YOUR_API_KEY",
]);
```

### **Getting Started with MetricPoints**
1. **Sign up** at [metricpoints.com](https://metricpoints.com)
2. **Create a CSP project** for your domain
3. **Get your API key** and add it to the report-uri directive
4. **Monitor violations** in real-time through the dashboard

## 🧪 Testing Your Security Headers

### **Online Testing Tools**
- **[Security Headers](https://securityheaders.com)**: Check your security headers
- **[CSP Evaluator](https://csp-evaluator.withgoogle.com)**: Test your CSP policy
- **[Mozilla Observatory](https://observatory.mozilla.org)**: Comprehensive security analysis

### **Browser Developer Tools**
1. **Open Developer Tools** (F12)
2. **Go to Network tab**
3. **Reload the page**
4. **Check Response Headers** for your security headers

### **Testing CSP Violations**
1. **Add a test script** that violates your CSP
2. **Check browser console** for violation messages
3. **Verify reports** are sent to your endpoint (if using MetricPoints)

## ⚠️ Important Considerations

### **Before Going Live**
- **Test thoroughly** in a staging environment
- **Use report-only mode** initially to catch issues
- **Monitor closely** for the first few days
- **Have a rollback plan** if issues arise

### **Common Issues**
- **Inline scripts/styles** may be blocked
- **Third-party resources** might not load
- **WordPress plugins** may conflict with strict policies
- **CDN resources** need to be explicitly allowed

### **Performance Impact**
- **Minimal overhead** - headers are sent once per page
- **Browser caching** reduces repeated header processing
- **Modern browsers** handle CSP efficiently

## 🔄 Updates and Maintenance

### **Regular Reviews**
- **Monthly**: Review CSP violation reports
- **Quarterly**: Update allowed domains and sources
- **Annually**: Review and update security policies

### **Staying Current**
- **Follow security blogs** for new threats
- **Update WordPress** and plugins regularly
- **Monitor browser updates** for new security features
- **Review CSP specification** changes

## 🤝 Contributing

We welcome contributions to improve these security header examples!

### **How to Contribute**
1. **Fork the repository**
2. **Create a feature branch**
3. **Make your changes**
4. **Test thoroughly**
5. **Submit a pull request**

### **What We're Looking For**
- **New security header examples**
- **Platform-specific implementations** (WooCommerce, BuddyPress, etc.)
- **Performance optimizations**
- **Better documentation**
- **Bug fixes and improvements**

## 📝 License

This project is licensed under the **GPL v3 License** - see the [LICENSE](LICENSE) file for details.

**Why GPL v3?**
- Ensures derivative works remain open source
- Prevents commercial exploitation of examples
- Encourages community contributions
- Aligns with WordPress licensing philosophy

## 📞 Support & Resources

### **WordPress Security Resources**
- [WordPress Security Handbook](https://developer.wordpress.org/plugins/security/)
- [WordPress Security Team Blog](https://wordpress.org/news/category/security/)
- [WordPress Security Best Practices](https://wordpress.org/support/article/hardening-wordpress/)

### **Security Headers Documentation**
- [MDN Security Headers](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers#Security)
- [CSP Specification](https://www.w3.org/TR/CSP3/)
- [HSTS Specification](https://tools.ietf.org/html/rfc6797)

### **MetricPoints Support**
- **Documentation**: [docs.metricpoints.com](https://docs.metricpoints.com)
- **Email**: support@metricpoints.com
- **Website**: [metricpoints.com](https://metricpoints.com)

## 🙏 Acknowledgments

- **WordPress Community**: For the excellent platform
- **Security Researchers**: For ongoing security research
- **Browser Vendors**: For implementing security standards
- **MetricPoints Team**: For CSP monitoring tools

---

**Built with ❤️ for the WordPress Community**

*Secure your WordPress site today with professional-grade security headers.*

## 📋 Changelog

### **Version 1.0.0** - Initial Release
- Complete CSP implementation with nonce support
- HSTS, Permissions Policy, and Referrer Policy headers
- WordPress-specific optimizations
- Comprehensive documentation and examples
- GPL v3 licensing
