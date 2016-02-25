<?php
namespace Mirasvit\BlogSampleData\Setup;

use Magento\Framework\Setup;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;

class Installer implements Setup\SampleData\InstallerInterface
{
    /**
     * @var \Mirasvit\BlogSampleData\Model\Category
     */
    protected $category;

    /**
     * @var \Mirasvit\BlogSampleData\Model\Post
     */
    protected $post;

    /**
     * @param \Mirasvit\BlogSampleData\Model\Category $category
     * @param \Mirasvit\BlogSampleData\Model\Post     $post
     */
    public function __construct(
        \Mirasvit\BlogSampleData\Model\Category $category,
        \Mirasvit\BlogSampleData\Model\Post $post
    ) {
        $this->category = $category;
        $this->post = $post;
    }

    /**
     * {@inheritdoc}
     */
    public function install()
    {
        try {
            $this->category->install(['Mirasvit_BlogSampleData::fixtures/category.yaml']);
            $this->post->install(['Mirasvit_BlogSampleData::fixtures/post.yaml']);
        } catch (\Exception $e) {
            echo $e;
        }
    }
}
