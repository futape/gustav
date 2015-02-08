This page provides miscellaneous information about working with Gustav.

+   Whenever **linebreaks** play a role, Gustav accepts `\n` and `\r\n`, as well as `\r`, if not stated differently.
+   If not described differently, Gustav expects **absolute (server-root-relative) paths**. Due to that a directory separator is prepended to the most paths.
+   You should **never rely on `.` and `..`** path segments since these may be removed by Gustav.
+   Internally Gustav uses **UTF-8** and also creates output encoded in UTF-8. Due to that, source files should always be encoded in UTF-8, too. Moreover, template files and converter files should work with UTF-8 data, too, and should also produce such data.
+   Most functions expecting a path parameter and passing it to [`GustavBase::path`](Private-API%3a-GustavBase#string-path-stringstring-path_segment--stringstring-path_segment--stringstring---) also accept an **array value for the path parameter**. See [`GustavBase::path`](Private-API%3a-GustavBase#string-path-stringstring-path_segment--stringstring-path_segment--stringstring---) for more information.
+   Although PHP also accepts `/` as dirctory separators when running on Windows, Gustav requires to use the <strong><em>real</em> directory separator</strong>. You can use the [`replace_directory_separator` configuration option](Gustav-configuration#string-replace_directory_separator--) to replace a specific character with the system's directory separator in [GvBlock options'](GvBlock-options) values.
+   Whenever a relative URL is required, you should use a **root-relative URL** instead of a protocol-relative or a directory-relative one.



##Further reading

+   [System requirements](System-requirements)
+   [Glossary](Glossary)