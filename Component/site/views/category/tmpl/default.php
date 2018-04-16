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

$canCreate	= $this->user->authorise('core.create', 'com_smfaq.category.'.$this->category->id);
$canEdit	= $this->user->authorise('core.edit', 'com_smfaq.category.'.$this->category->id);

switch ($this->params->get('open_question', 0)) {
	default:
	case 0:
		$style = 'display:none;height:1px;';
	break;
	case 1:
		$style = 'display:block;';
	break;
	case 2:
		$style = 'display:block;';
		$unclick = true;
	break;		
	case 3:
		$as_link = true;
	break;	
}

?>

<?php if ($this->params->get('show_page_title')) : ?>
	<div class="page-header">
<h1>
	<?php echo $this->escape($this->params->get('page_title')); ?>
</h1>
</div>
<?php endif; ?>
<?php if ( $this->params->get( 'show_desc', 1 ) ) : ?>
<div class="contentdescription<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<?php echo $this->category->description; ?>
</div>
<?php endif; ?>
<?php // Вывод подкатегорий ?>
<?php if (!empty($this->children[$this->category->id]) && $this->maxLevel != 0) : ?>
<div class="cat-children">
	<h3><?php echo JText::_('COM_SMFAQ_SUBCATEGORIES') ; ?></h3>
	<?php echo $this->loadTemplate('children'); ?>
</div>
<?php endif; ?>
<div id="smfaq" class="questions">
<?php // Вывод формы добавления вопроса
if ($canCreate && !$canEdit) :
	if ($this->params->get('show_form', 0) && isset($this->form)) : ?>
		<div><?php echo $this->form; ?></div>
	<?php else : ?>
		<div class="padding-bottom20"><span onclick="SmFaq.showform(true,this)" class="btn btn-success"><?php echo JText::_('COM_SMFAQ_NEW_QUESTION'); ?></span></div>
<?php endif; ?>
<?php elseif ($canEdit) : ?>
	<a href="<?php echo JRoute::_('index.php?option=com_smfaq&amp;task=edit.add&amp;catid='.$this->category->id); ?>" class="btn btn-success">
	<?php echo JText::_('COM_SMFAQ_NEW_QUESTION'); ?></a>
	<span class="btn btn-success" style="margin-left:10px;" onclick="SmFaq.unpublished(<?php echo JFactory::getApplication()->input->get('Itemid', null, 'int'); ?>)">
	<?php echo JText::_('COM_SMFAQ_SHOW_NO_ANSWER_QUESTIONS'); ?></span>
	<div id="smfaq-unpub"></div>
<?php endif; ?>
<?php if ($this->params->get('show_print', 0)) : ?>
	<?php $print_link = JRoute::_(SmfaqHelperRoute::getCategoryRoute($this->category->id).'&tmpl=component&print=1'); ?>
	<div class="smfaq-print"><?php echo JHtml::_('link', $print_link, JText::_('COM_SMFAQ_PRINT_VIEW'), array('rel' => 'nofollow', 'target' => '_blank'));?></div>
	<div style="clear: both"></div>
<?php endif ?>

