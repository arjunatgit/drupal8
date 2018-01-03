<?php
namespace Drupal\resume\Plugin\Block;
use Drupal\Core\Block\BlockBase;
/**
 * Provides a 'ResumeBlock' block.
 *
 * @Block(
 *  id = "resume_block",
 *  admin_label = @Translation("Resume Form block"),
 * )
 */
class ResumeBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    ////$build = [];
    //$build['mydata_block']['#markup'] = 'Implement MydataBlock.';
    $form = \Drupal::formBuilder()->getForm('Drupal\resume\Form\ResumeForm');
    return $form;
  }
}
