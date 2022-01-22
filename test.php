
<form method="post">
    <input type="submit" name="test" id="test" value="RUN" /><br/>
</form>

<?php

function testfun()
{
   echo date("Y/m/d G:i:s") . " - Your clicked the button";
}

if(array_key_exists('test',$_POST)){
   testfun();
}

?>