## xoob/ResqueBoard

Fork of the upstream ResqueBoard. Fixes a few bugs and ships with
a patched Cube server.

### Install

**Mac OS X**

Before first run, install the required dependencies:

```bash
# MongoDB
brew install mongodb
ln -sfv /usr/local/opt/mongodb/*.plist ~/Library/LaunchAgents
launchctl load ~/Library/LaunchAgents/homebrew.mxcl.mongodb.plist

# php-redis
brew install php55-redis
```

Then set up ResqueBoard:

```bash
git clone git://github.com/xoob/ResqueBoard.git resque-board
cd resque-board
curl -s https://getcomposer.org/installer | php
php composer.phar install --no-dev --prefer-dist
```

Start the Cube server and then a PHP development server:

```bash
php bin/resque-board.php
```

Open the board on http://0.0.0.0:8888/

----

#ResqueBoard [![Build Status](https://travis-ci.org/kamisama/ResqueBoard.png?branch=dev)](https://travis-ci.org/kamisama/ResqueBoard) [![Coverage Status](https://coveralls.io/repos/kamisama/ResqueBoard/badge.png)](https://coveralls.io/r/kamisama/ResqueBoard)

ResqueBoard is an analytics software for PHP Resque. Monitor your workers health and job activities in realtime.

Unlike the [original resque](https://github.com/defunkt/resque/#the-front-end), that display only what's happening right now, ResqueBoard remembers and saves  everything to compute metrics about your jobs and workers health in realtime.

Learn more on the [official website](http://resqueboard.kamisama.me), or take a look at the [demo](http://resque.kamisama.me/).

##Goals
ResqueBoard is built for 2 objectives :

* see what's happening right now in realtime
* visualize what's happened in the past with various charts, to easily benchmarks and balance your workers

##Minimum requirements

Although ResqueBoard is easy to install and run, you should not run it on a very basic webserver. It requires a minimum of processing power and memory for the various computation, and data storage.
