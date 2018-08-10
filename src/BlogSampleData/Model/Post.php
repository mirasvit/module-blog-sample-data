<?php

namespace Mirasvit\BlogSampleData\Model;

use Magento\Framework\Setup\SampleData\Context as SampleDataContext;
use Magento\Framework\App\ResourceConnection;
use Mirasvit\Blog\Api\Repository\CategoryRepositoryInterface;
use Mirasvit\Blog\Api\Repository\PostRepositoryInterface;
use Mirasvit\Blog\Api\Repository\TagRepositoryInterface;
use Mirasvit\Blog\Model\PostFactory;
use Mirasvit\Blog\Model\CategoryFactory;
use Magento\Catalog\Model\ProductFactory;
use Mirasvit\Blog\Model\Config;
use Mirasvit\Core\Service\YamlService;
use RomaricDrigon\MetaYaml\MetaYaml;

class Post
{
    public function __construct(
        PostRepositoryInterface $postRepository,
        CategoryRepositoryInterface $categoryRepository,
        TagRepositoryInterface $tagRepository,
        SampleDataContext $sampleDataContext,
        ProductFactory $productFactory,
        Config $config,
        ResourceConnection $resource
    ) {
        $this->fixtureManager = $sampleDataContext->getFixtureManager();
        $this->postRepository = $postRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
        $this->productFactory = $productFactory;
        $this->config = $config;
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

            $posts = YamlService::parse($fileName);

            foreach ($posts as $post) {
                echo 'Saving a post' . PHP_EOL;

                $tagIds = [];
                if (isset($post['tags'])) {
                    $tagNames = explode(',', $post['tags']);
                    $tagNames = array_filter($tagNames);
                    foreach ($tagNames as $tagName) {
                        $tag = $this->tagRepository->create()
                            ->setName($tagName);
                        $tag = $this->tagRepository->ensure($tag);

                        $tagIds[] = $tag->getId();
                    }
                }

                $model = $this->postRepository->create();
                $model->setData($post)
                    ->setContent($this->getContent())
                    ->setShortContent($this->getShortContent())
                    ->setCategoryIds($this->getRandomCategories())
                    ->setTagIds($tagIds)
                    ->setProductIds($this->getRandomProducts())
                    ->setCreatedAt(time() - rand(0, 365 * 24 * 60 * 60));

                $this->postRepository->save($model);

                $img = $this->fixtureManager->getFixture(
                    'Mirasvit_BlogSampleData::fixtures/media/' . rand(1, 15) . '.jpg'
                );

                try {
                    @mkdir($this->config->getMediaPath(''), 777, true);

                    $newImg = $model->getId() . '.jpg';
                    copy($img, $this->config->getMediaPath('') . '/' . $newImg);

                    $model->setFeaturedImage($newImg);

                    $this->postRepository->save($model);
                } catch (\Exception $e) {
                    echo $e;
                }
            }
        }
    }

    /**
     * @return array
     */
    protected function getRandomCategories()
    {
        $result = [];
        $collection = $this->categoryRepository->getCollection();
        $collection->getSelect()->orderRand()
            ->limit(rand(1, 3));

        foreach ($collection as $item) {
            $result[] = $item->getId();
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function getRandomProducts()
    {
        $result = [];
        $collection = $this->productFactory->create()->getCollection()
            ->addAttributeToFilter('visibility', 4);
        $collection->getSelect()->orderRand()
            ->limit(rand(4, 8));

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
        for ($i = 0; $i < rand(2, 6); $i++) {
            $text .= '<p>' . $faker->realText(rand(100, 1000)) . '</p>';
        }

        return $text;
    }

    /**
     * @return string
     */
    protected function getShortContent()
    {
        $faker = \Faker\Factory::create();

        return '<p>' . $faker->realText(rand(200, 400)) . '</p>'
            . '<p>' . $faker->realText(rand(200, 400)) . '</p>';
    }
}