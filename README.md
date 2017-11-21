#
# OIDCFED_Lib_PHP_RP
#

--- ENGLISH ---

This project is an attempt to create Relying Party (RP) using the OIDCFED (OpenID Connect Federation)
 specification, developed as a PHP language / platform library.
It is in development and is currently not recommended for use in production
OR in other words do if you know what yu do.

--- ROMANIAN ---

Acest proiect reprezintă o tentativă de creare a Relying Party (RP) cu utilizarea specificației
OIDCFED (OpenID Connect Federation), elaborată ca librărie pentru limbajul/platforma PHP.
Este în elaborare și la moment nu este recomandat pentru a fi utilizat în producție
Sau cu alte cuvinte utilizați numai dacă cunoașteți ce faceți.


---
About OpenID Connect & OpenID Connec Federeations see: http://openid.net/connect/
JOSE or JSON Object Signing and Encryption, more info about JOSE can be found by link:
 https://datatracker.ietf.org/wg/jose/documents/

Some requirements:
    #	https://github.com/Spomky-Labs/jose
    #   https://github.com/jumbojett/OpenID-Connect-PHP

Tests using site: https://agaton-sax.com:8080/

P.S.: An other idea is to use / continue:
        #   https://github.com/ritou/php-Akita_OpenIDConnect
        OR
        #   https://bitbucket.org/PEOFIAMP/phpoidc/overview

In file README_Lib_Require you can see requirements for OIDCFED library implementation.
Not all for this requirements was implemented in this library. Part of requirements was
implemented in projects: kdoyen/openid-connect-php and Spomky-Labs/jose.

For OpenID Connect instructions you can also see examples on project kdoyen/openid-connect-php .

But later will try to implement/merge all in one code.
If someone want to complete this code: you can send a patch or you should create an issue.

In this code using oidcfed as namespace and you should begin with class: oidcfedClient.
All classes are in directory classes.

Some examples:

For generating key use openssl, ex.:

openssl genrsa -out foo.key 2048
openssl rsa -pubout -in foo.key -out foo.pem


openssl genrsa -out bar.key 2048
openssl rsa -pubout -in bar.key -out bar.pem