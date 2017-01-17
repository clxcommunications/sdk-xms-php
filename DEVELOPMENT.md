REST API SDK for SMS
====================

Some development documentation for the PHP REST API SDK for SMS.

Release procedure
-----------------

The following steps are necessary to perform a release of the SDK:

1. Update to release version in `src/Version.php` and `CHANGELOG.md`.

3. Commit the changes and add a release tag.

4. Generate PHP docs and commit to `gh-pages` branch.

5. Prepare `src/Version.php` and `CHANGELOG.md` for next development cycle.

6. Commit again.

7. Push it all to GitHub.

8. Log in to Packagist and press "Update" on the sdk-xms package.
