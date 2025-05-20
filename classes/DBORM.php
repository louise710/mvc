<?php

declare(strict_types = 1);

namespace Classes;


class DBORM implements iDBFuncs{

    private object $db;
    private string $sql = '';

    private int $whereInstanceCounter = 0;
    private array $valueBag = [];
    private string $table = '';

    public function __construct($driver, $user, $password, $options=null){
        try{
            $this->db = new \PDO($driver,$user,$password);
        } catch (\PDOException $error){
            echo $error->getMessage();
        }
    }

    public function select(?array $fieldList=null): object{
        $this->sql .= 'SELECT ';
        
        if($fieldList === null){
             $fieldList = '*';
             $this->sql .= $fieldList;
        } else {
             $contents = count($fieldList)-1;
             $count = 0; 
             foreach($fieldList as $field){
                $this->sql .= $field;  
                if($count < $contents)
                   $this->sql .= ', ';
                   
                $count++;   
             }  
        }

        $this->sql .= ' ';

        return $this;
    }

    public function table($table): object{
        $this->table = $table;
        return $this;   
    }

    public function from($table): object{
        $this->sql .= 'from '.$table;
        return $this;
    }

    public function get(): array{
        $recordset = $this->_runGetQuery(__METHOD__); 
        return $recordset;
    }

    public function getAll(): array{    
        $recordset = $this->_runGetQuery(__METHOD__);  
        return $recordset;
    }

    private function _runGetQuery($getMethod): array {
        $this->sql .= ';';
        $dbStatement = $this->db->prepare($this->sql);
    
        if ($this->valueBag) {
            $dbStatement->execute($this->valueBag);
            $this->valueBag = [];
        } else {
            $dbStatement->execute();
        }
    
        if (str_ends_with($getMethod, '::get')) {
            $recordset = $dbStatement->fetch(\PDO::FETCH_BOTH);
        } elseif (str_ends_with($getMethod, '::getAll')) {
            $recordset = $dbStatement->fetchAll(\PDO::FETCH_BOTH);
        } else {
            $recordset = []; 
        }
    
        $this->whereInstanceCounter = 0;
        $this->sql = '';
    
        return $recordset;
    }
    

    public function where(): object{
        if(func_num_args() <= 1){
            throw new Exception('Expecting 2 to 3 parameters. Less than 2 parameters encountered.');
        }

        if(func_num_args() == 2){
            $field = func_get_arg(0);
            $operator = '=';
            $value = func_get_arg(1);
        } elseif(func_num_args() == 3) {
            $field = func_get_arg(0);
            $operator = func_get_arg(1);
            $value = func_get_arg(2);            
        } 
            
        $this->_runWhere($field, $operator, $value, __METHOD__);

        return $this;
    }

    public function whereOr(): object{
        if(func_num_args() <= 1){
            throw new Exception('Expecting 2 to 3 parameters. Less than 2 parameters encountered.');
        }

        if(func_num_args() == 2){
            $field = func_get_arg(0);
            $operator = '=';
            $value = func_get_arg(1);
        } elseif(func_num_args() == 3) {
            $field = func_get_arg(0);
            $operator = func_get_arg(1);
            $value = func_get_arg(2);            
        }

        $this->_runWhere($field, $operator, $value, __METHOD__);

        return $this; 
    }

    private function _runWhere($field, $operator, $value, $whereMethod): void{

        if($this->whereInstanceCounter > 0){
            if($whereMethod === 'DBORM::where')
                $this->sql .= ' and ';
            elseif($whereMethod === 'DBORM::whereOr')
                $this->sql .= ' or ';

            $this->sql .= $field.' '.$operator.' ?';
        } else {
            $this->sql .= ' where '.$field.' '.$operator.' ?';
        }
        
        $this->valueBag[] = $value;
        $this->whereInstanceCounter++;
    }

    public function showQuery(): string{
        return $this->sql;
    }

    public function showValueBag(): array{
        return $this->valueBag;
    }

    public function insert(Array $values): int{  
        $insertSQL = ' VALUES ('; 

        $numberArrayElements = count($values);

        foreach($values as $value){
            $this->valueBag[] = $value;
        }

        for($counter = 0;$counter < $numberArrayElements; $counter++){
              $insertSQL .= '?';

              if($counter < ($numberArrayElements-1))
                   $insertSQL .= ','; 
        }

        $insertSQL .= ');';

        $this->sql .= 'INSERT INTO '; 
        $this->sql .= $this->table;
        $this->sql .= $insertSQL;

        $result = $this->_executeQuery();

        return $result;
    }

    private function _executeQuery(): int{
        try {
            $valueCounter = 0;

            $dbStatement = $this->db->prepare($this->sql);
            
            $numberOfValuesInValueBag = count($this->valueBag); 
             
            while($valueCounter < $numberOfValuesInValueBag){
                $valueType = gettype($this->valueBag[$valueCounter]);
                switch($valueType){
                    case 'string' : { $pdoType = \PDO::PARAM_STR; break; }
                    case 'integer': { $pdoType = \PDO::PARAM_INT; break; }
                    case 'double' : { $pdoType = \PDO::PARAM_STR; break; }         
                }

                $dbStatement->bindParam($valueCounter+1,$this->valueBag[$valueCounter], $pdoType);
                $valueCounter++;
            }
            
            $dbStatement->execute();
            $this->valueBag = []; 
            $this->sql = '';
            $this->whereInstanceCounter = 0;
            
            return $queryResult = $dbStatement->rowCount();
        } 
        catch(PDOException $e){
            echo $e->getMessage();  
        }
    }

    public function update(Array $values): int{
        if (empty($this->table)) {
            throw new Exception('Table name not specified. Use the table() method first.');
        }
        
        if (empty($values)) {
            throw new Exception('No values provided for update.');
        }
        
        if ($this->whereInstanceCounter == 0) {
            throw new Exception('Update operation requires a WHERE condition to prevent updating all rows.');
        }
        
        $whereStatement =  $this->sql;
        $this->sql = 'UPDATE ' . $this->table . ' SET ';
        $setParts = [];
        $updateValues = [];
    
        foreach ($values as $column => $value) {
            $setParts[] = "$column = ?";
            $updateValues[] = $value;
        }
        
        $this->sql .= implode(', ', $setParts);
        $this->sql .= $whereStatement;

        foreach (array_reverse($updateValues) as $val) {
            array_unshift($this->valueBag, $val);
        }

        $this->sql .= ';';
        return $this->_executeQuery();
    
    }

    public function delete(): int{
        if (empty($this->table)) {
            throw new Exception('Table name not specified.');
        }
        
        if ($this->whereInstanceCounter == 0) {
            throw new Exception('Delete operation requires a WHERE condition to prevent deleting all rows.');
        }

        $whereStatement =  $this->sql;
        $this->sql = 'DELETE FROM ' . $this->table;
        $this->sql .= $whereStatement;

        $this->sql .= ';';
        return $this->_executeQuery();
    }

}