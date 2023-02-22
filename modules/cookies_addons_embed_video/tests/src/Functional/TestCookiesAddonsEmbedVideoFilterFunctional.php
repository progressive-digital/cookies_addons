<?php

namespace Drupal\Tests\cookies_addons_embed_video\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\cookies_filter\Entity\CookiesServiceFilterEntity;

/**
 * This class provides methods for testing cookies_addons_embed_video.
 *
 * @group cookies_addons_embed_video
 */
class TestCookiesAddonsEmbedVideoFilterFunctional extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'node',
    'cookies',
    'cookies_video',
    'cookies_addons',
    'cookies_addons_embed_video',
    'filter_test',
    'block',
  ];

  /**
   * A user with authenticated permissions.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $user;

  /**
   * A user with admin permissions.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $adminUser;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->user = $this->drupalCreateUser([]);
    $this->adminUser = $this->drupalCreateUser();
    $this->adminUser->addRole($this->createAdminRole('admin', 'admin'));
    $this->adminUser->save();
    $this->drupalLogin($this->adminUser);
    // Create article content type:
    $this->createContentType(['type' => 'article']);
  }

  /**
   * Tests if the CookiesAddonsEmbedVideoFilter exists.
   */
  public function testCookiesAddonsEmbedVideoFilterUiExists() {
    $session = $this->assertSession();
    $page = $this->getSession()->getPage();
    $this->drupalGet('/admin/config/content/formats/manage/filter_test');
    $session->statusCodeEquals(200);
    $page->hasField('edit-filters-cookies-addons-embed-viedeo-filter-status');
  }

  /**
   * Test the UI for enabling the filter.
   */
  public function testEnablingYoutubeFilter() {
    $session = $this->assertSession();
    $page = $this->getSession()->getPage();
    $this->drupalGet('/admin/config/content/formats/manage/filter_test');
    $session->statusCodeEquals(200);
    $page->checkField('edit-filters-cookies-addons-embed-viedeo-filter-status');
    $page->pressButton('edit-actions-submit');
    $session->statusCodeEquals(200);
    $session->pageTextContains('The text format Test format has been updated.');
  }

}
