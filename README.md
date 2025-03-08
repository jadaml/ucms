# Micro Content Management System

*[CMS]: Content Management System
*[µCMS]: Micro Content Management System
*[VS Code]: Visual Studio Code

Micro Content Management System (µCMS) is a lightweight CMS software, which allow users to provide the content in Markdown files. The hierarchy of the pages are defined by the file hierarchy the markdown files are placed, and every page is queried with GET requests, thus eliminating the need to maintain a database on the host.

Originally I have created µCMS as a practice to learn PHP in the early 2000s, I have released it as a means to quickly create a CMS website, without much knowledge on building one. Although it is severely inadequate compared to other modern solution, this project remains only as an option to those, who wants to deepen their web server management skill, by introducing a tool that does not provide much help in maintaining the site for them, forcing their hands to a more hands-on experience, as technologically it is severely limited. That limitation is the reason it is called micro-CMS.

When referring to µCMS in a context, where only ASCII characters are available, you may refer to this software as either mCMS or uCMS—though I personally don't like the typesetting of 'µ' as 'u' despite the project package name uses it.

## Setup development environment

## Install µCMS

### Download dependencies

If you have downloaded a release package, then you only need to extract the package to your desired location.

If you are cloning this repository, yoiu need Composer to be installed, and execute the following command first:

```sh
composer install
```

after that, you will need to copy the following files to your desired destination:

- config
  - ucms.php
- public
  - css
    - style.css
    - theme.css
  - en
    - err.md
    - main.md
  - images
    - ucms.png
  - .htaccess
  - index.php
- resources
  - template.html
- src
  - langs.php
  - md_sp.php
  - navlist.php
  - pages.php
  - utils.php
- vendor
  - *Just copy the entire folder. this document would go on for ages if I'd list all the files.*

### Set Up Webserver

Make sure that the files have the appropriate rights for your server to read them. µCMS does not write to it's files, and thus does not need the files to be writable.

Then you need to set your web server's document root to the public folder you have copied. So for example, if you have copied all the above files to the /var/www path, then you would set the document root to /var/www/public.

If you want, you can change the name of the public folder, but make sure that the document root is the changed folder.

If you would like to hide "index.php" in the URL, you also need to enable the rewrite module, so .htaccess files are considered by the server. When enabled, /index.php?dir/page requests are translated to /dir/page requests.

### Configuration

While the default configuration of µCMS is set so it can be immediately used, there are some configuration options for your convenience. These configurations are also configured in the shipped [main.md](public/en/main.md) file.
