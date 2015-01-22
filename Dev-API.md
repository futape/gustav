The Dev API contains class member that are not defined as `private` or those that should be but aren't due to technial restrictions for example. Moreover this section doesn't describe the publically available members, rather it describes the *real* members used to implement the public functionality. For example, the Dev API would describe the `__call()` method, while the public API would describe the methods made available using that method.  
Please note, that, like the private API's ones, any class members documented in this section may change without releasing a new major version of Gustav. Therfore you should not rely on these members if you wish to update easily to future (non-major) versions.  
The Dev API is spread across the following Gustav classes.

+   [`Gustav`](Dev-API:-Gustav)
+   [`GustavSrc`](Dev-API:-GustavSrc)
+   [`GustavDest`](Dev-API:-GustavDest)
+   [`GustavContent`](Dev-API:-GustavContent)
+   [`GustavBlock`](Dev-API:-GustavBlock)
+   [`GustavGenerator`](Dev-API:-GustavGenerator)
+   [`GustavBase`](Dev-API:-GustavBase)
+   [`Hooks classes`](Dev-API:-Hooks-classes)