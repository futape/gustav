#Contributing

Everyone is welcome to contribute to this project. Just fork, clone, branch, edit, commit, push, request pull.

This repository uses the branching model developed by [Vincent Driessen](http://nvie.com/posts/a-successful-git-branching-model/). Before creating a *feature branch*, you should read that article. You may also want to use the [`git-flow`](https://github.com/nvie/gitflow) Git extension. The most important thing about the model is that you should branch off from the `develop` branch. That branch will, if accepted, be merged into its origin branch again.  
If not developing a feature, but rather a hotfix (for example, as a reaction to a bug issue), you have to create a *hotfix branch* by branching off from the `master` branch. That branch will, if accepted, be merged back into `master` **and** `develop`.

Usually, each commit on the `master` branch marks a new release and is tagged with a version number. That version number follows the [semantic versioning methodology](http://semver.org). A hotfix will increase the *patch* number, while updates on the `develop` branch will (most often) increase the *minor* number. API-incompatible updates will increase the *major* number.

Please write descriptive commit messages and fetch regularly from this repository! Also, keep track of whitespace and lineending warnings - Gustav uses Unix LF newlines!  
Moreover, please keep my coding style (variable naming etc.). I'm going to write a document describing it.



##Contributing to the documentation

When developing a new feature you may very likely also want to extend the documentation to document your feature. The documentation pages are contained in the `doc` directory.   When a pull requets is accepted, I will push the updated documentation to the GitHub wiki repository. Please note that a wiki page's title will become the corresponding file's filename, with `-`s replaced by spaces. Therefore `-`s in a wiki page's title aren't possible.
