<?php

class PersonsEntity implements JsonSerializable
{
    protected $id;
    protected $lastName;
    protected $firstName;
    protected $address;
    protected $city;

    /**
     * Accept an array of data matching properties of this class
     * and create the class
     *
     * @param array $data The data to use to create
     */
    public function __construct(array $data) {
        // no id if we're creating
        /*if(isset($data['P_Id'])) {
            $this->id = $data['P_Id'];
        }*/
        
        $this->id = $data['P_Id'];
        $this->lastName = $data['LastName'];
        $this->firstName = $data['FirstName'];
        $this->address = $data['Address'];
        $this->city = $data['City'];
    }

    public function getId() {
        return $this->id;
    }

    public function getlastName() {
        return $this->lastName;
    }
    
    public function getFirstName() {
        return $this->firstName;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getCity() {
        return $this->city;
    }
    
    public function jsonSerialize() {
            return [
                'P_Id' => $this->id,
                'LastName' => $this->lastName,
                'FirstName' => $this->firstName,
                'Address' => $this->address,
                'City' => $this->city
            ];
        }
}
