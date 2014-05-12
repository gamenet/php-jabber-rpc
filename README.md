# php-jabber-rpc #

[![Build Status](https://travis-ci.org/gamenet/php-jabber-rpc.svg?branch=master)](https://travis-ci.org/gamenet/php-jabber-rpc)
[![Latest Stable Version](https://poser.pugx.org/gamenet/php-jabber-rpc/v/stable.png)](https://packagist.org/packages/gamenet/php-jabber-rpc)
[![Code Coverage](https://scrutinizer-ci.com/g/gamenet/php-jabber-rpc/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/gamenet/php-jabber-rpc/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/gamenet/php-jabber-rpc/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/gamenet/php-jabber-rpc/?branch=master)
[![Latest Unstable Version](https://poser.pugx.org/gamenet/php-jabber-rpc/v/unstable.png)](https://packagist.org/packages/gamenet/php-jabber-rpc)
[![License](https://poser.pugx.org/gamenet/php-jabber-rpc/license.png)](https://packagist.org/packages/gamenet/php-jabber-rpc)

# About #

[mod_xmlrpc](http://www.ejabberd.im/ejabberd+integration+with+XMLRPC+API) is a module for [ejabberd](http://www.ejabberd.im/),
a XMPP/Jabber server written in Erlang. It starts a XML-RPC server and waits for external requests. Implemented calls include
statistics and user administration. This allows external programs written in any language like websites or administrative tools
to communicate with ejabberd to get information or to make changes without the need to know ejabberd internals.

One example usage is a corporate site in PHP that creates a Jabber user every time a new user is created on the website. Some
 benefits of interfacing with the Jabber server by XML-RPC instead of modifying directly the database are:

 * external programs are more simple and easy to develop and debug
 * can communicate with a server in a different machine, and even on Internet

This library is an simple wrapper above php xmlrpc module to simplify ejabberd mod_xmlrpc usage from php.

