<table class="datatables">
  <thead>
    <tr>
      <th>Name</th>
      <th>Ip</th>
      <th>Mac</th>
      <th>Wake Up</th>
      <th>Checks</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($computers as $computer): ?>
    <tr>
      <td><a href="<?php echo url_for('computer/show?id='.$computer->getId()) ?>"><?php echo $computer->getName() ?></a></td>
      <td><?php echo $computer->getIp() ?></td>
      <td><?php echo $computer->getMac() ?></td>
      <td><?php echo button_to('Wake me up!', 'computer/wakeup?id='.$computer->getId())?></td>
      <td>
        <?php echo button_to('Check remote desktop', 'computer/remote?id='.$computer->getId())?>
      <!-- <?php echo button_to('Check ping', 'computer/ping?id='.$computer->getId())?> -->
    </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>