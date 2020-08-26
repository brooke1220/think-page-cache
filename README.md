# think-page-cache
thinkphp 页面缓存

For nginx:

Update your location block's try_files directive to include a check in the page-cache directory:

location = / {
    try_files /page-cache/pc__index__pc.html /index.php?$query_string;
}

location / {
    try_files $uri $uri/ /page-cache/$uri.html /page-cache/$uri.json /index.php?$query_string;
}
For apache:

Open public/.htaccess and add the following before the block labeled Handle Front Controller:

# Serve Cached Page If Available...
RewriteCond %{REQUEST_URI} ^/?$
RewriteCond %{DOCUMENT_ROOT}/page-cache/pc__index__pc.html -f
RewriteRule .? page-cache/pc__index__pc.html [L]
RewriteCond %{DOCUMENT_ROOT}/page-cache%{REQUEST_URI}.html -f
RewriteRule . page-cache%{REQUEST_URI}.html [L]
RewriteCond %{DOCUMENT_ROOT}/page-cache%{REQUEST_URI}.json -f
RewriteRule . page-cache%{REQUEST_URI}.json [L]
