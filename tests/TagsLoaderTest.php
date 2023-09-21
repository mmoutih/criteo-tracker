<?php
namespace Tests;

class TagsLoaderTest extends CriteoTagTestCase
{
   
    public function testLoadingScript()
    {
        $header = $this->loader->getCriteoLoaderFile();
        $this->assertStringContainsString($this->idCriteo,$header);
        $this->assertStringContainsString("text/javascript",$header);
        $this->assertStringContainsString("dynamic.criteo.com/js/ld/ld.js",$header);
    }


    public function testTracingScript()
    {
        $this->loader->viewHomePage();
        $script = $this->loader->getCriteoTracingScript(false);
        $this->basicAsserts($script);
       
    }

    public function testDeferTracingScript()
    {
        $this->loader->viewHomePage();
        $script = $this->loader->getCriteoTracingScript(5000);
        $this->basicAsserts($script);
        $this->assertStringContainsString("window.setTimeout",$script);
        $this->assertStringContainsString("5000",$script);
    }


    private function basicAsserts($script)
    {
        $this->assertStringContainsString($this->idCriteo,$script);
        $this->assertStringContainsString("text/javascript",$script);
        $this->assertStringContainsString("window.criteo_q = window.criteo_q || [];",$script);
        $this->assertStringContainsString("events.forEach",$script);
        $this->assertStringContainsString("window.criteo_q.push(element)",$script);
    }

}
