<?php

namespace Drupal\cookies_addons_blocks\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure cookies addons blocks settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'cookies_addons_blocks_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['cookies_addons_blocks.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['blocks'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Drupal Blocks to block'),
      '#default_value' => $this->config('cookies_addons_blocks.settings')
        ->get('blocks'),
      '#description' => $this
        ->t('One block per line, format: block_id|cookies_service_machine_name')
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('cookies_addons_blocks.settings')
      ->set('blocks', $form_state->getValue('blocks'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
