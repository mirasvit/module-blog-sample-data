<?php

namespace Mirasvit\BlogSampleData\Model;

use Magento\Framework\Setup\SampleData\Context as SampleDataContext;
use Magento\Framework\App\ResourceConnection;
use Mirasvit\Blog\Model\CategoryFactory;
use Mirasvit\Core\Service\YamlService;
use RomaricDrigon\MetaYaml\MetaYaml;

class Category
{
    /**
     * @param SampleDataContext  $sampleDataContext
     * @param CategoryFactory    $categoryFactory
     * @param ResourceConnection $resource
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        CategoryFactory $categoryFactory,
        ResourceConnection $resource
    ) {
        $this->fixtureManager = $sampleDataContext->getFixtureManager();
        $this->categoryFactory = $categoryFactory;
        $this->resource = $resource;
    }

    /**
     * @param array $fixtures
     * @return void
     */
    public function install(array $fixtures)
    {
        $tableName = $this->resource->getTableName('mst_blog_category_entity');
        $this->resource->getConnection()->query("DELETE FROM $tableName");
        $this->resource->getConnection()->query("ALTER TABLE $tableName AUTO_INCREMENT = 1");

        foreach ($fixtures as $fileName) {
            $fileName = $this->fixtureManager->getFixture($fileName);

            $categories = YamlService::parse($fileName);

            foreach ($categories as $category) {
                $parentId = $this->getIdByUrlKey($category['parent']);

                $model = $this->categoryFactory->create();
                $model->setData($category)
                    ->setParentId($parentId)
                    ->save();
            }
        }
    }

    /**
     * @param string $urlKey
     * @return int
     */
    protected function getIdByUrlKey($urlKey)
    {
        return (int)$this->categoryFactory->create()->getCollection()
            ->addAttributeToFilter('url_key', $urlKey)
            ->getFirstItem()
            ->getId();
    }
}