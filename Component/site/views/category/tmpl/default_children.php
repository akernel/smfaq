<?php
/**
 * SMFAQ
 *
 * @package		Component for Joomla 2.5.6+
 * @version		1.7.3
 * @copyright	(C)2009 - 2013 by SmokerMan (http://joomla-code.ru)
 * @license		GNU/GPL v.3 see http://www.gnu.org/licenses/gpl.html
 */

// защита от прямого доступа
defined('_JEXEC') or die('@-_-@');
$class = ' class="first"';
if (count($this->children[$this->category->id]) > 0) :
?>
<?php foreach($this->children[$this->category->id] as $id => $child) : ?>
	<?php
	//var_dump($child);
	if($this->params->get('show_empty_categories') || $child->numitems || count($child->getChildren())) :
	//$class = isset($this->children[$this->category->id][$id + 1]) == false ? null : 'class = "last"';
	if(!isset($this->children[$this->category->id][$id + 1]))
	{
		$class = ' class="last"';
	}
	
	?>
			<?php $class = ''; ?>
			<a href="<?php echo JRoute::_(SmFaqHelperRoute::getCategoryRoute($child->id));?>"><button class="btn btn-primary" type="button">
				<?php echo $this->escape($child->title); ?>
			<span class="badge">
			<?php if ($this->params->get('show_subcat_desc') && $child->description) :?>
				<div class="category-desc">
					<?php echo JHtml::_('content.prepare', $child->description); ?>
				</div>
            <?php endif; ?>
 			<?php if ($this->params->get('show_cat_num_links_cat')) :?>
						<?php echo JText::_('COM_SMFAQ_NUM_CAT_ITEMS') ; ?>
						<?php echo $child->getNumItems(true); ?></span></button></a><br/><br/>
			<?php endif; ?>
			<?php if(count($child->getChildren()) > 0 ) :
				$this->children[$child->id] = $child->getChildren();
				$this->category = $child;
				$this->maxLevel--;
				echo $this->loadTemplate('children');
				$this->category = $child->getParent();
				$this->maxLevel++;
			endif; ?>
	<?php endif; ?>
	<?php endforeach; ?>
<?php endif;