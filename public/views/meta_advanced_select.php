<tr class="<?php if ($flag_required) echo 'required';?>">
  <td class="first" valign="top"><?php echo $label; ?></td>
  <td>
    <input class="advanced_select_filter" data-filter-name="<?php echo $name; ?>" type="text" placeholder="<?php _e('Search filter','mantelzorg'); ?> " /><br />
    <?php _e('Show only selected','mantelzorg'); ?> <input type="checkbox" checked="CHECKED" class="advanced_select_selected" data-filter-name="<?php echo $name; ?>" /><br />
    <?php
    foreach($unique_parents as $parent_slug => $parent_label) {
      ?><h4><?php echo $parent_label;?></h4>
      <ul class="advanced_select" data-filter-name="<?php echo $name; ?>">
        <?php foreach ($value as $option) {
          if ($option['parent_slug'] == $parent_slug) {
          ?>
          <li class="<?php if ($option['selected']=='1') echo 'checked'; ?>"><input <?php if ($option['selected']=='1') echo 'checked="CHECKED"'; ?> class="advanced_select_checkbox" type="checkbox" value="<?php echo $option['value'];?>" name="<?php echo $name; ?><?php if ($flag_multiple) echo "[]" ?>" /> <?php echo $option['label'];?></li>
          <?php
          }
        } ?>
      </ul>
      
      <?php 
    }
    ?>
    <?php if ($howto): ?><br />
    <span class="description"><?php echo $howto; ?></span>
    <?php endif; ?><br />
  </td>
</tr>