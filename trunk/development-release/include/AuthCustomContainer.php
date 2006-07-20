<?php

include_once dirname(__FILE__) . "/../bundled-libs/Auth/Container.php";

class CustomAuthContainer extends Auth_Container
{
    /**
     * Constructor
     */
  function CustomAuthContainer($db)
    {
      // Init Here
      
    }

    function fetchData($username, $password)
    {
        // Check If valid etc
        if($isvalid) {
            // Perform Some Actions
            return true;
        }
        return false;
    }
}

?>