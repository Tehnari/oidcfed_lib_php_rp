Please note that this activity has been discontinued, this repository is currently unmaintained and it is still available only for historical reference
---

#
# OIDCFED_Lib_PHP_RP
#

--- ENGLISH ---

This project is an attempt to create Relying Party (RP) using the OIDCFED
(OpenID Connect Federation)
 specification, developed as a PHP language / platform library.
It is in development and is currently not recommended for use in production.
But you can try this project (source code) and send patch here, or change it
for your needs.

--- ROMANIAN ---

Acest proiect reprezintă o tentativă de creare a Relying Party (RP) cu utilizarea
 specificației OIDCFED (OpenID Connect Federation), elaborată ca librărie pentru
limbajul/platforma PHP. Este în elaborare și la moment nu este recomandat pentru
a fi utilizat în producție. Dar puteți să încercați acest proiect (cod sursă)
și să transmite-ți patch-uri cu modificările dorite, sau pentru înlătrurarea
erorilor depistate. Da și desigur puteți să utilizați ca bază pentru proiectele
dvoastră.


---
About OpenID Connect & OpenID Connec Federeations see: http://openid.net/connect/
JOSE or JSON Object Signing and Encryption, more info about JOSE can be found
by link:  https://datatracker.ietf.org/wg/jose/documents/

Some requirements:
    #	https://github.com/Spomky-Labs/jose
    #   https://github.com/jumbojett/OpenID-Connect-PHP

Tests using site: https://agaton-sax.com:8080/

P.S.: An other idea is to use / continue:
        #   https://github.com/ritou/php-Akita_OpenIDConnect
        OR
        #   https://bitbucket.org/PEOFIAMP/phpoidc/overview

In file README_Lib_Require you can see requirements for OIDCFED library
implementation. Not all for this requirements was implemented in this library.
Part of requirements was implemented in projects: kdoyen/openid-connect-php and
Spomky-Labs/jose.

For OpenID Connect instructions you can also see examples on project
kdoyen/openid-connect-php .

But later will try to implement/merge all in one code.
If someone want to complete this code: you can send a patch or you
should create an issue.

In this code using oidcfed as namespace and you should begin with class:
oidcfedClient.
All classes are in directory classes.

Some examples:

For generating key use openssl, ex.:

openssl genrsa -out foo.key 2048
openssl rsa -pubout -in foo.key -out foo.pem


openssl genrsa -out bar.key 2048
openssl rsa -pubout -in bar.key -out bar.pem

---
Installing / Using ...

Before using you should know that this project can serve as a base for
further development (or changing) for your needs.
Is done without using a Database, and if you needed please do it,
as is requirements for your projects.

1) Check structure of folders:

    |
    |
    |-> oidcfed_data
    |   |-> keys
    |
    |->oidcfed_lib_php_rp (web root, cloned from github)
    |   |-> here is source code from github.

Folders oidcfed_data and oidcfed_data/keys should be created at first
start/access of the project. But if you see some questions from web
server or acl problems just create them manually.

2) Check requirements:
 - install openssl (should be form the start, but who knows ... :) )
 - start composer to add dependencies
 - read/change variables at parameters.php and classes/configure.php
 (at this moment is better to check both files).

3) Known issues...

 - Unpacking Metadata Statements (MS) is rewrited at this moment.
 - Token saved session should be checked better (!). 
 - Scope, claims and time parameters should be checked after unpacking of the MS
is done.

(Done partially) Patch/commits with fix for this should be uploaded at first part of January 2018.

4) starting index.php will work as OIDCfed RP (should work as simple OIDC
client too, but...). If you want too check simple OIDC RP you can
use oidc_simple_test.php .
