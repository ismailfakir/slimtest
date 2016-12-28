<?php

class PersonsMapper extends Mapper
{
    public function getPersons() {
        $sql = "SELECT * from Persons";
        $stmt = $this->db->query($sql);

        $results = [];
        while($row = $stmt->fetch()) {
            $results[] = new PersonsEntity($row);
        }
        return $results;
    }

    
    public function getPersonById($id) {
        $sql = "SELECT * from Persons where P_Id = :id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(["id" => $id]);

        if($result) {
            return new PersonsEntity($stmt->fetch());
        }

    }

    public function save(PersonsEntity $person) {
        $sql = "INSERT INTO Persons (P_Id, LastName, FirstName, Address, City)
            VALUES (:id, :lastName, :firstName, :address, :city)";
 
        $stmt = $this->db->prepare($sql);
        
        
        $stmt->bindParam(':id',$person->getId());
        $stmt->bindParam(':lastName', $person->getLastName());
        $stmt->bindParam(':firstName', $person->getFirstName());
        $stmt->bindParam(':address', $person->getAddress());
        $stmt->bindParam(':city', $person->getCity());
        
        $result = $stmt->execute();
         

        if(!$result) {
            throw new Exception("could not save record");
        }
    }
}
