<?php

namespace Drupal\cookies_addons_views\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Cookies Addons Views settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cookies_addons_views_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['cookies_addons_views.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['views'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Views to block'),
      '#default_value' => $this->config('cookies_addons_views.settings')
        ->get('views'),
      '#description' => $this
        ->t('New-line separated, format: view_id|view_display_id|cookies_service_machine_name')
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('cookies_addons_views.settings')
      ->set('views', $form_state->getValue('views'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
