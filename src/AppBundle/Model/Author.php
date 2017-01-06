<?php

namespace AppBundle\Model;

class Author
{
    public $id;
    public $firstName;
    public $lastName;

    public function __construct(int $id, String $firstName, String $lastName)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    /**
     * renvoie le nom complet : Bob Lee
     * @return String
     */
    function getName(): String
    {
        return $this->firstName. "." .$this->lastName ;
    }

    /**
     * renvoie le initial du prénom et nom complet : B.Lee
     * @return String
     */
    function getShortName(): String
    {
        return strtoupper($this->firstName[0]). "." .$this->lastName;
    }

    /**
     * renvoie les initiales : B.L
     * @return String
     */
    function getInitial(): String
    {
        return strtoupper( $this->firstName[0]. "." .$this->lastName[0] );
    }

    function __toString()
    {
        return "Author{ id -> $this->id , firstName -> $this->firstName , lastName -> $this->lastName }";
    }
}
?>