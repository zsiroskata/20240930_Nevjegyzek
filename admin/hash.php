<?php
    $jelszo ="juliska";
    print "jelszo: " . $jelszo;
    print "<br>md5() " .md5($jelszo);
    
    print "<br>sha1() " .sha1($jelszo);

    print "<br>sha3-512() ".hash("sha3-512", $jelszo);
?>