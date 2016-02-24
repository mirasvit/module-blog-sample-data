<?php
namespace Mirasvit\BlogSampleData\Setup;

use Magento\Framework\Setup;

class InstallData implements Setup\InstallDataInterface
{
    /**
     * @var Setup\SampleData\Executor
     */
    protected $executor;

    /**
     * @var Installer
     */
    protected $installer;

    /**
     * @param Setup\SampleData\Executor $executor
     * @param Installer                 $installer
     */
    public function __construct(Setup\SampleData\Executor $executor, Installer $installer)
    {
        $this->executor = $executor;
        $this->installer = $installer;
    }

    /**
     * @param Setup\ModuleDataSetupInterface $setup
     * @param Setup\ModuleContextInterface   $moduleContext
     */
    public function install(Setup\ModuleDataSetupInterface $setup, Setup\ModuleContextInterface $moduleContext)
    {
        $this->executor->exec($this->installer);
    }
}
