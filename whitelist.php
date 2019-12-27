<?php

/*

Whilelist util for Ethereum compatible addresses

27-12-2019

*/

$whitelist =  new Hash;

if (isset($argv[1])) {


        if ($argv[1] == "wallets") {

                $whitelist->vars();
        }

        if ($argv[1] == "set" AND isset($argv[2]) AND isset($argv[3])) {

                $whitelist->var = strtolower($argv[2]);
                $whitelist->value = $argv[3];
                $whitelist->update();
        }

        if ($argv[1] == "delete" AND isset($argv[2])) {

                $whitelist->var = strtolower($argv[2]);
                $whitelist->delete();
        }

        if ($argv[1] == "change" AND isset($argv[2]) AND isset($argv[3])) {

                $whitelist->var = strtolower($argv[2]);
                $whitelist->value = $argv[3];
                $whitelist->rename();
        }

        if ($argv[1] == "help") {

                $whitelist->help();
                exit;
        }
}
echo $whitelist->vars();
exit;


class Hash {

        public $data; //Assoc array containing all key/value pairs, accessible via $this->data
        public $var; //Input variable given via terminal commandline, accessible via $this->var
        public $value; //Input value ""
        public $file = "whitelist.json"; // json file which is used to store json-encoded assoc array

        //Create json file for storage if not exist and load decoded json data in assoc array $data
        function __construct() {

                $this->exist($this->file);
                $this->data = $this->load();
        }

        //Load variables from storage file into assoc array $data
        private function load () {

                return json_decode((file_get_contents($this->file)),true);
        }

        //Validate variable  and save assoc array to storage file
        private function save () {

                if ($this->validateVars() == true) {

                        file_put_contents($this->file, json_encode($this->data));
                } else {

                        echo "\nset: Invalid wallet address\n\n";
                }
        }

        //Update hash key value in assoc array and save
        public function update () {

                $this->data[$this->var] = $this->value;
                $this->save($this->data);
        }

        //Delete key / value pair from assoc array and save
        public function delete () {

                unset($this->data[$this->var]);
                $this->save($this->data);
        }

        //Rename key in  assoc array and save
        public function rename () {

                $value = $this->data[$this->var];
                $this->data[$this->value] = $value;
                unset($this->data[$this->var]);
                $this->save($this->data);
        }

        //Check if storage file exist, create if not exist
        private function exist () {

                if (!file_exists($this->file)) {

                        touch($this->file);
                }
        }

        //Validate input variables with array $this->validVars
        private function validateVars () {

                if(strlen($this->var) == 42) {

                        return true;
                }
        }

        //Display saved variables
        public function vars () {

                echo "\nWallets\n-------\n\n";

                if (!empty($this->load())) {

                        foreach($this->load() as $key => $value) {

                                echo $key . " - " . $value . "\n";
                        }
                } else { echo "No wallet addresses found.\n"; }
                echo "\n";

        }


        public function varExist ($var) {

                if(array_key_exists($var, $this->data)) { return true; }
        }

        public function help () {

                echo "\nCommands\n--------\n\n";
                echo "Usage: php whitelist.php command options\n\n";
                echo "set 0x..address description       ( set or update address & description ) \n";
                echo "delete 0x..address                ( delete address )\n";
                echo "change 0x..address                ( change address )\n";
                echo "list                              ( list all addresses )\n\n";
                if (!empty($this->validVars)) {

                        echo "options                   ( show variable options that are  set in class in \$validVars )\n";
                        echo "-------\n\n";
                        foreach($this->validVars as $key) {

                                echo $key . "\n";
                        }
                echo "\n\n";
                }

        }
}
?>
