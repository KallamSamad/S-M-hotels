<?php
function db_connect() {
    return new SQLite3("SNM.db");
}
?>
