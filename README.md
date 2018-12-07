# fix data migration for grouped

The Magento data migration tool up to (at least) versions 2.2.7 and 2.3.0 has
a bug, which removes the "apply_to" fields for (e.g.) price.
This causes the price field to appear for grouped products. See this 
[issue](https://github.com/magento/data-migration-tool/issues/574).

#How to install

There is currently no direct composer integration, so put

``` 
{
   "type": "vcs",
   "url": "git@github.com:wilfriedwolf/data-migration-tool.git"
}
```
to the "repositories" section and

```
 "snm/magento2-module-fixdatamigrationforgrouped": "dev-master",
```

to the "require" section in your composer.json. 
Alternatively you can download the source code and put it in the appropriate folders.
Since the tool contains a plugin executing `bin/magento setup:di:compile` is required before
starting the migration.

## Contribute

Feel free to **fork** and contribute to this module. Simply create a pull request and we'll 
review and merge your changes to master branch.

## About Sandstein Neue Medien

Sandstein Neue Medien is an internet agency located in Dresden/Germany. 
For more information, please visit [www.sandstein-neue-medien.de](https://www.sandstein-neue-medien.de).