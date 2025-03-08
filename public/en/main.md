# Welcome to Micro Content Management System #

Congratulation on your µCMS site configuration. To personalize your site, you
can do the following things.

## Pages ##

The pages µCMS will display are read from the root of this site, next to where
the index.php file can be found. The first level directories are named as the
[language/script code][RFC5646], like "en-US", "de-AT", "fr", etc. For example,
this very file can be found under `en/main.md`, which stand for the English
main page.

The default configuration of µCMS will look for a `main.md` file as an entry
page to your website. You can replace this file with whatever content you would
like. This page also appears on the navigation bar.

If you are using the theme that µCMS is shipped with, then you see the
navigation bar to the right when viewed on a PC, and right under the site title
on a mobile device. In this navigation bar, all the pages appear under the
language you are currently viewing. By default µCMS will look for `*.md` and
`*.markdown` files to display as entries on the navigation bar, and the entries
will be titled as the main title of your page decided by the first first-level
header found in the page[^1].

If you don't like the way the navigation bar is populated, then you can define
your own navigation layout, by creating a `nav.md` file as per the default
configuration. The main format of this file is to contain a title-less unordered
list of links, like in the sample code below, to make sure the theme designers
can match the intended design. Although you may style your navigation bar the
way you like it.

```md
- [Enter here](/en/main)
- [My hobbies](/en/hobby)
  - [Guitar](/en/hobby/guitar)
  - [My songs](/en/hobby/songs)
- [Contact with Earth](/en/contact)
```

As you can see from the above code snippet, syntax highlighting is applied.
The default theme µCMS is shipped with, uses [highlight.js][hljs] for rendering
syntax-highlighting. You may also add any other addons in the HTML template,
like [MathJax][mathjax] in your site.

There are some build-in pages that one can access, like the [about][about] page,
the [configuration][config] page and the [version][ver] page. These page aren't
designed to be accessed by users, but rather provided for legal and
troubleshooting purposes.

## Configuration ##

The configuration of µCMS is purposely placed outside of the public folder, so
it is not visible by the entire world. This way, any private information, that
you don't want publicly visible is not accessible by your visitors. Sensitive
configurations are also never going to be visible on the [configuration][config]
page either. The configuration file contains a single variable per line that you
can change to your taste. The variables are the following:

### Site Configuration ###

`TITLE`
: This is the title of your site. As the default title, it is currently set as _"Micro Content Management System"_. This is the title that will appear at the top of your browser's title bar, and the top of the site, if the theme supports it.

`DEFLANG`
: This is the language that your site defaults to, if the requested language cannot be found, and this is the first folder µCMS will search for pages inside the `DOCROOT` folder.

`COPYNOTE` _(optional)_
: If you want to set a copyright notice at the bottom of the site on all the pages, you can uncomment this variable, and define your own copyright notice line. This is an arbitrary text, so you can specify anything else other than a copyright notice. This notice will appear above the copyright notice for µCMS.

`DOCROOT` _(optional)_
: This is the path on your server µCMS will search for the language folders and any pages under them. The value is relative to the `index.php` file. This value provided, so you can separate the pages from µCMS files.

`MAINPAGE` _(optional)_
: Specifies the main page for each language the sites open up, when a visitor does not specify a path. The default value for this is `'main'`.

`NAVPAGE` _(optional)_
: Specifies the page overriding the content of your navigation page. Initially this value is commented out, so it does not take affect.

### Markdown Extra ###

