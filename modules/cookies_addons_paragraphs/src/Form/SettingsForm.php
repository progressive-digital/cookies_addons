<?php

namespace Drupal\cookies_addons_paragraphs\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Cookies Addons paragraphs settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cookies_addons_paragraphs_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['cookies_addons_paragraphs.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['paragraphs'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Drupal Paragraphs to block'),
      '#default_value' => $this->config('cookies_addons_paragraphs.settings')
        ->get('paragraphs'),
      '#description' => $this
        ->t('One paragraph per line, format: paragraph_id|cookies_service_machine_name')
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('cookies_addons_paragraphs.settings')
      ->set('paragraphs', $form_state->getValue('paragraphs'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
