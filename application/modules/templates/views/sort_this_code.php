<?php
//if the webpage is not on the root folder you have to change $folder = '/', to $folder = 'path/to/website/'
$folder = '/';
$first_bit = $this->uri->segment(1);
$third_bit = $this->uri->segment(3);

//If there is more distance than one folder from the root directory 
//you have to add as many "../" as folders from the root of the server are
if ($third_bit!="") {
    //we have three segments on the URL, so...
    $start_of_target_url = "../../../";
} else {
    //we probably have two semgents on the URL, so...
    $start_of_target_url = "../../";
}
?>

<script type="text/javascript">

    $(document).ready(function(){

        $( "#sortlist" ).sortable({
            placeholder: "ui-state-highlight",
            stop: function(event, ui) {saveChanges();}

        });
        $( "#sortlist" ).disableSelection();

        });

        function saveChanges()
        {
            var $num = $('#sortlist > li').size();
            $dataString = "number=" +$num;
            for($x=1;$x<=$num;$x++)
            {
                var $catid = $('#sortlist li:nth-child('+$x+') ').attr('id');
                $dataString = $dataString + "&order"+$x+"="+$catid;
            }           $.ajax({
             type: "POST",
              url: "<?php echo $start_of_target_url.$folder.$first_bit; ?>/sort",
              data: $dataString,
              success: function(result){console.log(result); }
            });
            return false;
        }

</script>