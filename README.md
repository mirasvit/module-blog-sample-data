

## 1.0.3
*(2016-03-25)*

#### Improve
* Post to products relation

---

## 1.0.2
*(2016-03-17)*

#### Fixed
* Fixed an issue with images

---

## 1.0.0
*(2016-03-17)* 

* stable version

------
# Submodule mirasvit/module-sample-data
## Basic Usage

### Service\DateService
Позволяет двигать текущую дату, при этом все insert/update запросы будут идти с новой датой.

```php
/** @var \Mirasvit\SampleData\Service\DateService $this->dateService */

$this->dateService->toPast(6 * 30); #уходим на 6 месяцев назад
for ($i = 0; $i < 100; $i++) {
    this->sampleDataService->order();
    
    $this->dateService->toFeature(2); #уходим на два дня вперед
}
$this->dateService->reset(); #востанавливаем реальные значения
```

### Service\DatabaseService
Набор методов для работы с БД

```php
/** @var \Mirasvit\SampleData\Service\DatabaseService $this->databaseService */

$this->databaseService->truncateTable('customer_entity'); #очищает таблицу с учетом foregin keys и сбрасывает increment id
```

### Service\FixtureService
Набор методов для работы с fixtures

```php
/** @var \Mirasvit\SampleData\Service\FixtureService $this->fixtureService */

$content = $this->fixtureService->loadFixture('Mirasvit_Affiliate::fixture/text.html');
$data = $this->fixtureService->loadYamlFixture('Mirasvit_Affiliate::fixture/account.yaml');
```

------
# Submodule mirasvit/module-blog
# Blog MX | Magento 2 Blog Module by [Mirasvit](https://mirasvit.com/)

FREE, fully featured, powerful Blog solution for your online store! Magento 2 Blog MX allows you to open a blog and engage more and more customers to your shop activities using any type of content: images, video, articles etc.

## Key Features

* SEO friendly posts and URLs
* Multi-level categories
* Ability to preview post before publication or before save changes
* RSS Feed
* Tags and Tag Cloud
* Disqus comments
* Featured image for posts
* Ability to pin post at the top
* Sharing buttons

[more information](https://mirasvit.com/magento-2-extensions/blog.html)

## Documentation
[https://github.com/mirasvit/module-blog/wiki](https://github.com/mirasvit/module-blog/wiki)

## Demo
[http://blog.m2.mirasvit.com/blog/fashion/](http://blog.m2.mirasvit.com/blog/fashion/)

## Sample Data
[https://github.com/mirasvit/module-blog-sample-data](https://github.com/mirasvit/module-blog-sample-data)

## Support
[https://mirasvit.com/](https://mirasvit.com/)

