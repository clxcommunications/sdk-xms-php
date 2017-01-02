REST API SDK for SMS
====================

This is the PHP SDK for the CLX Communications REST API (also called
XMS) for sending and receiving single or batch SMS messages. It also
supports scheduled sends, organizing your frequent recipients into
groups, and customizing your message for each recipient using
parameterization.

This library is compatible with PHP 5.6 and later.

Using
-----

The SDK is packaged using [Composer](https://getcomposer.org/) and is
available in the [Packagist](https://packagist.org/) repository under
the name `clxcommunications/sdk-xms`.

License
-------

This project is licensed under the Apache License Version 2.0. See the
LICENSE.txt file for the license text.

Release procedure
-----------------

The following steps are necessary to perform a release of the SDK:

1. Update to release version in `src/Version.php` and `CHANGELOG.md`.

3. Commit the changes and add a release tag.

4. Prepare `src/Version.php` and `CHANGELOG.md` for next development cycle.

5. Commit again.

6. Push it all to GitHub.
