<?php

class CSVValidator {
    private $errors = [];
    private $columns = [];
    private $sources = [
        "google forms", "facebook leads", "email response", "manual registration"
    ];
    private $counter = 0;

    public function loadCSV($path) {
        $reader = fopen($path, 'r');
        $this->counter = 0;
        $this->errors = [];

        if($reader) {
            $count = 0;
            while(true) {
                ++$this->counter;
                $line = fgetcsv($reader,10000,",");
                if(!$line) {
                    break;
                }
                if($this->counter == 1) {
                    $this->columns = $line;
                    continue;
                }
                $this->checkLine($line);
            }
        }
        echo json_encode($this->errors);
        fclose($reader);
    }

    private function checkLine($line): bool {
        $userName = $line[0];
        $age = $line[1];
        $dni = $line[2];
        $source = $line[3];
        $tags = $line[4];
        $phone = $line[5];
        $externalId = $line[6];
        
        $lineErrors = [];

        if(!$this->checkStringEnum($source)) {
            $lineErrors["source"] = $source;
        }

        if(!$this->checkNumber($age, 1, 125)) {
            $lineErrors["age"] = $age;
        }

        if(is_numeric($dni) && strlen($dni) != 11) {
            $lineErrors["dni"] = $dni;// . "_" . strlen($dni);
        }

        if(!$this->checkNumber($externalId, 10000, 99999)) {
            $lineErrors["externalId"] = $externalId;
        }

        if(!$this->checkTags($tags)) {
            $lineErrors["tags"] = $tags;
        }

        if(!$this->checkPhoneNumber($phone)) {
            $lineErrors["phone"] = $phone;
        }

        if(count($lineErrors) > 0) {
            $this->errors[$this->counter] = $lineErrors;
        }

        return !empty($lineErrors);
    }

    private function writeLine(string $string) {

    }

    private function checkNumber(int $val, int $min, int $max) {
        return is_numeric($val) && $min <= $val && $max >= $val; 
    }

    private function checkStringEnum(string $stringText) {
        return in_array(strtolower($stringText), $this->sources);
    }

    private function checkUserName(string $name) {
        $nameParts = explode(" ", $name);
    }

    private function checkTags(string $tags): bool {
        $parts = explode("|", $tags);
        $valid = true;
        foreach($parts as $part) {
            $match = preg_match('/^(\w*)$/', $part, $output_array);;
            if(empty($output_array)) {
                $valid = false;
            }
        }
        return $valid;
    }

    public function checkPhoneNumber(string $phone) {
        $phone = str_replace("+54", "", $phone);
        $phone = str_replace("-", "", $phone);
        $phone = str_replace(" ", "", $phone);
        $phone = str_replace("+", "", $phone);
        return strlen($phone) == 11;
    }
}

$instance = new CSVValidator();

$instance->loadCSV(".\\data.csv");
