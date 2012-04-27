# Extended Testing Plugin for CakePHP #

A holder for extending CakePHP's testing suite. currently contains a new comparison test method called:

	assertIsSubset($subsetArray, $setArray);

This method will Pass if the $subsetArray key->values are contained in the $setArray. Other keys in the $setArray will be ignored.

This is useful for two reasons:

1) If fixture data is added, and your old tests are comparing arrays of this fixture data using assertIsSubset, then the tests will continue to Pass, and do not have to be re-written. If you were using assertEqual() for the tests then the data would have to be not only added to the fixture, but every assertEqual would have to be updated to pass.
2) Result data that contains values that change every time (like created and modified field data) does not have to be unset before comparing data using assertIsSubset.


## Installation ##

Just clone this repo into the APP/plugins folder:

	git clone git@github.com:bmilesp/extended_testing.git

## How to use it ##

Instead of extending your unit tests cases with CakeTestCase, use ExtendedTestCase:

	App::Import('Lib','ExtendedTesting.ExtendedTestCase');

	class MatchesControllerTestCase extends ExtendedTestCase {...}


## Requirements ##

* PHP version: PHP 5.2+
* CakePHP version: Cakephp 1.3 Stable
* [SimpleTest](http://www.simpletest.org/)

## Developer ##

Brandon Plasters [bmilesp](http://twitter.com/bmilesp) [Blog](http://blog.brandonplasters.com)

## License ##

Copyright 2011-2012, [Brandon Plasters](http://blog.brandonplasters.com)

Licensed under [The MIT License](http://www.opensource.org/licenses/mit-license.php)<br/>
Redistributions of files must retain the above copyright notice.

## Copyright ###

Copyright 2011-2012<br/>
[Brandon Plasters](http://blog.brandonplasters.com)

## Credits ##

assertIsSubset is Based on [woledzki's](https://github.com/sebastianbergmann/phpunit/pull/361) idea of array subsets.