<?php

class SQLDeveloperClient {

    private $user_reports = "C:\\Users\\0102032114\\AppData\\Roaming\\SQL Developer\\UserReports.xml";
    private $user_name    = "Paulo";
    private $conn;

    function __construct($oci) {
        $this->conn = $oci;
    }

    function setUser($name) {
        $users = [
            "Paulo"     => "C:\\Users\\0102032114\\AppData\\Roaming\\SQL Developer\\UserReports.xml",
            "Jacob"     => "C:\\Users\\Public\\SQL\\Jacob.xml",
            "Guilherme" => ""
        ];

        if (array_key_exists($name, $users)) {
            if (file_exists($users[$name])) {
                $this->user_reports = $users[$name];
                $this->user_name    = $name;
            }
        }
    }

    function getSQL($name, $DEBUG = false) {
        $start = microtime(true);

        $user_reports = $this->user_reports;

        if (!file_exists($user_reports)) {
            print_r("File not exists");
            if ($DEBUG === true) {
                echo "Tempo de Execução: " . round((microtime(true) - $start), 3) . " ms <br><br>";
            }
            return false;
        }

        try {
            $xml = simplexml_load_file($user_reports, 'SimpleXMLElement', LIBXML_NOCDATA);

            foreach ($xml->display as $report) {
                if ($name === ((String) $report->name)) {
                    if ($DEBUG === true) {
                        echo "Tempo de Execução: " . round((microtime(true) - $start), 3) . " ms <br><br>";
                    }
                    return (String) $report->query->sql;
                }
            }
        } catch (Exception $e) {
            if ($DEBUG === true) {
                echo "Tempo de Execução: " . round((microtime(true) - $start), 3) . " ms <br><br>";
            }
            return false;
        }
        if ($DEBUG === true) {
            echo "Tempo de Execução: " . round((microtime(true) - $start), 3) . " ms <br><br>";
        }
        return false;
    }

    function runQuery($name, $return_array = true, $debug = false, $line_numbers = false) {
        $sql = $this->getSQL($name);
        if ($sql !== false) {
            return $this->runSQL($sql, $return_array, $debug, $line_numbers);
        } else {
            return false;
        }
    }

    function runSQL($sql, $return_array = true, $debug = false, $line_numbers = false, &$counter = 0) {
        $start = microtime(true);

        $stid = oci_query($sql);

        if (!$return_array) {
            ob_start();
        }
        $response = [];
        $counter  = 0;

        if (!$return_array) {
            echo "<table border='1' cellspacing='0'>";
        }

        /* @var $stid oci_result */
        $stid  = oci_query($sql);
        $first = true;
        while ($row   = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
            if ($first) {
                $head = $row;
                if ($line_numbers) {
                    echo "<th>#</th>";
                }
                foreach ($head as $field => $item) {
                    if (!$return_array) {
                        echo "<th>{$field}</th>";
                    }
                }
                $first = false;
            }

            $amostra = $row;
            if (!$return_array) {
                echo "<tr>";
            }
            if ($line_numbers) {
                echo "<td>" . ($counter + 1) . "</td>";
            }
            foreach ($row as $field => $item) {
                $response[$counter][$field] = $item;
                if (!$return_array) {
                    echo "<td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>";
                }
            }
            if (!$return_array) {
                echo "</tr>";
            }
            $counter++;
        }
        if (!$return_array) {
            echo "</table>";
        }

        if (!$return_array) {
            $html = ob_get_clean();
        }

        oci_free_statement($stid);

        if ($debug === true) {
            echo "Tempo de Execução: " . round((microtime(true) - $start), 3) . " ms <br><br>";
        }

        if ($return_array === true) {
            return $response;
        } else {
            return $html;
        }
    }

    function listSQL($show_sql = false, $debug = false) {
        $start = microtime(true);
        /* LISTAR TODAS AS CONSULTAS SALVAS NO SQL DEVELOPER */

        $user_reports = $this->user_reports;

        $xml = simplexml_load_file($user_reports, 'SimpleXMLElement', LIBXML_NOCDATA);
        echo "<pre>";
        foreach ($xml->display as $report) {
            $nome = $report->name;
            if ($show_sql === true) {
                $sql = (String) $report->query->sql;
                echo "<h1><a href='?u={$this->user_name}&q=$nome' target='_blank'>[TABLE]</a><a href='?json&u={$this->user_name}&q=$nome' target='_blank'>[JSON]</a> $nome</h1>";
                print_r($sql);
            } else {
                echo "<li><a href='?u={$this->user_name}&q=$nome' target='_blank'>[TABLE]</a><a href='?json&u={$this->user_name}&q=$nome' target='_blank'>[JSON]</a> $nome</li>";
            }
        }
        echo "</pre>";
        if ($debug === true) {
            echo "Tempo de Execução: " . round((microtime(true) - $start), 3) . " ms <br><br>";
        }
    }

}
