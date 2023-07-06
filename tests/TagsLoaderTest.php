<?php
namespace Tests;

class TagsLoaderTest extends CriteoTagTestCase
{
   
    public function testLoadingTags()
    {
        $header = $this->loader->getCriteoLoaderFile();
        $this->assertStringContainsString($this->idCriteo,$header);
        $this->assertStringContainsString("text/javascript",$header);
        $this->assertStringContainsString("dynamic.criteo.com/js/ld/ld.js",$header);
    }

}
