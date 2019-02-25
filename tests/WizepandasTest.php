<?php
use PHPUnit\Framework\TestCase;
require_once('./src/Wizepandas.class.php');

// mocks
function loadWizePandasScript() {
  return null;
}
function saveWizePandasScript($script) {
}

final class WizepandasTest extends TestCase
{
    public function testCanSetApiKey()
    {
        $config = array(
          'apiKey' => '469ddcde-e87d-4f61-8bce-a759c46dcad9',
          'loadScript' => 'loadWizePandasScript',
          'saveScript' => 'saveWizePandasScript'
        );
        $instance = new WizePandas($config);
        $this->assertEquals(
            '469ddcde-e87d-4f61-8bce-a759c46dcad9',
            $instance->getApiKey()
        );
    }

    public function testCanGetJavascript()
    {
        $config = array(
          'apiKey' => '469ddcde-e87d-4f61-8bce-a759c46dcad9',
          'loadScript' => 'loadWizePandasScript',
          'saveScript' => 'saveWizePandasScript'
        );
        $instance = new WizePandas($config);
        $instance->getLatestJavascript();
        $this->assertContains(
            '<script>',
            $instance->getJavascript()
        );
    }

    public function testCanGetHeadCode()
    {
        $config = array(
          'apiKey' => '469ddcde-e87d-4f61-8bce-a759c46dcad9',
          'loadScript' => 'loadWizePandasScript',
          'saveScript' => 'saveWizePandasScript'
        );
        $instance = new WizePandas($config);
        $instance->getLatestJavascript();
        $this->assertContains(
            '<script>',
            $instance->getHeadCode()
        );
    }

    public function testCanGetBodyCode()
    {
      $config = array(
        'apiKey' => '469ddcde-e87d-4f61-8bce-a759c46dcad9',
        'loadScript' => 'loadWizePandasScript',
        'saveScript' => 'saveWizePandasScript'
      );
      $instance = new WizePandas($config);
      $output = $instance->getBodyCode();
      $this->assertEquals(
          "<iframe id='aq-ch' src='//static-42andpark-com.s3.amazonaws.com/html/danaton3.html' width='1' height='1' style='width:1px;height:1px;position:absolute;left:-1000;' frameborder=0></iframe>",
          $instance->getBodyCode()
      );
    }
}
