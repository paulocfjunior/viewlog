<?php 


    function oci_query ($ssql, $oci = "") {
        if ($oci === "") require "oci.php";
        
        $stid = oci_parse($oci, $ssql);
        oci_execute($stid);
        return $stid;
    }


    echo "<table border='1'>\n";
    while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
        echo "<tr>\n";
        foreach ($row as $item) {
            echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
        }
        echo "</tr>\n";
    }
    echo "</table>\n";
