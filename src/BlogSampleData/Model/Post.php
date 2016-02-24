<?php

namespace Mirasvit\BlogSampleData\Model;

use Magento\Framework\Setup\SampleData\Context as SampleDataContext;
use Magento\Framework\App\ResourceConnection;
use Symfony\Component\Yaml\Parser as YamlParser;
use Mirasvit\Blog\Model\PostFactory;
use Mirasvit\Blog\Model\CategoryFactory;

class Post
{
    /**
     * @param SampleDataContext  $sampleDataContext
     * @param PostFactory        $postFactory
     * @param CategoryFactory    $categoryFactory
     * @param YamlParser         $yamlParser
     * @param ResourceConnection $resource
     */
    public function __construct(
        SampleDataContext $sampleDataContext,
        PostFactory $postFactory,
        CategoryFactory $categoryFactory,
        YamlParser $yamlParser,
        ResourceConnection $resource
    ) {
        $this->fixtureManager = $sampleDataContext->getFixtureManager();
        $this->postFactory = $postFactory;
        $this->categoryFactory = $categoryFactory;
        $this->yamlParser = $yamlParser;
        $this->resource = $resource;
    }

    /**
     * @param array $fixtures
     * @return void
     */
    public function install(array $fixtures)
    {
        $tableName = $this->resource->getTableName('mst_blog_post_entity');
        $this->resource->getConnection()->query("DELETE FROM $tableName");
        $this->resource->getConnection()->query("ALTER TABLE $tableName AUTO_INCREMENT = 1");

        foreach ($fixtures as $fileName) {
            $fileName = $this->fixtureManager->getFixture($fileName);

            $posts = $this->yamlParser->parse(file_get_contents($fileName));

            foreach ($posts as $post) {
                $model = $this->postFactory->create();
                $model->setData($post)
                    ->setContent($this->getContent())
                    ->setShortContent($this->getShortContent())
                    ->setCategoryIds($this->getRandomCategories())
                    ->save();
            }
        }
    }

    /**
     * @return array
     */
    protected function getRandomCategories()
    {
        $result = [];
        $collection = $this->categoryFactory->create()->getCollection();
        $collection->getSelect()->orderRand()
            ->limit(rand(1, 3));

        foreach ($collection as $item) {
            $result[] = $item->getId();
        }

        return $result;
    }

    /**
     * @return string
     */
    protected function getContent()
    {
        $faker = \Faker\Factory::create();

        $text = '';
        for ($i = 0; $i < rand(3, 20); $i++) {
            $text .= '<p>' . $faker->realText(rand(500, 2000)) . '</p>';
        }

        return $text;
    }

    /**
     * @return string
     */
    protected function getShortContent()
    {
        $faker = \Faker\Factory::create();

        return $faker->realText(rand(100, 500));
    }
}