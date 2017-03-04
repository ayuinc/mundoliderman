<div class="cmcp">
  <?php echo $this->view('mcp/capacitacion/_menu'); ?>
  <div class="cbody p-21">
    <a href="<?= $add_url ?>" class="submit">Nueva Pregunta</a> 
    <br><br>
    <?php echo $table_preguntas['table_html']; ?>
    <?php echo $table_preguntas['pagination_html']; ?>
  </div>
</div>