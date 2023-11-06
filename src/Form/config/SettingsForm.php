<?php

namespace Drupal\onovas_block_selection_field\Form\config;

/**
 * @file
 * SettingsForm.php
 */

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Markup;
use Drupal\Core\Config\Config;
use Drupal\Core\Extension\ExtensionPathResolver;
use Drupal\Core\Extension\ThemeHandlerInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\onovas_block_selection_field\lib\general\MarkdownParser;

/**
 * Formulario de configuración del módulo.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * Constructor para añadir dependencias.
   *
   * @param \Drupal\Core\Extension\ExtensionPathResolver $logger
   *   Servicio PathResolver.
   */
  public function __construct(protected ExtensionPathResolver $pathResolver,
                              protected ThemeHandlerInterface $themeHandler) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('extension.path.resolver'),
      $container->get('theme_handler')
    );
  }

  /**
   * Implements getFormId().
   */
  public function getFormId() {
    return 'onovas_block_selection_field.settings';
  }

  /**
   * Implements getEditableConfigNames().
   */
  protected function getEditableConfigNames() {
    return ['onovas_block_selection_field.settings'];
  }

  /**
   * Implements buildForm().
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    /* Obtengo la configuración actual */
    $config = $this->config('onovas_block_selection_field.settings');

    /* SETTINGS FORM */
    $form['settings'] = [
      '#type' => 'vertical_tabs',
    ];

    $form['general_settings'] = $this->getGeneralSettings($config);
    $form['general_settings']['#open'] = TRUE;

    /* *************************************************************************
     * CONTENIDO DE CHANGELOG.md, LICENSE.md y README.md
     * ************************************************************************/

    /* Datos auxiliares */
    $module_path = $this->pathResolver
      ->getPath('module', "onovas_block_selection_field");

    /* Compruebo si existe y leo el contenido del archivo CHANGELOG.md */
    $contenido = $this->getChangeLogBuild($config, $module_path);
    if ($contenido) {
      $form['info'] = $contenido;
    }

    /* Compruebo si existe y leo el contenido del archivo LICENSE.md */
    $contenido = $this->getLicenseBuild($config, $module_path);
    if ($contenido) {
      $form['license'] = $contenido;
    }

    /* Compruebo si existe y leo el contenido del archivo README.md */
    $contenido = $this->getReadmeBuild($config, $module_path);
    if ($contenido) {
      $form['help'] = $contenido;
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $config = $this->config('onovas_block_selection_field.settings');

    /* Guardar valores del formulario */
    $list = [
      'theme',
    ];

    foreach ($list as $item) {
      $config->set($item, $form_state->getValue($item));
    }
    $config->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * Genera el formulario para la configuración general del módulo.
   *
   * @param \Drupal\Core\Config\Config $config
   *   Configuración del módulo.
   *
   * @return array
   *   Array con el contenido a renderizar, si procede.
   */
  private function getGeneralSettings(Config $config): array {
    $theme_list = $this->themeHandler->listInfo();
    $options = [];
    foreach ($theme_list as $key => $value) {
      $options[$key] = $value->info['name'];
    }

    $form['general_settings'] = [
      '#type'        => 'details',
      '#title'       => $this->t('General'),
      '#group'       => 'settings',
      '#description' => $this->t('<p><h2>General Settings</h2></p>'),

      'theme' => [
        '#type'          => 'select',
        '#title'         => $this->t('Theme'),
        '#description'   => $this->t('Select the theme you want to use to render the blocks'),
        '#default_value' => $config->get('theme'),
        '#required'      => TRUE,
        '#options'       => $options,
        '#empty_option'  => $this->t('-- None --'),
        '#empty_value'   => '_none',
        '#sort_options'  => TRUE,
        '#sort_start'    => 1,
        '#multiple'      => FALSE,
      ]
    ];

    return $form['general_settings'];
  }

  /**
   * Obtiene el contenido del archivo CHANGELOG.md.
   *
   * @param \Drupal\Core\Config\Config $config
   *   Configuración del módulo.
   * @param string $module_path
   *   Path del módulo.
   *
   * @return array
   *   Array con el contenido a renderizar, si procede.
   */
  private function getChangeLogBuild(Config $config,
                                     string $module_path): array {
    $template = file_get_contents($module_path . "/templates/custom/info.html.twig");

    $ruta = $module_path . "/CHANGELOG.md";
    $contenido = $this->getMdContent($ruta);

    if ($contenido) {
      $form['info'] = [
        '#type'        => 'details',
        '#title'       => $this->t('Info'),
        '#group'       => 'settings',
        '#description' => '',

        'info' => [
          '#type'     => 'inline_template',
          '#template' => $template,
          '#context'  => [
            'changelog' => Markup::create($contenido),
          ],
        ],
      ];

      return $form['info'];
    }

    return [];
  }

  /**
   * Obtiene el contenido del archivo LICENSE.md.
   *
   * @param \Drupal\Core\Config\Config $config
   *   Configuración del módulo.
   * @param string $module_path
   *   Path del módulo.
   *
   * @return array
   *   Array con el contenido a renderizar, si procede.
   */
  private function getLicenseBuild(Config $config,
                                   string $module_path): array {
    $template = file_get_contents($module_path . "/templates/custom/license.html.twig");

    $ruta = $module_path . "/LICENSE.md";
    $contenido = $this->getMdContent($ruta);

    if ($contenido) {
      $form['license'] = [
        '#type'        => 'details',
        '#title'       => $this->t('License'),
        '#group'       => 'settings',
        '#description' => '',

        'license' => [
          '#type'     => 'inline_template',
          '#template' => $template,
          '#context'  => [
            'license' => Markup::create($contenido),
          ],
        ],
      ];

      return $form['license'];
    }

    return [];
  }

  /**
   * Obtiene el contenido del archivo LICENSE.md.
   *
   * @param \Drupal\Core\Config\Config $config
   *   Configuración del módulo.
   * @param string $module_path
   *   Path del módulo.
   *
   * @return array
   *   Array con el contenido a renderizar, si procede.
   */
  private function getReadmeBuild(Config $config,
                                  string $module_path): array {
    $template = file_get_contents($module_path . "/templates/custom/help.html.twig");

    $ruta = $module_path . "/README.md";
    $contenido = $this->getMdContent($ruta);

    if ($contenido) {
      $form['help'] = [
        '#type'        => 'details',
        '#title'       => $this->t('Help'),
        '#group'       => 'settings',
        '#description' => '',

        'help' => [
          '#type'     => 'inline_template',
          '#template' => $template,
          '#context'  => [
            'readme' => Markup::create($contenido),
          ],
        ],
      ];

      return $form['help'];
    }

    return [];
  }

  /**
   * Obtiene el contenido de un archivo .md.
   *
   * @param string $path
   *   Ruta completa del archivo.
   *
   * @return string
   *   Contenido del archivo.
   */
  private function getMdContent(string $path): string {
    $parser = new MarkdownParser();

    $contenido = '';
    if (file_exists($path)) {
      $contenido = file_get_contents($path);
      $contenido = $parser->text($contenido);
    }

    return $contenido;
  }

}
