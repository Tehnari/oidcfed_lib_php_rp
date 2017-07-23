#
# OIDCFED_Lib_PHP_RP
#

--- ENGLISH ---

This project is an attempt to create Relying Party (RP) using the OIDCFED (OpenID Connect Federation)
 specification, developed as a PHP language / platform library.
It is in development and is currently not recommended for use in production.

--- ROMANIAN ---

Acest proiect reprezintă o tentativă de creare a Relying Party (RP) cu utilizarea specificației
OIDCFED (OpenID Connect Federation), elaborată ca librărie pentru limbajul/platforma PHP.
Este în elaborare și la moment nu este recomandat pentru a fi utilizat în producție.


---
About OpenID Connect & OpenID Connec Federeations see: http://openid.net/connect/
JOSE or JSON Object Signing and Encryption, more info about JOSE can be found by link:
 https://datatracker.ietf.org/wg/jose/documents/

Some requirements:
    #	https://github.com/Spomky-Labs/jose
    #	https://github.com/krisrandall/OpenID-Connect-PHP

P.S.: An other idea is to use / continue:
        #   https://github.com/ritou/php-Akita_OpenIDConnect
        OR
        #   https://bitbucket.org/PEOFIAMP/phpoidc/overview

Some examples:


For generating key use openssl, ex.:

openssl genrsa -out foo.key 2048
openssl rsa -pubout -in foo.key -out foo.pem


openssl genrsa -out bar.key 2048
openssl rsa -pubout -in bar.key -out bar.pem