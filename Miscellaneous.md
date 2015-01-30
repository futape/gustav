This page provides miscellaneous information about working with Gustav.

+   Whenever linebreaks play a role, Gustav accepts `\n` and `\r\n`, as well as `\r`, if not stated differnetly.
+   If not described differently, Gustav expect absolute (server-root-relative) paths. Due to that a directory separator is prepended to the most paths.
+   You should never rely on `.` and `..` path segments since these may be removed by Gustav.
+   Internally Gustav uses UTF-8 and also creates output encoded in UTF-8. Due to that, source files should always be encoded in UTF-8, too. Moreover, template files and converter files should work with UTF-8 data, too, and should also produce such data.
+   Gustav should work fine in any OS in any server software. It requires PHP 5.3+. The following systems have been tested.
    
    +   Apache 2.2, PHP 5.3.29
+   Most functions expecting a path parameter and passing it to [`GustavBase::path`](Private-API%3a-GustavBase#string-path-stringstring-path_segment--stringstring-path_segment--stringstring---) also accep an array value for the path parameter. See [`GustavBase::path`](Private-API%3a-GustavBase#string-path-stringstring-path_segment--stringstring-path_segment--stringstring---) for more information.



##Further reading

+   [Terminology](Terminology)