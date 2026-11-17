# files-site-uploader

This is a basic PHP form/site with stylesheet to allow uploads to a web server.

When visited it will work as a normal page and has a file upload box. Select a file, type a path relative to the domain (like `media/videos`) hit upload and it goes.

There are some checks and debug information available (some commented out) as well. Use the included stylesheet for light/dark mode and fonts. Thats about it.

## Warning
This form/site is vulnerable to path traversal attacks. I recommend not using it at all but if you do, lock it behind authentication like HTTPS Basic Authorization.

I have not fixed it because I have it well secured in production and I am the only one using it. You can easily traverse paths and upload to any directory your PHP can access.
