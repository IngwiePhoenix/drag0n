# The drag0n Installer: 0.3 Deskshell-Alpha

Have you ever had the problem, that you needed to install a package, but you weren't familar wit compiling from source?

Or...have you ever just needed a library real quick, like `libcurl`?

Or just the bare build environment with `GCC`, `G++` and `make`?


Oh yeah, I know how you feel. Especially when you then try to install package managers, and they just totally mess up your compiles. That is exactly what a package manager shouldn't do - seriously. As well as their purpose might be - no...just no.


`apt` installs everything to root (/). So, it installs things into their place. And that is **EXACTLY** what `drag0n` will be doing!



## Something about it.

drag0n is nearly fully PHP based, packed with additional modules and packages, to make the best out of this powerful scripting language. Basically, I dont like to remember it as a "pre hypertext processor". That is not what it is. It's a real scripting language with some things that make it look more like a real programm - especialyl when you add extensions like I did to enable _multithreading_. This is so different from what you think, is it? PHP, a multithreading scripting language. And if i could have gotten `bcompiler` to work, i even would have some binaries made out of PHP completely.


## Hackery with OS X

To make Mac OS treat one bundle as two individual apps, we have to swap Info.plist files. It is quite funny that it 1) works and 2) is so dirty but works. But really, I just had no other oslution than to implement InfoSwap, a little class with just two stati methods: *toDrag0n* and *toChromium*. These methods swap the Info.plist file to either the one of drag0n or chromium. Sounds easy, is tricky. Consider what happens if our parent process gets terminated somehow? I am building a stack of three processes right here; php 5.3.3 (OS X default) -> php 5.5.3 (drag0n-php) -> Chromium 32. The first and second process will be ran in the background - whilst the first swaps the Info.plist files, so that Chromium launches correctly. drag0n uses LSUIElement=1, and Chromium -=0. As you realize, it is really a lot of hackery.

Drag0n is a big pile of hackery, toat put together, results into a very nice app. If you are searching for some, lurk thru drag0n. ;)


# Installation

As I now have 3 submodules, you need to clone with all submodules:

    git clone --recursive https://github.com/IngwiePhoenix/drag0n.git
    
Then just double-click drag0n. But if you are a developer, you may want to re-generate my environment. Do this by cloning straight into /Applications, like:

    $ cd /Applications
    $ git clone --recursive https://github.com/IngwiePhoenix/drag0n.git
    
That way you'll get a path like `/Applications/drag0n/drag0n.app`. That is exactly the way I built my stuff :)


# What comes with drag0n?

With a copy of drag0n, you get:

- [PHP](http://php.net) 5.5.3
    - [pthreads](https://github.com/krakjoe/pthreads) 0.45-rc
    - gnupg extension
    - ssh2 extension
    - curl extension
    - sockets enabled
    - pear and phar enabled
    - original headers
- [Yii](http://yiiframework.com) 1.1.14
- [Spyc](https://github.com/tekimaki/spyc) {Submodule}
- [PHPLinq](http://phplinq.codeplex.com)
- [GCC](http://hpc.sourceforge.net) 4.9
	- included libgcc with headers
- [Make](http://www.gnu.org/software/software.html) 3.82
- [dylibbundler](http://macdylibbundler.sourceforge.net) 0.4.1
- [Deskshell](https://github.com/sihorton/appjs-deskshell) 0.8, customized
- Precompiled libcurl, libmcrypt, icu, tidy, libpng and GD dynamic libraries
- [CocoaDialog](https://github.com/mstratman/cocoadialog)
- [wget](http://www.techtach.org/wget-prebuilt-binary-for-mac-osx-lion) 1.13
- [phpws](https://github.com/Devristo/phpws) {Submodule}
- [WebServerPHP](https://code.google.com/p/php-webserver)
- [WingStyle](https://github.com/IngwiePhoenix/WingStyle) {Submodule}



# Key features of drag0n
- Installs custom packages from different resources.
- It is modular, so you can make your own wrapper for different repository types (Git, APT, etc) so we do not need just one format but can use existing ones.
- Usable thru CLI, GUI and URI.
    - CLI syntax: d0 _action_ _method_ [_arg1_, _arg2_, ...]
    - URI syntax: d0:action/method?arg1&arg2
        - Please note. When using the URI, the user will be asked first if the action should be performed or not to prevent evil code.
    - Actions: install, uninstall, resource, pkg, dev
    - Install: url [url], id [id appearing in list of available packaes], file [path]
    - Uninstall: id [id]
    - resource: add [url or id listed in DIDR], remove [id or name]
    - pkg [id]
    - dev: build-env, build-pkg [folder containing package], build-res [output dir (ftp/sftp/file)], [resource info], [folder with d0p files]
- Purely written in PHP. All and every piece of code is open source.
- Works out of the box with no own dependencies, since everythign needed is included.
- Extreme convinience: All resources are listed or can be listed, so long url's can be replaced by short names.
- Easily readable configuration files (YAML)
- Included build system to replace the need to lurk them all up - they're all in one place.
- Very fast and intelligent, can detect already-installed applications and list them in the interface and makes them update-able thru it too.
- A lot of under-the-hood hackery that can act as a nice code resource for other developers.
- Can interact with other programs to gain more information about the system and set everything up in a very convinient way.
- Installs everything into the default locations (such as /usr/local).
- Offers pre and post scripts for install, update, reinstall and remove to ensure that developers can do everything needed to fine tune a package.
- Will soon also offer possibility to reboot the os and install things at boot time if needed (will use nvram and launchDaemons).



## Wait, what, MULTITHREADING?!

Oh yes, you totally got this right.

[@krakjoe](https://github.com/krakjoe) created the `pthreads` extension, that gives PHP coders three classes:

- Thread
- Worker
- Stackable

The first is self-explaining, whilst the second and third are stuck together. A worker is a reusable thread. So what we will be able to do in drag0n is, simultaneous downloads and other things - such as starting another script to fetch updates while we're doing other things...in the short, a lot of things are now possible.



## PHP as a desktop app?

The answer here is appJS - [@milani](https://github.com/milani)'s work.

appJS is a...err... Hm. I would say it's a library. This library allows me to spawn a browser window. But unlike node-webkit or such things, I can actually manipulate the router function - THAT function that serves the files! So in here, I was able to emulate a CGI environment, and plugged a window management into PHP. Because i can operate forth and back. From within the "webview", I can then still use JavaScript to use node.js related functions. So I could trigger another PHP script to execute in the background and log its output into the integrated console - and once done, can tell the webview to change.

As you can see; I am able to do quite a lot of new things. with PHP. 

__UPDATE__: Now we run on [@sihorton](http://github.com/sihorton)'s [Deskshell](http://github.com/sihorton/appjs-deskshell). It is the evolution of AppJS and defines the new standart in drag0n. I currently had to exclude the shell functionality in the terminal, but it'll return soon :)


## A build environment within an app?

Yup! Absolutely! If we have a package that comes as source code, or if you are compiling from source, you can just go ahead and use these tools. That said, you do not need to stick to Apple. Ok, we don't have some certain programs, but in the most cases you don't even need them. :)

We will soon include the following set of build tools. Please note, all the current releases are "Debug releases". The offical release will have these stripped, but can be added by just installing a package to do that. Users will be granted the option of downloading a full (Debug+Release) bundle or just a Release bundle. The debug bundle adds additional tools and some re-written components.

Build tools that will be included:

- Ninja
- Clang
- GCC
- G++
- Make

That basically makes up everything you need for building.


## You use Spyc, why not the actual module?

*Yaaaawn*. Lazyness bro! :D

The work that [@tekimaki](https://github.com/tekimaki) did, is inspiring! And, I am able to patch and hack about as well. So, the native PHP module would parse YAML files into arrays - with my own addition `SpycObject` I am parsing it into an ArrayObject instead - means I am staying OOP. Why? ...well, I hate typing `$variable['key']`... o-o

But really, having a PHP module like that is very useful, and tutoring too.



## I know LINQ from MS' C# - but what is it doing here in PHP?

This confusing piece of software really caught my eye. I am about to be implementing it into the drag0n PHP core to save some `foreach`'es. =)



## Dylibbundler?

Exactly. Since my PHP is a custom compile, you will most probably have a problem: You don't meet my dependencies. With this utility however I was able to bundle the dylibs into my package - and using the environment variable `DYLD_LIBRARY_PATH`, I can just add a path to the linker to look at and for dynamic libraries. So if your libs dont fit, it'll fall back to mine =)



## CocaDialog and wget?

1st: I really dont like using cURL from the command line. `wget` is just sooo self explaining~! So I included a pre-compile, since I am going to use this for updating the app. Yes, for the update, I will most probably fall back to ordinary shell scripts. These can use CocoaDialog to give you nice output while it updates the internal stuff.



## I found a file called "Apple.php" - what is that?

The `Apple`-class is something I wrote myself, which is especially for making it easier to work in the Mac OS X environment. I will have a Windows and UNIX class, depending on the OS I will distribute this app on/for.



# Contributing, Porting, that...

A repository called drag0n-src will be opened up properly soon. It includes the whole lot of dependencies and information to build drag0n. A lot of hand-work will be needed still to get it up and running for your OS. It will compile neatly for MacOS of course, but not for anything else as of now.



# HAVE FUN!

Gimme feedback, PR's or whatever you would liek to give me :)



# A load of mentions and thanks and credits...

[@milani](https://github.com/milani) : AppJS is what made this possible. Thank you for this lib, please continue it!

[@tekimaki](https://github.com/tekimaki) : Thanks to Spyc, I now have a foolproof configuration file format. This is plain amazing!

[@krakjoe](https://github.com/krakjoe) : pthreads is going to change the way I will be coding PHP in the future, dramatically. Thanks for helping me getting it to work! Keep up the great work!

[@mstratman](https://github.com/mstratman) : CocoaDialog makes it possible to keep my users from staring into a terminal when I perform updates to the behind-the-scenes things. Great work there!

[@bwoebi](https://github.com/bwoebi) : He contributed some patches so far, and is testing drag0n on a MacBook Pro with a Retina display - something I dont have right here :p That way I (or we in that case) can ensure that we'll be retina-ready at some point. :)

[@sihorton](https://github.com/sihorton) : Sihorton started the Deskshell project. It now is the new standart behind drag0n. Expect much more to come.
In fact, drag0n will end up being the "app-store" for Deskshell apps :)