<?php // Вывод вопросов ?>
<?php foreach ($this->items as $item) : ?>
			<?php if (isset($as_link)) : 
				$link = JRoute::_(SmfaqHelperRoute::getQuestionRoute($this->category->id, $item->id));
				?>
				<div class="question-link">
				<a href="<?php echo $link; ?>"><?php echo $this->escape($item->question); ?></a>
				</div>
			<?php continue; ?>
			<?php endif; ?>
			<?php if (!isset($unclick)) :
				$onclick = 'onclick="SmFaq.answer(\''.$item->id.'\');"';
				else :
				$onclick = '';
				endif; ?>
    <div class="panel-group" id="faqAccordion">
        <div class="panel panel-default ">
            <div class="panel-heading accordion-toggle question-toggle collapsed" data-toggle="collapse" data-parent="#faqAccordion" data-target="#question0">
                 <h4 class="panel-title">
							<div id="q<?php echo $item->id; ?>" class="question" <?php echo $onclick; ?>>
							<a name="<?php echo 'p'.$item->id; ?>" class="img"></a>
							<?php echo $this->escape($item->question); ?>
				         </div>
			        </h4>
			   </div>
            <div id="a<?php echo $item->id; ?>" class="answer" style="<?php echo $style; ?>"><div id="ac<?php echo $item->id; ?>" class="answer_content" style="top: 0px;">
            <?php //Created date and author question
            	$author = $this->params->get('show_created_date') || $this->params->get('show_created_by');
            	$ans = $this->params->get('show_answer_created_by') || $this->params->get('show_answer_created_date');
            ?>
			<?php if ($author || $ans) : ?>
				<div>
				<?php if ($author) : ?>
					<div>
		            	<?php if ($this->params->get( 'show_created_by')) : ?>
		                	<span class="author"><small><?php echo JText::sprintf('COM_SMFAQ_CREATED_BY', $this->escape($item->created_by)); ?></small></span>
		                <?php endif ?>
		            	<?php if ($this->params->get( 'show_created_date')) : ?>
		                	<span class="date"><small><?php echo JText::sprintf('COM_SMFAQ_CREATED', JHTML::_('date', $item->created, $this->params->get('date_format'))); ?></small></span>
		                <?php endif ?>
		            </div>
	            <?php endif ?>   
	            <?php if ($ans) : ?>
	            	<div>
		                <?php if ($this->params->get('show_answer_created_by')) : ?>
		                    <span class="ans-author"><small><?php echo  JText::sprintf('COM_SMFAQ_ANSWER_BY', $this->escape($item->answer_created_by)); ?></small></span>
		                <?php endif ?> 
		     			<?php if ($this->params->get('show_answer_created_date')) : ?>
		                    <span class="ans-date"><small><?php echo JText::sprintf('COM_SMFAQ_ANSWER_CREATED', JHTML::_('date', $item->answer_created, $this->params->get('date_format'))); ?></small></span>
		                <?php endif ?>
		                
	                </div>
	            <?php endif ?>
	            </div>
	        <?php endif ?>   
             <div class="panel-body">
                  <h5><span class="label label-primary">Ответ</span></h5>
               </div>
           <blockquote><?php 
            if ($this->params->get('content_plugins')) {
            	 $item->answer = JHtml::_('content.prepare', $item->answer);
            }
            ?>  
			<?php echo $item->answer; ?> </blockquote>
            <?php if ($canEdit) : ?> 
            	<div class="clr"></div>
				<a class="btn btn-success" href="<?php echo JRoute::_('index.php?option=com_smfaq&task=edit.edit&catid='.$item->catid.'&id='.$item->id); ?>"><?php echo JText::_('COM_SMFAQ_EDIT'); ?></a>
				<span><?php echo JText::sprintf('COM_SMFAQ_VOTE_STATE', $item->vote_yes, $item->vote_no, $item->comments); ?></span>
			<?php endif; ?>	
			<?php // Вывод опроса ?>
            <?php if ($this->params->get('show_poll', 1)) : ?><hr/>
	            <form action="#" name="vote<?php echo $item->id; ?>">
	                <?php echo JText::_('COM_SMFAQ_VOTE_QUESTION'); ?>
	                    <input type="radio" name="vote_question" onclick="SmFaq.Vote(this.form, value)" value="1" /> <?php echo JText::_('COM_SMFAQ_YES'); ?>
	                    <input type="radio" name="vote_question" onclick="SmFaq.Vote(this.form, value)" value="0" /> <?php echo JText::_('COM_SMFAQ_NO'); ?>
	                    <input type="hidden" name="id" value="<?php echo $item->id ?>" />
	                    <input type="hidden" name="token" value="<?php echo JSession::getFormToken(); ?>" />
	            </form>
            <?php endif; ?> 
            </div>
            </div>
        </div>
     </div>
<?php endforeach; ?>
<?php //Пагинация ?>
<?php if ($this->pagination->get('pages.total') > 1) : ?>
	<div class="pagination">
		<?php  if ($this->params->def('show_pagination_results', 1)) : ?>
			<p class="counter">
				<?php echo $this->pagination->getPagesCounter(); ?>
			</p>
		<?php endif; ?>
		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
<?php endif; ?>
</div>

