<?php

namespace Drupal\onovas_block_selection_field\Plugin\EntityReferenceSelection;

use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\Plugin\EntityReferenceSelection\DefaultSelection;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Filters the block listing for an entity reference field.
 *
 * @EntityReferenceSelection(
 *   id = "blocks",
 *   label = @Translation("Blocks: Filter by blocks in block_content region"),
 *   entity_types = {"block"},
 *   group = "blocks",
 *   weight = 1
 * )
 */
class BlockSelectionPlugin extends DefaultSelection {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container,
                                array $configuration,
                                $plugin_id,
                                $plugin_definition) {
    $instance = parent::create($container,
                               $configuration,
                               $plugin_id,
                               $plugin_definition);
    $instance->configFactory = $container->get('config.factory');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getReferenceableEntities($match = NULL,
                                           $match_operator = 'CONTAINS',
                                           $limit = 0) {
    $target_type = $this->configuration['target_type'];

    $query = $this->buildEntityQuery($match, $match_operator);
    if ($limit > 0) {
      $query->range(0, $limit);
    }

    $result = $query->execute();

    if (empty($result)) {
      return [];
    }

    /* Obtengo el tema seleccionado en la configuración de este módulo */
    $config = $this->configFactory
      ->getEditable('onovas_block_selection_field.settings');
    $theme_selected = $config->get('theme');

    $options = [];

    /** @var \Drupal\Core\Extension\ThemeHandlerInterface */
    $entities = $this->entityTypeManager->getStorage($target_type)->loadMultiple($result);
    foreach ($entities as $entity_id => $entity) {
      $block_theme = $entity->getTheme();
      $block_region = $entity->getRegion();

      /* Sólo se muestran los bloques para mi tema en la sección "block_content" */
      if ($block_theme == $theme_selected && $block_region == 'block_content') {
        $bundle = $entity->bundle();
        $options[$bundle][$entity_id] = Html::escape($this->entityRepository->getTranslationFromContext($entity)->label());
      }
    }

    return $options;
  }

}
