<table id="coolcalc-model-list" class="table table-striped table-hover table-condensed table-responsive">
<thead>
<tr>
<th><?php echo __('Model','coolcalc');?></th>
<th class="text-right"><?php echo __('Cooling capacity','coolcalc'); ?>
</th>
<th width="25%" class="text-right"><?php echo __('Price (excl. VAT)','coolcalc'); ?></th>
<th>&nbsp;</th>
</tr>
</thead>
<tbody>
  <?php foreach($machines as $machine) : ?>
  <tr data-cid="<?php echo $machine->ID; ?>">
  <td><?php echo $machine->post_title; ?></td>
  <td class="text-right"><?php echo $machine->meta['capacity'][0]; ?> kW</td>
  <td class="text-right">&euro;&nbsp;<span class="money"><?php echo $machine->meta['price'][0]; ?></span></td>
  <td><button type="button" class="btn btn-primary btn-xs coolcalc-calculation btn-arrow"><?php echo __('Quote','coolcalc');?> <span class="glyphicon glyphicon-menu-right"></span></button></td>
  </tr>
<?php endforeach; ?>
</tbody>
</table>