µCMS uses [Michel Fortin's PHP Markdown][php-md] library. It is what drives the
parsing of the markdown pages. While you can't directly assign values for the
settings for the library, µCMS provides the means to set these properties
through the following variables.

> **Note:** These descriptions are generated with the help of AI, as there way
> too many of them to do them by hand under short notice.

`TAB_WIDTH`
: Defines the number of spaces a tab character represents in code blocks.

`HARD_WRAP`
: If set to `true`, all newlines will be converted to `<br>` tags.

`NO_MARKUP`
: If set to `true`, raw HTML tags will not be processed.

`NO_ENTITIES`
: If set to `true`, HTML entities will not be expanded.

`PREDEF_URLS`
: An associative array that defines predefined reference-style link URLs.
:
: Example: `$PREDEF_URLS = array('ucms-home' => 'https://www.jadaml.website/ucms');`

`PREDEF_TITLES`
: An associative array that defines predefined reference-style link titles.
:
: Example: `$PREDEF_TITLES = array('ucms-home' => 'µCMS Home');`

`URL_FILTER_FUNC`
: A callback function used to modify URLs before they are output.

`HEADER_ID_FUNC`
: A callback function used to generate `id` attributes for headers.

`CODE_BLOCK_CONTENT_FUNC`
: A callback function for processing content inside fenced code blocks.

`CODE_SPAN_CONTENT_FUNC`
: A callback function for processing content inside inline code spans.

`ENHANCED_ORDERED_LIST`
: If set to `true`, ordered lists will support different numbering styles like `1.` and `1)`.

`FN_LINK_CLASS`
: Defines the CSS class for footnote reference links.

`FN_BACKLINK_CLASS`
: Defines the CSS class for footnote backlink links.

`FN_BACKLINK_LABEL`
: Defines the label for footnote backlinks. If empty, no label is added.

`FN_BACKLINK_HTML`
: Defines the HTML content for footnote backlinks.

`CODE_CLASS_PREFIX`
: Defines the class prefix for fenced code blocks.

`CODE_ATTR_ON_PRE`
: If set to `true`, attributes will be placed on the `<pre>` tag instead of the `<code>` tag.

`TABLE_ALIGN_CLASS_TMPL`
: Defines the template for table alignment classes.
:
: Example: `$TABLE_ALIGN_CLASS_TMPL = 'align-%%';`

`PREDEF_ABBR`
: An associative array that defines predefined abbreviations.
:
: Example: `$PREDEF_ABBR = array('CMS' => 'Content Management System', 'µCMS' => 'Micro Content Management System');`

`HASHTAG_PROTECTION`
: If set to `true`, prevents hashtags from being interpreted as headers.

`TAGS_TO_SKIP`
: Defines a list of HTML tags inside which SmartyPants processing is skipped.
:
: Example: `$TAGS_TO_SKIP = 'pre|code|kbd|script|style|math';`

### SmartyPants Typographer ###

µCMS uses [Michel Fortin's PHP SmartyPants][php-sp] library. It is what drives
the parsing of character sequence for controlling the typographic elements in
your page. While you can't directly assign values for the settings for the
library, µCMS provides the means to set these properties through the following
variables.

> **Note:** These descriptions are generated with the help of AI, as there way
> too many of them to do them by hand under short notice.

`DO_NOTHING`
: If set to `true`, SmartyPants processing is disabled.

`DO_QUOTES`
: Controls automatic conversion of straight quotes to curly quotes.
: Current behavior: 'quoted', "quoted"

`DO_BACKTICKS`
: Controls how backticks are converted to typographic quotes.
: Current behavior: single -- `, double -- ``
: Possible values:

- `0`: No conversion
- `1`: Convert double backticks
- `2`: Convert both single and double backticks

`DO_DASHES`
: Controls how hyphens are converted.
: Current behavior: double: --, triple: ---

- `0`: No conversion
- `1`: Convert `--` to `&ndash;`
- `2`: Convert `--` to `&ndash;` and `---` to `&mdash;`

`DO_ELLIPSES`
: If set to `1`, converts `...` to `&hellip;`.
: Current Behavior: ...

`CONVERT_QUOT`
: If set to `1`, converts `"` to `&quot;`.

`SMART_DOUBLEQUOTE_OPEN`
: Defines the entity for opening double quotes.
: Default: `&#8220;`.

`SMART_DOUBLEQUOTE_CLOSE`
: Defines the entity for closing double quotes.
: Default: `&#8221;`.

`SMART_SINGLEQUOTE_OPEN`
: Defines the entity for opening single quotes.
: Default: `&#8216;`.

`SMART_SINGLEQUOTE_CLOSE`
: Defines the entity for closing single quotes.
: Default: `&#8217;`.

`BACKTICK_DOUBLEQUOTE_OPEN`
: Defines the entity for opening double quotes using backticks.
: Default: `&#8220;`.

`BACKTICK_DOUBLEQUOTE_CLOSE`
: Defines the entity for closing double quotes using backticks.
: Default: `&#8221;`.

`BACKTICK_SINGLEQUOTE_OPEN`
: Defines the entity for opening single quotes using backticks.
: Default: `&#8216;`.

`BACKTICK_SINGLEQUOTE_CLOSE`
: Defines the entity for closing single quotes using backticks.
: Default: `&#8217;`.

`EM_DASH`
: Defines the entity for an em-dash (`---`).
: Default: `&#8212;`.

`EN_DASH`
: Defines the entity for an en-dash (`--`).
: Default: `&#8211;`.

`ELLIPSIS`
: Defines the entity for an ellipsis (`...`).
: Default: `&#8230;`.

`DOUBLEQUOTE_LOW`
: Defines the entity for low double quotes.
: Default: `&#8222;`.

`GUILLEMET_LEFTPOINTING`
: Defines the entity for left-pointing guillemets.
: Default: `&#171;`.

`GUILLEMET_RIGHTPOINTING`
: Defines the entity for right-pointing guillemets.
: Default: `&#187;`.

`GERESH`
: Defines the entity for the Hebrew Geresh character.
: Default: `&#1523;`.

`GERSHAYIM`
: Defines the entity for the Hebrew Gershayim character.
: Default: `&#1524;`.

`DO_COMMA_QUOTES`
: If set to `1`, converts comma quotes.
: Current behavior: ,,quoted"

`DO_GUILLEMETS`
: If set to `1`, enables conversion of guillemets (`<<`, `>>` to `«`, `»`).
: Current behavior: <<, >>

`DO_GERESH_GERSHAYIM`
: If set to `1`, enables conversion of Geresh and Gershayim marks.
: Current behavior: צ'ארלס, צה"ל

`DO_SPACE_COLON`
: If set to `1`, enables the addition of a non-breaking space before colons.
: Current behavior: example :

`DO_SPACE_SEMICOLON`
: If set to `1`, enables the addition of a non-breaking space before semicolons.
: Current behavior: 1; 2 ;

`DO_SPACE_MARKS`
: If set to `1`, enables the addition of a non-breaking space before certain punctuation marks.
: Current behavior: ︎¡Exclaim! ¿Question?

`DO_SPACE_EMDASH`
: If set to `1`, enables the addition of a space before em dashes.
: Current behavior: Hello—world. Hello — world.

`DO_SPACE_ENDASH`
: If set to `1`, enables the addition of a space before en dashes.
: Current behavior: Hello–world. Hello – world.

`DO_SPACE_FRENCHQUOTE`
: If set to `1`, enables the addition of a space inside French-style quotation marks.
: Current behavior: «allô», « allô »

`DO_SPACE_THOUSANDS`
: Controls the spacing format for thousands separators.
: Current behavior: 10000

`DO_SPACE_UNIT`
: Controls the addition of non-breaking spaces before unit symbols.
: Current behavior: 12kg, 12 kg

`SPACE_EMDASH`
: Defines the spacing applied before em dashes.

`SPACE_ENDASH`
: Defines the spacing applied before en dashes.

`SPACE_COLON`
: Defines the spacing applied before colons.

`SPACE_SEMICOLON`
: Defines the spacing applied before semicolons.

`SPACE_MARKS`
: Defines the spacing applied before certain punctuation marks.

`SPACE_FRENCHQUOTE`
: Defines the spacing applied inside French-style quotation marks.

`SPACE_THOUSAND`
: Defines the spacing used for thousands separators.

`SPACE_UNIT`
: Defines the spacing applied before unit symbols.

`SPACE`
: Defines the regex pattern used for spaces, including non-breaking spaces.

[^1]: The first implementation of µCMS expects the first first-level header to be found on the first line.

[RFC5646]: https://datatracker.ietf.org/doc/html/rfc5646
[hljs]: https://highlightjs.orgc
[mathjax]: https://www.mathjax.org/
[about]: /index.php?*about
[config]: /index.php?*config
[ver]: /index.php?*version
[php-md]: https://michelf.ca/projects/php-markdown/
[php-sp]: https://michelf.ca/projects/php-smartypants/
