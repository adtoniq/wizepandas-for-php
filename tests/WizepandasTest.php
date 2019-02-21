<?php
use PHPUnit\Framework\TestCase;
require_once('./src/Wizepandas.class.php');

final class WizepandasTest extends TestCase
{
    public function testCanSetApiKey()
    {
        $instance = new WizePandas();
        $instance->setApiKey('12345678-1234-5678');
        $this->assertEquals(
            '12345678-1234-5678',
            $instance->getApiKey()
        );
    }

    public function testApiKeyMustBeString()
    {
        $instance = new WizePandas();
        $instance->setApiKey(42);
        $this->assertEquals(
            null,
            $instance->getApiKey()
        );
    }

    public function testCanGetJavascript()
    {
        $instance = new WizePandas();
        $instance->getLatestJavascript();
        $this->assertContains(
            '<script>',
            $instance->getJavascript()
        );
    }

    public function testCanGetHeadCode()
    {
        $instance = new WizePandas();
        $instance->getLatestJavascript();
        $this->assertContains(
            '<script>',
            $instance->getHeadCode()
        );
    }

    public function testCanGetBodyCode()
    {
      $instance = new WizePandas();
      $output = $instance->getBodyCode();
      $this->assertEquals(
          "<iframe id='aq-ch' src='//static-42andpark-com.s3.amazonaws.com/html/danaton3.html' width='1' height='1' style='width:1px;height:1px;position:absolute;left:-1000;' frameborder=0></iframe>",
          $instance->getBodyCode()
      );
    }
}
