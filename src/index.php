<?php

class CSVValidator {
    private $errors = [];
    private $sources = [
        "google forms", "facebook leads", "email response", "manual registration"
    ];
    private $counter = 0;

    public function loadCSV($path, $destiny) {
        $reader = fopen($path, 'r');
        $writter = fopen($destiny, 'w');

        if($reader === false || $writter === false) {
            throw new Exception("Open/Writte files errors");
        }

        $this->counter = 0;
        $this->errors = [];

        while (($line = fgetcsv($reader,10000,",")) !== false) {
            ++$this->counter;
            if($this->counter == 1) {
                fputcsv($writter, $line, ",");
                continue;
            }
            if($this->checkLine($line)) {
                fputcsv($writter, $line, ",");
            }
        }
        
        fclose($reader);
        fclose($writter);
        file_put_contents(".\\errors.json", json_encode($this->errors));
    }

    private function checkLine($line): bool {
        $name = $line[0];
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

        if(!$this->checkUserName($name)) {
            $lineErrors["name"] = $name;
        }

        if(count($lineErrors) > 0) {
            $this->errors[$this->counter] = $lineErrors;
        }

        return !empty($lineErrors);
    }

    private function checkNumber(int $val, int $min, int $max) {
        return is_numeric($val) && $min <= $val && $max >= $val; 
    }

    private function checkStringEnum(string $stringText) {
        return in_array(strtolower($stringText), $this->sources);
    }

    private function checkUserName(string $name) {
        $regex = "/^([A-Za-z]+) ?([A-Za-z]\.)? ([A-Za-z]+)$/";
        return preg_match($regex, $name);
    }

    private function checkTags(string $tags): bool {
        $regex = "/^[\w]+(?:\|[\w]+)*$/";
        return preg_match($regex, $tags);
    }

    private function checkPhoneNumber(string $phone) {
        $phone = str_replace(["+54", "-", " ", "+"], "", $phone);
        return is_numeric($phone) && strlen($phone) == 11;
    }
}

$instance = new CSVValidator();

$instance->loadCSV(".\\data.csv", ".\\final.csv");
