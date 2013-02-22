# Simple Magento 1.x Module Starter
## by Ali Bawany

## Summary:
This simple PHP script, run from a command line, will enabled you to create the basic directory structure for a Magento 1.x module within a running Magento instance. If things work well, your running Magento instance will show the module as follows:
* Go to Magento Admin
* Navigate to System -> Configuration -> Advanced
* Expand "Disable Modules Output" table and search for your defined module within the list

## Instructions:
* Usage is as follows:
```
# php -f Magento1xModuleStarter.php
usage: Magento1xModuleStarter.php <mage basedir> <namespace> <mod_name> 
```

* In the above, you can see that the script requires a base directory where Magento  is installed, a namespace, and the name of the module that you want to create. For example:
```
# php -f Magento1xModuleStarter.php /var/www/magento_1.7.2 Bagsof Monet
```

* Once the basic directories and initial config files are created, you should be all set to customize away and begin building the module of your choice.

### Caveats:
* Don't re-run the script before deleting your module directories (i.e. ```<mage basedir>/app/code/local/<namespace>/<mod_name>```. The script is currently naive and does not overwrite/delete existing directories. It also exits when it encounters an error instead of continuing to the next step.

## References:
- http://coding.smashingmagazine.com/2012/03/01/basics-creating-magento-module/
- http://alanstorm.com/magento_controller_hello_world

