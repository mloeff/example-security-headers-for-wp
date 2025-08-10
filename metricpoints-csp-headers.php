<?php
/**
 * WordPress Content Security Policy Implementation
 * 
 * Version: 2025-08-09.001
 * MetricPoints.com - https://metricpoints.com/
 * 
 * This file provides comprehensive CSP headers for WordPress sites.
 * Please note: These are example CSP headers from MetricPoints.com. Your site may need additions or changes, so please review and adjust as needed.
 * 
 * Place this file in your theme directory (or if you're using a child theme, in the child theme directory) and require it in functions.php:
 * 
 * require_once get_template_directory() . '/metricpoints-csp-headers.php';
 * 
 * Features:
 * - Nonce-based inline script security
 * - Popular third-party service support
 * - Report-only mode for safe testing
 * - Automatic nonce injection for WordPress
 * - Comprehensive security headers
 */

// Generate a nonce so we can reference it in the CSP headers
add_action(\'init\', function () {
    // Generate a base64 nonce per request
    if (!defined(\'CSP_NONCE\')) {
        define(\'CSP_NONCE\', base64_encode(random_bytes(16)));
    }
});

add_action(\'send_headers\', function () {
    // HSTS - Force HTTPS for 2 years
    header(\'Strict-Transport-Security: max-age=63072000; includeSubDomains\');

    // Permissions-Policy - Restrict browser features
    header("Permissions-Policy: accelerometer=(), autoplay=(), camera=(), encrypted-media=(), fullscreen=(self), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), midi=(), payment=(), picture-in-picture=(), sync-xhr=(), usb=(), xr-spatial-tracking=()");

    // Referrer-Policy - Control referrer information
    header(\'Referrer-Policy: strict-origin-when-cross-origin\');

    // Build CSP
    $nonce = CSP_NONCE;

    $script_src = [
        "\'self\'",
        "\'nonce-{$nonce}\'", // allow inline scripts that carry this nonce
        "https://www.googletagmanager.com",
        "https://googletagmanager.com",
        "https://www.google.com",
        "https://www.googleadservices.com",
        "https://googleads.g.doubleclick.net",
        "https://www.gstatic.com",
        "https://static.hotjar.com",
        "https://script.hotjar.com",
        "https://app.termly.io",
    ];

    $script_src_elem = $script_src;

    $style_src = [
        "\'self\'",
        "\'unsafe-inline\'", // Consider replacing with nonces for production
        "https://fonts.googleapis.com",
        "https://app.termly.io",
    ];

    $img_src = [
        "\'self\'",
        "data:",
        "blob:",
        "https:",
        "https://www.googleadservices.com",
        "https://googleads.g.doubleclick.net",
        "https://app.termly.io",
    ];

    $font_src = [
        "\'self\'",
        "data:",
        "https://fonts.gstatic.com",
        "https://fonts.googleapis.com",
        "https://www.gstatic.com",
    ];

    $connect_src = [
        "\'self\'",
        "https:",
        "wss:",
        "https://www.googletagmanager.com",
        "https://www.google-analytics.com",
        "https://www.google.com",
        "https://googleads.g.doubleclick.net",
        "https://www.googleadservices.com",
        "https://static.hotjar.com",
        "https://script.hotjar.com",
        "https://*.hotjar.com",
        "wss://*.hotjar.com",
        "https://app.termly.io",
    ];

    $frame_src = [
        "\'self\'",
        "https://www.googletagmanager.com",
        "https://www.google.com",
        "https://www.gstatic.com",
        "https://td.doublclick.net",
    ];

    $worker_src = [
        "\'self\'",
        "blob:",
    ];

    // Compose CSP
    $csp = implode(\'; \', [
        "default-src \'self\'",
        "base-uri \'self\'",
        "object-src \'none\'",
        "form-action \'self\'",
        "frame-ancestors \'none\'",
        "script-src " . implode(\' \', $script_src),
        "script-src-elem " . implode(\' \', $script_src_elem),
        "style-src " . implode(\' \', $style_src),
        "img-src " . implode(\' \', $img_src),
        "font-src " . implode(\' \', $font_src),
        "connect-src " . implode(\' \', $connect_src),
        "frame-src " . implode(\' \', $frame_src),
        "worker-src " . implode(\' \', $worker_src),
        "upgrade-insecure-requests",
    ]);

    // Use Report-Only mode for safe testing
    // Change to Content-Security-Policy when ready to enforce
    header("Content-Security-Policy-Report-Only: {$csp}");
});

/**
 * Add the CSP nonce to all enqueued <script> tags so inline snippets added via
 * wp_add_inline_script() (and many plugin inlines) will execute without unsafe-inline.
 */
add_filter(\'script_loader_tag\', function ($tag, $handle, $src) {
    if (false === strpos($tag, \' nonce=\')) {
        $tag = str_replace(\'<script \', \'<script nonce="\' . esc_attr(CSP_NONCE) . \'" \', $tag);
    }
    return $tag;
}, 10, 3);

/**
 * Attach the CSP nonce to all <script> tags missing it.
 * - Safe in production (just adds an attribute)
 * - Plays nice with your Report-Only CSP
 */
add_action(\'template_redirect\', function () {
    if (!defined(\'CSP_NONCE\')) {
        define(\'CSP_NONCE\', base64_encode(random_bytes(16)));
    }
    ob_start(function ($html) {
        // Add nonce to any <script> without an existing nonce=
        // - Ignore scripts with a nonce already
        // - Works for inline and external
        $nonce = CSP_NONCE;

        // Don\'t touch JSON-LD etc that some plugins output as application/ld+json
        $html = preg_replace(
            \'/<script(?![^>]*\\bnonce=)([^>]*)>/i\',
            \'<script nonce="\' . $nonce . \'"$1>\',
            $html
        );

        return $html;
    });
}, 0);

/**
 * If you have inline <script> blocks hard-coded in templates (not enqueued),
 * give them the nonce manually: <script nonce="<?php echo CSP_NONCE; ?>">...</script>
 */

/**
 * Customization Notes:
 * 
 * 1. To enforce CSP (remove Report-Only):
 *    Change: Content-Security-Policy-Report-Only
 *    To: Content-Security-Policy
 * 
 * 2. To add your own domains:
 *    Add them to the appropriate arrays (script_src, img_src, etc.)
 * 
 * 3. To remove \'unsafe-inline\' from style-src:
 *    Generate nonces for inline styles or use hashes
 * 
 * 4. To add CSP reporting, you can use the MetricPoints.com CSP reporting endpoint:
 *    Just signup for an account at https://metricpoints.com/
 *    Or you can build your own CSP reporting endpoint.
 *    Add: "report-uri https://metricpoints.com/api/your-csp-report-endpoint"
 *    Or: "report-to default" (requires Report-To header)
 * 
 */';