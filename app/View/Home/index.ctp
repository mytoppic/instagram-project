<?php

echo $this->Form->create('Rs', array('action' => 'login'));
echo $this->Form->input('name', array('type' => 'text','id' => 'email'));
echo $this->Form->input('agent', array('type' => 'hidden','id' => 'agent','value' => env('HTTP_USER_AGENT')));
echo $this->Form->end(array('class' => 'try'));
Debugger::dump(CakeSession::read('API.next_max_id'));
?>
<div class="res"><ol></ol></div>
<script>
    $(function () {
        $('.submit').on('click', function (event) {
            event.preventDefault();
            email = $('#email').val();
            $.ajax({
                type: "POST",
                url: '<?php echo Router::url(
                    array('controller' => 'rs', 
                    'action' => 'get')); ?>',
                data: {
                    email: email
                },
                success: function (respone) {
                    if (respone == 0)
                        return;
                    resJson = JSON.parse(respone);
                    for (i = 0; i < resJson.length; i++) {
                        $('<li />').appendTo('.res ol').html('<img src="' + resJson[i] + '" />');
                    }
                }
            });
        });
    });
</script>