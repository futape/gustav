#Contributing

Everyone is welcome to contribute to this project. Just fork, clone, branch, edit, commit, push, request pull.

This repository uses the branching model developed by [Vincent Driessen](http://nvie.com/posts/a-successful-git-branching-model/). Before creating a *feature branch*, you should read that article. You may also want to use the [`git-flow`](https://github.com/nvie/gitflow) Git extension. The most important thing about the model is that you should branch off from the `develop` branch. That branch will, if accepted, be merged into its origin branch again.  
If not developing a feature, but rather a hotfix (for example, as a reaction to a bug issue), you have to create a *hotfix branch* by branching off from the `master` branch. That branch will, if accepted, be merged back into `master` **and** `develop`.

Each commit on the `master` branch marks a new release and is tagged with a version number. That version number follows the [semantic versioning methodology](http://semver.org). A hotfix will increase the *patch* number, while updates on the `develop` branch will (most often) increase the *minor* number. API-incompatible updates will increase the *major* number.

Please write descriptive commit messages and fetch regularly from this repository! Also, keep track of whitespace and lineending warnings!



##Contributing to the documentation

Contributing to the documentation is currently not available via Git. However, you can send me the documentation for you feature by another way.
