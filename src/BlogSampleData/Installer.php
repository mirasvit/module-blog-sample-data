<?php
namespace Mirasvit\BlogSampleData;

use Mirasvit\BlogSampleData\Setup\Installer as BlogInstaller;

class Installer
{
    /**
     * @var BlogInstaller
     */
    private $installer;

    public function __construct(BlogInstaller $installer)
    {
        $this->installer = $installer;
    }

    public function runCleanup()
    {

    }

    public function runInstall()
    {
        $this->installer->install();
    }
}
