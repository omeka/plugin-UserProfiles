<?php
/**
 * @version $Id$
 * @copyright Center for History and New Media, 2007-2010
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @package Omeka
 */

require_once 'UserProfiles_Test_AppTestCase.php';
/**
 * Test suite for UserProfiles
 *
 * @package Omeka
 * @copyright Center for History and New Media, 2007-2010
 */
class UserProfiles_AllTests extends PHPUnit_Framework_TestSuite
{
    public static function suite()
    {
        $suite = new UserProfiles_AllTests('UserProfiles Tests');
        $testCollector = new PHPUnit_Runner_IncludePathTestCollector(
          array(dirname(__FILE__) . '/integration')
        );
        $suite->addTestFiles($testCollector->collectTests());
        return $suite;
    }
}
