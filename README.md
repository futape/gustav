#Gustav

<img src="https://raw.githubusercontent.com/futape/gustav/master/misc/Gustav.png" alt="Gustav Logo" width="200" />

*Gustav* is a powerful static-site gernerator written in PHP.

It provides everything you need to run a blog or any other website - From a static-site generator offering a robust templating system and a great customizability to a massive PHP API providing functions for searching the published articles and pages, getting available tags and categories, and more, as well as hooks that can be used to extend Gustav's functionality.

Moreover the project is well documented and provides lots of information on getting started and getting better with Gustav in the [GitHub Wiki](https://github.com/futape/gustav/wiki).  
For those that just want to set up Gustav quickly, click [here](https://github.com/futape/gustav/wiki/Getting-started). Others may want to read the full [documentation](https://github.com/futape/gustav/wiki) to get more information.

A demo of Gustav running in a production environment is avaiable on <http://demo.gustav.futape.de>. The source code for that website is also [available on GitHub](https://github.com/futape/demo.gustav.futape.de). Feel free to fork the repository and play around with the example code.



##Features

+   [Fast installation and setup](https://github.com/futape/gustav/wiki/Getting-started)
+   [Low system requirements](https://github.com/futape/gustav/wiki/System-requirements)
+   [Automatic generation](https://github.com/futape/gustav/wiki/Automatic-generation-of-destination-files)
+   [Customizable](https://github.com/futape/gustav/wiki/Gustav-configuration)
+   [Massive API](https://github.com/futape/gustav/wiki/API)
+   [Extendable](https://github.com/futape/gustav/wiki/Extending-Gustav)
+   [Easy to learn](https://github.com/futape/gustav/wiki/Getting-started)
+   [Well documented](https://github.com/futape/gustav/wiki)



##A word about the `master` branch

This repository has two main branches, the `develop` branch and the `master` branch.  
Branch management is done using [Vincent Driessen](http://nvie.com/posts/a-successful-git-branching-model/)'s branching model, meaning that all bleeding-edge features are available on the `develop` branch, while the `master` branch contains the stable releases only. Commits on the `master` branch introducing changes to the public API are tagged with a version number.

Versioning is done using [semantic versioning](http://semver.org/). This means that a version identifier consists of three parts, the first one being the *major* version number, the second one the *minor* version number and the third one speciying the *patch* number, separated by dots. Whenever a API-incompatible change is introduced, the major version is number increased. If the change is backwards-compatible to the public API, the minor version number is increased. A hotfix to the source increases the patch number.

A list of releases can be seen [here](https://github.com/futape/gustav/releases). Please note, that releases with a major version number of 0 belong to the initial development phase and are not considered to be absolutely stable. However, every release since version 1.0.0 is considered to be stable.



##License

The Gustav source is published under the permissive [*New* BSD Open-Source-License](http://opensource.org/licenses/BSD-3-Clause).  
A [copy of that license](https://github.com/futape/gustav/blob/master/src/futape/gustav/LICENSE) is located under `src/futape/gustav`.

Any other content like the Gustav logo or the documentation is, if not otherwise stated, published under a [Creative Commons Attribution 4.0 International License](http://creativecommons.org/licenses/by/4.0/).  
<a href="http://creativecommons.org/licenses/by/4.0/"><img alt="Creative Commons License" border="0" src="https://i.creativecommons.org/l/by/4.0/80x15.png" /></a>



##Support

<a href="https://flattr.com/submit/auto?user_id=lucaskrause&url=https%3A%2F%2Fgithub.com%2Ffutape%2Fgustav" target="_blank"><img src="http://button.flattr.com/flattr-badge-large.png" alt="Flattr this" title="Flattr this" border="0" /></a>



##Contributing

For information on contributing to Gustav, see [`CONTRIBUTING.md`](CONTRIBUTING.md).



##Author

<table><tbody><tr><td>
    <img src="http://www.gravatar.com/avatar/118bcae2fda8b302155ad47a2bfda556.png?s=100&amp;d=monsterid" />
</td><td>
    Lucas Krause (<a href="https://twitter.com/futape">@futape</a>)
</td></tr></tbody></table>

For a full list of contributors, click [here](https://github.com/futape/gustav/graphs/contributors).
