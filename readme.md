# The drag0n Installer: 0.2 ALPHA-PREVIEW

Have you ever had the problem, that you needed to install a package, but you weren't familar wit compiling from source?
Or...have you ever just needed a library real quick, like `libcurl`?
Or just the bare build environment with `GCC`, `G++` and `make`?

Oh yeah, I know how you feel. Especially when you then try to install package managers, and they just totally mess up your compiles. That is exactly what a package manager shouldn't do - seriously. As well as their purpose might be - no...just no.

`apt` installs everything to root (/). So, it installs things into their place. And that is **EXACTLY** what `drag0n` will be doing!


## Something about it.

drag0n is nearly fully PHP based, packed with additional modules and packages, to make the best out of this powerful scripting language. Basically, I dont like to remember it as a "pre hypertext processor". That is not what it is. It's a real scripting language with some things that make it look more like a real programm - especialyl when you add extensions like I did to enable _multithreading_. This is so different from what you think, is it? PHP, a multithreading scripting language. And if i could have gotten `bcompiler` to work, i even would have some binaries made out of PHP completely.


# Installation

Err...easy. Copy drag0n.app into /Applications, or whever you want it. If you want to impersonate my path: `/Applications/drag0n/drag0n.app`
That's it. ^3^


# What comes with drag0n?

With a copy of drag0n, you get:
- [PHP](http://php.net) 5.5.3
    - [pthreads](https://github.com/bwoebi/pthreads) 0.45-rc
    - original headers!
- [Yii](http://yiiframework.com) 1.1.14
- [Spyc](https://github.com/tekimaki/spyc)
- [PHPLinq](http://phplinq.codeplex.com)
- [GCC](http://hpc.sourceforge.net) 4.9
	- included libgcc with headers
- [Make](http://www.gnu.org/software/software.html) 3.82
- [dylibbundler](http://macdylibbundler.sourceforge.net) 0.4.1
- [node.js](http://nodejs.org) 0.8.0
- [appjs](https://github.com/appjs/appjs) 0.20
- Precompiled libcurl, libmcrypt, icu, tidy, libpng and GD dynamic libraries
- [CocoaDialog](https://github.com/mstratman/cocoadialog)
- [wget](http://www.techtach.org/wget-prebuilt-binary-for-mac-osx-lion) 1.13


## Wait, what, MULTITHREADING?!

Oh yes, you totally got this right.
@bwoebi created the `pthreads` extension, that gives PHP coders three classes:

- Thread
- Worker
- Stackable

The first is self-explaining, whilst the second and third are stuck together. A worker is a reusable thread. So what we will be able to do in drag0n is, simultaneous downloads and other things - such as starting another script to fetch updates while we're doing other things...in the short, a lot of things are now possible.


## But...its PHP - how did it just go...DESKTOP?

The answer here is appJS - @milani's work.
appJS is a...err... Hm. I would say it's a library. This library allows me to spawn a browser window. But unlike node-webkit or such things, I can actually manipulate the router function - THAT function that serves the files! So in here, I was able to emulate a CGI environment, and plugged a window management into PHP. Because i can operate forth and back. From within the "webview", I can then still use JavaScript to use node.js related functions. So I could trigger another PHP script to execute in the background and log its output into the integrated console - and once done, can tell the webview to change.
As you can see; I am able to do quite a lot of new things. with PHP. 


## There is GCC in there...

Yup! Absolutely! If we have a package that comes as source code, or if you are compiling from source, you can just go ahead and use these tools. That said, you do not need to stick to Apple. Ok, we don't have some certain programs, but in the most cases you don't even need them. :)


## You use Spyc, why not the actual module?

*Yaaaawn*. Lazyness bro! :D
The work that @tekimaki did, is inspiring! And, I am able to patch and hack about as well. So, the native PHP module would parse YAML files into arrays - with my own addition `SpycObject` I am parsing it into an ArrayObject instead - means I am staying OOP. Why? ...well, I hate typing `$variable['key']`... o-o
But really, having a PHP module like that is very useful, and tutoring too.


## I know LINQ from c## - but what is it doing here in PHP?

This confusing piece of software really caught my eye. I am about to be implementing it into the drag0n PHP core to save some `foreach`'es. =)


## Dylib...bundler?

Exactly. Since my PHP is a custom compile, you will most probably have a problem: You don't meet my dependencies. With this utility however I was able to bundle the dylibs into my package - and using the environment variable `DYLD_LIBRARY_PATH`, I can just add a path to the linker to look at and for dynamic libraries. So if your libs dont fit, it'll fall back to mine =)


## CocaDialog and wget?

1st: I really dont like using cURL from the command line. `wget` is just sooo self explaining~! So I included a pre-compile, since I am going to use this for updating the app. Yes, for the update, I will most probably fall back to ordinary shell scripts. These can use CocoaDialog to give you nice output while it updates the internal stuff.


## I found a file called "Apple.php" - what is that?

The `Apple`-class is something I wrote myself, which is especially for making it easier to work in the Mac OS X environment. I will have a Windows and UNIX class, depending on the OS I will distribute this app on/for.


# Contributing, Porting, that...

So, you maybe want to have this run on -insert platform here-. To do this, make sure you have a Mac OS environment. Use the php-wrapper script in drag0n.app/Contents/System/bin to run the internal PHP and check for it's config-flags. Example:
```bash
/Applications/drag0n/drag0n.app/Contents/System/bin/php-wrapper -i
```
That should do it. 

Now, fetch the config-flags and take a note. Next, download pthreads from the link above and place it into the ext/ folder of the PHP source. Then start compiling with the config flags given from the version I delivered.

Once done, you have basically what you need to get the base running, since appjs is distributed as a pre-compile. Fetch it there, and start rebuilding my structure with the new versions. I tried to keep my structure very Apple-like, but you can make changes to the structure. PHP files aren't really path-dependent.


# HAVE FUN!

Gimme feedback, PR's or whatever you would liek to give me :)


# A load of mentions and thanks and credits...
@milani : AppJS is what made this possible. Thank you for this lib, please continue it!
@tekimaki : Thanks to Spyc, I now have a foolproof configuration file format. This is plain amazing!
@bwoebi : pthreads is going to change the way I will be coding PHP in the future, dramatically. Thanks for helping me getting it to work! Keep up the great work!
@mstratman : CocoaDialog makes it possible to keep my users from staring into a terminal when I perform updates to the behind-the-scenes things. Great work there!